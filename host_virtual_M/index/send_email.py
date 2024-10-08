import smtplib
from email.message import EmailMessage

# import os
# file_path = 'C:/xampp/htdocs/sistema_de_tickets/sistema_de_tickets/host_virtual_M/index/formDataUser.txt'
# os.chdir('C:/xampp/htdocs/sistema_de_tickets/sistema_de_tickets/host_virtual_M/index')  # Cambia el directorio de trabajo a la carpeta del archivo
# print(os.path.exists(file_path))  # Imprime True si el archivo existe, False si no.    

# Función para leer datos del archivo en UTF-8
def read_form_data(file_path):
    data = {}
    with open(file_path, 'r', encoding='utf-8') as file:
        for line in file:
            key, value = line.strip().split('=', 1)
            data[key] = value
    return data

# Ruta al archivo que contiene los datos del formulario
file_path = 'C:/xampp/htdocs/sistema_de_tickets/sistema_de_tickets/host_virtual_M/index/form_data_user.txt'

# Leer datos del archivo
form_data = read_form_data(file_path)

# Configuración del correo para Gmail
email_subject = "Confirmación de recepción del ticket"
sender_email_address = "sistemadiprop@outlook.com"
email_password = "vvvglnmafyngyqlz"  
email_smtp = "smtp.office365.com"
smtp_port = 587

# Crear el mensaje de correo
message = EmailMessage()
message['Subject'] = email_subject
message['From'] = sender_email_address
message['To'] = f"{form_data.get('correo')}, cecilio@iplgsc.com"  # Usar el correo del formulario
message.set_content(
    f"Hola {form_data.get('nombrecompleto', 'Usuario')},\n\n"
    f"Gracias por contactarnos. Aquí aparecen los datos que nos suministró para confirmar su correcto envío:\n\n"
    f"Nombre Completo: {form_data.get('nombrecompleto', 'No proporcionado')}.\n"
    f"Descripción: {form_data.get('descripcion', 'No proporcionado')}\n"
    f"Departamento: {form_data.get('ubicacion', 'No proporcionado')}.\n"
    f"Urgencia: {form_data.get('urgencia', 'No proporcionado')}.\n\n"
    "Atentamente,\nEl departamento de Mantenimiento\n(no responder a este mensaje).",
    charset='utf-8'
)

try:
    # Conectar al servidor SMTP de Gmail
    with smtplib.SMTP(email_smtp, smtp_port) as server:
        server.ehlo()  # Saludo inicial al servidor
        server.starttls()  # Comenzar la conexión segura TLS
        server.ehlo()  # Repetir saludo después de TLS
        server.login(sender_email_address, email_password)  # Iniciar sesión en Gmail
        server.send_message(message)  # Enviar el correo

    print("Correo enviado exitosamente.")  # Mensaje de éxito

except Exception as e:
    print(f"Error al enviar el correo: {e}")  # Mensaje de error con detalles
