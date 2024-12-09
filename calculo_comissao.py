import locale
from datetime import datetime, timedelta, date
import cx_Oracle
import pandas as pd
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.mime.application import MIMEApplication  

try:
    # definindo local para brasil, para que em mes ele coloque o nome em portugues
    locale.setlocale(locale.LC_TIME, 'pt_BR.UTF-8')

    # Obtém a data atual
    data_atual = datetime.now().date()

    # Calcula o mês anterior
    mes_anterior = (data_atual.replace(day=1) - timedelta(days=1)).strftime('%B')
    ano_atual = str(data_atual.year % 100).zfill(2) 
    data_atual = data_atual.strftime('%d_%m_%y')

    #conexao
    dsn_tns = cx_Oracle.makedsn('', '', service_name='')
    conn = cx_Oracle.connect(user='', password='', dsn=dsn_tns)

    # Iniciando query
    query = """*
    """

    # Rodar a consulta
    df = pd.read_sql(query, conn)

    # Aplicando a regex para substituir
    df['TX_TYPE'] = df['TX_TYPE'].str.replace(r'\(.*$', '', regex=True)
    df['VLR_BOL'] = df['VLR_BOL'].apply(lambda x: "{:,.2f}".format(x).replace('.', '#').replace(',', '.').replace('#', ','))
    df['VL_PARC'] = df['VL_PARC'].apply(lambda x: "{:,.0f}".format(x).replace('.', '#').replace(',', '.').replace('#', ','))

    # Formate a data no formato desejado
    df['DT_PG_BOL'] = df['DT_PG_BOL'].dt.strftime('%d/%m/%Y')
    df['DT_EMISSAO'] = df['DT_EMISSAO'].dt.strftime('%d/%m/%Y')
    df['DT_VENC_BOLT'] = df['DT_VENC_BOLT'].dt.strftime('%d/%m/%Y')

    # Excluindo colunas
    df.drop(labels=["COD_ACR"], axis=1, inplace=True)
    # Substituir os caracteres incorretos
    df['PRODUTO'] = df['PRODUTO'].str.replace('¿¿', 'é')

    # Definindo função para criacao da coluna de PCGT_COMISSAO que significa (porcentagem comissao)
    def calcular_porcentagem_comissao(dias):
        if dias >= 0 and dias <= 30:
            return 0.03
        elif dias >= 31 and dias <= 60:
            return 0.05
        elif dias >= 61 and dias <= 90:
            return 0.07
        elif dias >= 91 and dias <= 180:
            return 0.09
        elif dias >= 181 and dias <= 364:
            return 0.15
        elif dias >= 365 and dias <= 730:
            return 0.22
        elif dias >= 731 and dias <= 1095:
            return 0.25
        elif dias >= 1096 and dias <= 9999:
            return 0.34
        else:
            return 0

    # Aplicar a função ao DataFrame para criar a coluna 'PCTG_COMISSAO'
    df['PCTG_COMISSAO'] = df['FAIXA_ATRASO'].apply(calcular_porcentagem_comissao)

    # Converter 'PCTG_COMISSAO' para tipo numérico
    df['PCTG_COMISSAO'] = pd.to_numeric(df['PCTG_COMISSAO'])

    # Remover pontos de milhar, depois vírgulas e converter 'VLR_PARCELA' para tipo numérico
    df['VLR_PARCELA'] = df['VLR_PARCELA'].str.replace('.', '').str.replace(',', '.').astype(float)

    # Calcular a coluna 'VLR_COMISSAO'
    df['VLR_COMISSAO'] = df['VLR_PARCELA'] * df['PCTG_COMISSAO']

    # Multiplicar por 100 e remover um zero da coluna 'PCTG_COMISSAO' e adicionando o %
    df['PCTG_COMISSAO'] = (df['PCTG_COMISSAO'] * 100).astype(int) // 1
    df['PCTG_COMISSAO'] = df['PCTG_COMISSAO'].astype(str) + '%'

    # Formatar 'VLR_COMISSAO' como valores em reais com vírgula
    df['VLR_COMISSAO'] = df['VLR_COMISSAO'].apply(lambda x: f"{x:,.2f}".replace('.', ','))

    # convertendo coluna para numero
    df['VLR_COMISSAO'] = pd.to_numeric(df['VLR_COMISSAO'].str.replace(',', '.'), errors='coerce')
    df['CNPJ'] = pd.to_numeric(df['CNPJ'], errors='coerce')
    df['QTD_PARCELA'] = pd.to_numeric(df['QTD_PARCELA'], errors='coerce')
    df['NR_PARCELA'] = pd.to_numeric(df['NR_PARCELA'], errors='coerce')

    #definindo nome do arquivo em variavel e salvando
    arquivo = f"Analitico_Comissão_{data_atual}.xlsx"
    df.to_excel(arquivo, index=False)

    # Configuração do servidor SMTP
    smtp_server = "mail"
    smtp_port = 123

    # Credenciais e armazenando valores em variaveis para envio dos emails
    from_address = "pessoal@gmail.com"
    to_address = "destino@gmail.com"
    subject = "teste"
    message = f""" 
            Bom dia!


            Em anexo o comissionamento de {mes_anterior}/{ano_atual}.
                
            
            Atenciosamente,

            Gerência
    """

    # Criação do objeto MIMEMultipart
    msg = MIMEMultipart()
    msg['From'] = from_address
    msg['To'] = to_address
    msg['Subject'] = subject

    # Anexar a mensagem ao objeto msg
    msg.attach(MIMEText(message, 'plain'))

    # Abrir o arquivo e ler seu conteúdo
    with open(arquivo, "rb") as file:
        part = MIMEApplication(file.read(), Name=arquivo)

    # Adiciona o cabeçalho do anexo
    part['Content-Disposition'] = f'attachment; filename="{arquivo}"'
    msg.attach(part)


    server = smtplib.SMTP(smtp_server, smtp_port)
    server.starttls()  # Iniciar TLS
    server.login('s_sig_financeira', 'S1i2g3f4i5n')    
    server.send_message(msg)
    server.quit()
    print(f"E-mail enviado para {to_address}")
    pass
except (ValueError, KeyError):  # Use parênteses para capturar múltiplas exceções
    pass
