import smtplib
from email.message import EmailMessage
import sys

def send_email():
    # Leer datos del archivo en UTF-8
    file_path = 'form_data_Admin.txt'
    try:
        with open(file_path, 'r', encoding='utf-8') as file:
            form_data = {}
            for line in file:
                key, value = line.strip().split('=', 1)
                form_data[key] = value
    except FileNotFoundError:
        print("Error: El archivo de datos del formulario no se encontró.")
        return 1
    except Exception as e:
        print(f"Error: {e}")
        return 1

    # Obtener el id y el estado del ticket
    ticket_id = form_data.get('id', 'desconocido')
    ticket_estado = form_data.get('estado', 'desconocido')

    # Configuración del correo
    email_subject = f"El ticket # {ticket_id} se encuentra {ticket_estado}"
    sender_email_address = "ticketsprueba1@outlook.com"
    email_password = "yiphgjwukvgfkltq"
    email_smtp = "smtp-mail.outlook.com"

    # Crear el mensaje de correo con codificación UTF-8
    message = EmailMessage()
    message['Subject'] = email_subject
    message['From'] = sender_email_address
    message['To'] = form_data.get('correo')  # Usar el correo del formulario
    message['Cc'] = sender_email_address  # Enviar una copia visible al remitente
    message.set_content(
        f"Hola {form_data.get('nombrecompleto', 'usuario')},\n\n"
        f"Gracias por contactarnos. Aquí está la respuesta al ticket que enviaste:\n\n"
        f"Nombre Completo: {form_data.get('nombrecompleto', 'desconocido')}\n"
        f"Respuesta: {form_data.get('respuesta', 'no disponible')}\n"
        f"Estado del ticket: {form_data.get('estado', 'no disponible')}\n\n"
        "Atentamente,\nEl departamento de Mantenimiento\n(No responder a este mensaje).",
        charset='utf-8'  # Especificar la codificación utf-8
    )

    try:
        # Enviar el correo
        with smtplib.SMTP(email_smtp, 587) as server:
            server.set_debuglevel(1)  # Activar el debug si necesitas ver más detalles
            server.ehlo()
            server.starttls()
            server.login(sender_email_address, email_password)
            server.send_message(message)
        print("Correo enviado exitosamente.")
        return 0  # Indica éxito
    except smtplib.SMTPAuthenticationError:
        print("Error: Fallo en la autenticación.")
        return 1
    except smtplib.SMTPRecipientsRefused:
        print("Error: El destinatario fue rechazado.")
        return 1
    except smtplib.SMTPException as e:
        print(f"Error SMTP: {e}")
        return 1
    except Exception as e:
        print(f"Error inesperado: {e}")
        return 1

if __name__ == "__main__":
    sys.exit(send_email())
