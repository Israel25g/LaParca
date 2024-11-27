import smtplib
from email.message import EmailMessage

# Función para leer datos del archivo en UTF-8
def read_form_data(file_path):
    data = {}
    with open(file_path, 'r', encoding='utf-8') as file:
        for line in file:
            key, value = line.strip().split('=', 1)
            data[key] = value
    return data

# Ruta al archivo que contiene los datos del formulario
file_path = 'form_data_user.txt'

# Leer datos del archivo
form_data = read_form_data(file_path)

# Configuración del correo
email_subject = "Confirmación de recepción del ticket"
sender_email_address = "ticketsprueba1@outlook.com"
email_password = "yiphgjwukvgfkltq"
email_smtp = "smtp-mail.outlook.com"

# Crear el mensaje de correo
message = EmailMessage()
message['Subject'] = email_subject
message['From'] = sender_email_address
message['To'] = form_data['correo'], "israel@iplgsc.com", "alcibiades@iplgsc.com", # Usar el correo del formulario
message.set_content(
    f"Hola {form_data['nombrecompleto']},\n\n"
    f"Gracias por contactarnos, aqui apareceran los datos que nos suministro, para confirmar su correcto envio:\n\n"
    f"Nombre Completo: {form_data['nombrecompleto']}.\n"
    f"Descripción: {form_data['descripcion']}\n"
    f"Departamento: {form_data['ubicacion']}.\n"
    f"Urgencia: {form_data['urgencia']}.\n\n"
    "Atentamente,\nEl departamento de EEMP\n(no responder a este mensaje).",
    charset='utf-8'
)

try:
    # Enviar el correo
    with smtplib.SMTP(email_smtp, 587) as server:
        server.set_debuglevel(1)
        server.ehlo()
        server.starttls()
        server.login(sender_email_address, email_password)
        server.send_message(message)

    print("Correo enviado exitosamente.")  # Mensaje de éxito

except Exception as e:
    print(f"Error al enviar el correo: {e}")  # Mensaje de error con detalles
