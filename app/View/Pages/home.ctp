<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Prestadores de Serviço</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            text-align: center;
            padding: 40px;
        }

        .hero {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
        }

        .hero h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .hero .subtitle {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .hero p {
            color: #777;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-3px);
        }

        .features {
            display: flex;
            gap: 30px;
            margin-top: 40px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .feature {
            text-align: center;
        }

        .feature-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .feature span {
            color: #555;
            font-size: 0.9rem;
        }

        .footer {
            margin-top: 30px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero">
            <h1>🛠️ Prestadores de Serviço</h1>
            <p class="subtitle">Sistema de Cadastro e Gerenciamento</p>
            <p>
                Gerencie seus prestadores de serviço de forma simples e eficiente. 
                Cadastre, edite e organize todos os profissionais em um só lugar.
            </p>
            
            <div class="buttons">
                <?php echo $this->Html->link('Ver Prestadores', 
                    array('controller' => 'service_providers', 'action' => 'index'), 
                    array('class' => 'btn btn-primary')
                ); ?>
                <?php echo $this->Html->link('Novo Cadastro', 
                    array('controller' => 'service_providers', 'action' => 'create'), 
                    array('class' => 'btn btn-secondary')
                ); ?>
            </div>

            <div class="features">
                <div class="feature">
                    <div class="feature-icon">📋</div>
                    <span>Cadastro Completo</span>
                </div>
                <div class="feature">
                    <div class="feature-icon">🔍</div>
                    <span>Busca Rápida</span>
                </div>
                <div class="feature">
                    <div class="feature-icon">📷</div>
                    <span>Upload de Fotos</span>
                </div>
            </div>
        </div>

        <p class="footer">
            Desenvolvido com CakePHP 2 &bull; <?php echo date('Y'); ?>
        </p>
    </div>
</body>
</html>