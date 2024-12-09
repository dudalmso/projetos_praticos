import pandas as pd
import cx_Oracle
from reportlab.lib.pagesizes import letter
from reportlab.pdfgen import canvas
from reportlab.lib import colors



dsn_tns = cx_Oracle.makedsn("user", "port", service_name="")
conn = cx_Oracle.connect(user="", password="", dsn=dsn_tns)

# Nessas querys faço a captura das informações  e unifico num df final atraves do merge
query = """
    
    """

# Rodar a consulta
df = pd.read_sql(query, conn)

df = df.astype(str)

# ____________ Aqui inicio as formatacoes finais do dataframe

# Elimino colunas que nao vou utilizar para nao pesar o processo
df.drop(labels=["x", "y", "z"], axis=1, inplace=True)


# ________________ Formato datas e valores
df['DT'] = pd.to_datetime(df['DT']).dt.strftime('%d/%m/%Y')

# Formatar a coluna de valor para ter separadores de milhar e vírgula para casas decimais
df['VALOR'] = pd.to_numeric(df['VALOR'], errors='coerce')
df['VALOR'] = df['VALOR'].apply(lambda x: "{:,.2f}".format(x).replace(",", "X").replace(".", ",").replace("X", "."))


# ____________ Aqui inicio as formatacoes do pdf

for index, row in df.iterrows():
    cliente = row['cliente']
    
    # Define o caminho do arquivo PDF com o nome do cliente
    pdf_file_path = f"{cliente}.pdf"
    c = canvas.Canvas(pdf_file_path, pagesize=letter)
    width, height = letter
    
    # Definindo a consulta SQL com o NR_CTR atual
    query4 = f"""
    
    """
    cursor = conn.cursor()
    cursor.execute(query4)
    consulta_result = cursor.fetchall()
    y_position = height - 50  

    # ____________ Aqui só precisa adicionar as colunas do dataframe que voce precisa visualizar antes da tabela 
    # definindo cor da tabela
    c.setStrokeColor(colors.black)
    c.setFillColor(colors.lightgrey)

    # Adicionando informações ao PDF
    c.drawString(50, y_position, f"Cliente: {row['cliente']}")

    y_position -= 10  # Espaço extra antes da tabela

    c.rect(50, y_position, 500, 20, fill=1)  
    c.setFillColor(colors.black)
    c.drawString(55, y_position + 5, "Data")
    c.drawString(200, y_position + 5, "Descrição")
    c.drawString(400, y_position + 5, "Valor")
    
    y_position -= 20  

    # Iterar diretamente sobre os resultados da consulta SQL
    for result in consulta_result:
        data_formatada = pd.to_datetime(result[0], errors='coerce').strftime("%d/%m/%Y")

        c.drawString(55, y_position, data_formatada) 
        
        c.drawString(200, y_position, str(result[1])) 

        # Obter e formatar o valor
        valor = result[2]  # Obter o valor
        if valor is None:
            valor_formatado = "N/A"
        else:
            try:
                valor_float = float(valor)  # Tenta converter para float
                valor_formatado = "R$ " + "{:,.2f}".format(valor_float).replace(",", "X").replace(".", ",").replace("X", ".")
            except ValueError:
                valor_formatado = str(valor)

        # Desenhar o valor formatado
        c.drawString(400, y_position, valor_formatado)  
        
        y_position -= 20  # Ajusta a posição para a próxima linha

        if y_position < 50:
            c.showPage()
            y_position = height - 50  

    # Adiciona uma nova página após cada contrato, caso necessário
    c.showPage()
    c.save()
    print('gerado')

conn.close()

print("PDF gerado com sucesso!")
