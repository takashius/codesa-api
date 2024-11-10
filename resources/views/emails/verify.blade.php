<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Correo Electrónico</title>
    <style>
        /* Estilos personalizados para el correo */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            padding: 20px;
            line-height: 1.5;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Verificación de Correo Electrónico</h1>
        </div>
        <div class="content">
            <p>Hola,</p>
            <p>Gracias por registrarte en nuestra plataforma. Por favor, haz clic en el botón de abajo para verificar tu
                correo electrónico.</p>
            <a href="{{ $actionUrl }}" class="button">Verificar Correo Electrónico</a>
            <p>Si no solicitaste esta verificación, puedes ignorar este correo.</p>
            <p>Gracias,</p>
            <p>El equipo de [Tu Empresa]</p>
        </div>
    </div>
</body>

</html>
