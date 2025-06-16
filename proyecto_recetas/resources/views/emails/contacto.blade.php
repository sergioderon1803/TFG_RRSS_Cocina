<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #F07B3F;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .logo {
            width: 180px;
            height: auto;
            margin-bottom: 15px;
            display: inline-block;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 0 0 5px 5px;
            border: 1px solid #ddd;
        }
        .message {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.8em;
            color: #666;
        }
        .field {
            margin-bottom: 15px;
        }
        .field strong {
            color: #F07B3F;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $message->embed(public_path('images/logo.svg')) }}" alt="WeCook Logo" class="logo">
        <h1>Nuevo Mensaje de Contacto</h1>
    </div>
    
    <div class="content">
        <div class="field">
            <strong>Nombre:</strong> {{ $datos['nombre'] }}
        </div>
        
        <div class="field">
            <strong>Email:</strong> {{ $datos['email'] }}
        </div>
        
        <div class="field">
            <strong>Asunto:</strong> {{ $datos['asunto'] }}
        </div>
        
        <div class="message">
            <strong>Mensaje:</strong><br>
            {{ $datos['mensaje'] }}
        </div>
    </div>
    
    <div class="footer">
        <p>Este mensaje fue enviado desde el formulario de contacto de WeCook</p>
        <small>&copy; {{ date('Y') }} WeCook. Todos los derechos reservados</small>
    </div>
</body>
</html>
                <strong>Asunto:</strong> {{ $datos['asunto'] }}
            </div>
            
            <div class="message">
                <strong>Mensaje:</strong><br>
                {{ $datos['mensaje'] }}
            </div>
        </div>
        
        <div class="footer">
            <p>Este mensaje fue enviado desde el formulario de contacto de WeCook</p>
            <small>&copy; {{ date('Y') }} WeCook. Todos los derechos reservados</small>
        </div>
    </div>
</body>
</html>
