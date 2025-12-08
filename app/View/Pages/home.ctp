<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Prestadores de Serviço</title>
    <?php echo $this->Html->css('app'); ?>
    <style>
        body, html {
            background-color: var(--primary-red);
            color: white;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            gap: 32px;
        }

        .title-pg {
            font-size: 3.5rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -1px;
        }

        .btn-start {
            padding: 14px 32px;
            background-color: white;
            color: var(--primary-red);
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-start:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .btn-start:active {
            transform: translateY(0);
        }

        @keyframes fadeToWhite {
            0% {
                background-color: var(--primary-red);
            }
            100% {
                background-color: white;
            }
        }

        body.fade-out {
            animation: fadeToWhite 0.6s ease forwards;
        }

        body.fade-out .btn-start {
            color: white;
        }

        body.fade-out .title-pg {
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title-pg">Olá YouTube!</h1>
        <a href="#" class="btn-start" id="btn-start-link">Começar</a>
    </div>

    <script>
        document.getElementById('btn-start-link').addEventListener('click', function(e) {
            e.preventDefault();
            
            document.body.classList.add('fade-out');
            
            setTimeout(function() {
                window.location.href = '<?php echo $this->Html->url(array("controller" => "ServiceProviders", "action" => "index")); ?>';
            }, 600);
        });
    </script>
</body>
</html>