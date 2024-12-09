import random
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from datetime import datetime, timedelta
import os
import re
from sqlalchemy import create_engine, text
import mysql.connector
import time
import locale
from email.mime.base import MIMEBase
from email import encoders
import pandas as pd
import subprocess
from pathlib import Path
from io import StringIO

locale.setlocale(locale.LC_ALL, 'pt_BR.UTF-8')

# Função para obter informações dos usuários
def obter_informacoes_usuarios(usuarios):
    data = []
    for usuario in usuarios:
        try:
            output = os.popen(f'net user {usuario} /domain').read()
            senha_expiracao = re.findall(r'A\ssenha\sexpira\s+(\d{2}/\d{2}/\d{4})', output, re.DOTALL)
            data.append({'usuario': usuario, 'Senha_expiracao': senha_expiracao[0] if senha_expiracao else 'sem dados'})
        except Exception:
            data.append({'usuario': usuario, 'Senha_expiracao': 'sem dados'})
    return data

# Função para enviar e-mail
def enviar_email(smtp_server, smtp_port, email_from, email_to, subject, body, username, password, arquivo_anexo):
    try:
        msg = MIMEMultipart()
        msg['From'] = email_from
        msg['To'] = email_to
        msg['Subject'] = subject
        msg.attach(MIMEText(body, 'html'))  

        if arquivo_anexo:
            with open(arquivo_anexo, 'rb') as file:
                part = MIMEBase('application', 'octet-stream')
                part.set_payload(file.read())
                encoders.encode_base64(part)
                part.add_header(
                    'Content-Disposition',
                    f'attachment; filename={arquivo_anexo}'
                )
                msg.attach(part)
        server = smtplib.SMTP(smtp_server, smtp_port)
        server.starttls()
        server.login(username, password)
        server.send_message(msg)
        time.sleep(20)
        server.quit()
        print(f"E-mail enviado com sucesso para {email_to}")
    except Exception as e:
        print(f"Erro ao enviar e-mail para {email_to}: {e}")

# Função para enviar e-mails em lotes
def enviar_email_em_lotes(smtp_server, smtp_port, email_from, destinatarios, subject, body, username, password, arquivo_anexo, lote_size=10):
    lotes = [destinatarios[i:i + lote_size] for i in range(0, len(destinatarios), lote_size)]
    for lote in lotes:
        email_to = ";".join(lote)
        time.sleep(10) 
        enviar_email(smtp_server, smtp_port, email_from, email_to, subject, body, username, password, arquivo_anexo)

def save_encrypted_excel(data, filename, password):
    current_dir = Path(os.getcwd())
    save_path = current_dir / filename
    temp_path = save_path.parent / f"temp_{filename}"

    if isinstance(data, pd.DataFrame):
        data.to_excel(temp_path, index=False)
    else:
        raise ValueError("Os dados fornecidos devem ser um pandas DataFrame.")

    vbs_script = f"""
    Set excel_object = CreateObject("Excel.Application")
    Set workbook = excel_object.Workbooks.Open("{temp_path}")

    excel_object.DisplayAlerts = False
    excel_object.Visible = False

    workbook.Password = "{password}"
    workbook.SaveAs "{save_path}", 51, "{password}", "{password}", False, False

    excel_object.Quit
    """
    vbs_script_path = save_path.parent / "encrypt_save.vbs"

    with open(vbs_script_path, "w") as file:
        file.write(vbs_script)

    subprocess.call(['cscript.exe', str(vbs_script_path)])
    vbs_script_path.unlink()
    temp_path.unlink()
    return save_path.name


# ---- Atualizar Geral ----------- #
now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

conn = ""  # Sua  conexão
engine = create_engine(conn)


try:
    query = """
        SELECT /*PARALLEL*/
        colunas 
        FROM tbl_ficticia
        WHERE sit = 'ATIVO'
        AND senha IS NOT NULL;
    """
    df = pd.read_sql(query, engine)
except Exception as err:
    print(f"Erro ao conectar ao banco de dados: {err}")
    exit()

usuarios2 = df['usuario'].tolist()
informacoes2 = obter_informacoes_usuarios(usuarios2)
df_informacoes2 = pd.DataFrame(informacoes2)

df_informacoes2['Senha_expiracao'] = pd.to_datetime(df_informacoes2['Senha_expiracao'], format='%d/%m/%Y', errors='coerce')
df_informacoes2['Senha_expiracao'] = df_informacoes2['Senha_expiracao'].dt.strftime('%Y-%m-%d')

df2 = pd.merge(df, df_informacoes2, on='usuario', how='left')
df2['dt_senha_expiracao'] = df2['Senha_expiracao']
df2.drop(columns=['Senha_expiracao'], inplace=True)

df2['dt_senha_expiracao'] = pd.to_datetime(df2['dt_senha_expiracao'], errors='coerce')
df2['dt_senha_expiracao'] = df2['dt_senha_expiracao'].dt.strftime('%Y-%m-%d')

with engine.connect() as conn:
    for index, row in df2.iterrows():
        usuario2 = row['usuario']
        dt_senha_expiracao = row['dt_senha_expiracao']

        query2 = text("""
            UPDATE tbl_ficticia
            SET dt_senha_expira = :dt_senha_expira, dt_atualizacao = :now
            WHERE usuario2 = :usuario2
        """)
        try:
            conn.execute(query2, {
                'dt_senha_expira': dt_senha_expiracao,
                'now': now,
                'usuario2': usuario2
            }              
            )
            # commitar as alterações
            conn.commit()
            print("Atualização realizada com sucesso para a matrícula:", usuario2)

        except Exception as err:
            print(f"Erro ao atualizar para a matrícula {usuario2}: {err}")
            print('\n')
            print(query2)
            

# Data de hoje
hoje = datetime.today().date()

# Calcular o início da semana (domingo)
inicio_semana = datetime.combine(hoje - timedelta(days=hoje.weekday() + 1 if hoje.weekday() != 6 else 0), datetime.min.time())

# Calcular o fim do período (segunda-feira da próxima semana)
fim_semana = inicio_semana + timedelta(days=8)

# Exemplo de conversão da coluna `dt_senha_expiracao` para datetime
df2['dt_senha_expiracao'] = pd.to_datetime(df2['dt_senha_expiracao'], errors='coerce')

# Filtrar os dados com base no intervalo
df = df2[(df2['dt_senha_expiracao'] >= inicio_semana) & 
         (df2['dt_senha_expiracao'] <= fim_semana) & 
         df2['senha'].notnull() & (df2['senha'] != '')]


df2['dt_senha_expiracao'] = pd.to_datetime(df2['dt_senha_expiracao'], errors='coerce')

df = df2[(df2['dt_senha_expiracao'] >= inicio_semana) & 
         (df2['dt_senha_expiracao'] <= fim_semana) & 
         df2['senha'].notnull() & (df2['senha'] != '')]


if df.empty:
    print("Nenhuma senha para atualizar esta semana.")
    exit()

def alterar_senha(senha):
    novos_numeros = ''.join([str(random.randint(0, 9)) for _ in range(4)])
    if len(senha) > 4:
        return senha[:-4] + novos_numeros
    else:
        return novos_numeros

df['nova_senha'] = df['senha'].apply(alterar_senha)
df.dropna(subset=['senha'], inplace=True)
df = df[df['senha'] != '']

entrada = df[['usuario', 'senha', 'nova_senha']]
entrada.to_csv(r'entrada.txt', sep=';', index=False, header=False)

os.system(r'Change_p.exe')
time.sleep(20)

# Atualizar senhas no banco de dados
def atualizar_senhas_no_banco(arquivo_sucesso, conn):
    try:
        with open(arquivo_sucesso, 'r') as file:
            linhas = file.readlines()

        for linha in linhas:
            try:
                usuario, nova_senha = linha.strip().split(';')
                data_atual = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

                query_update = text("""
                    UPDATE tbl_ficticia
                    SET senha = :nova_senha,  dt_atualizacao = :data_atual
                    WHERE usuario = :usuario
                """)

                # Execute a consulta com os valores como um dicionário
                conn.execute(query_update, {
                    'nova_senha': nova_senha,
                    'data_atual': data_atual,
                    'usuario': usuario
                })
                conn.commit()

                print(f"Senha atualizada para matrícula: {usuario}")

            except ValueError as ve:
                print(f"Erro ao processar a linha: {linha.strip()}, erro: {ve}")

    except FileNotFoundError:
        print(f"Arquivo {arquivo_sucesso} não encontrado.")


with engine.connect() as conn:
    atualizar_senhas_no_banco('saida_sucesso.txt', conn)

# Envio individual
if not df.empty:
    for index, row in df.iterrows():
        # Destinatário
        destinatarios = [row['email']]

        df = pd.DataFrame([row])
        arquivo_anexo = save_encrypted_excel(df, "senhas_atualizada.xlsx", "1234")

        body_df = f"""
        <p>Boa noite!</p>
        <p>Segue em anexo arquivo para controle.</p>

        <p>Atenciosamente,</p>
        """
        enviar_email_em_lotes("mail.server.com.br", port , "emailremetente@gmail.com", destinatarios, "Atualização de Senhas", body_df, "usuario", "password", arquivo_anexo)
        print(f"E-mail enviado para: {destinatarios}")


conn.close()
print("Conexão encerrada.")
