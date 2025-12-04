<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $this->fetch('title'); ?> :: Meu Sistema
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->meta('viewport', 'width=device-width, initial-scale=1.0');

		// Carregando o FontAwesome (ícones)
		echo $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
		
		// Carregando o CSS do jQuery UI (para o datetimepicker)
		echo $this->Html->css('https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
		echo $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css');

		// Carregando o NOSSO CSS customizado
		echo $this->Html->css('custom');

		echo $this->fetch('meta');
		echo $this->fetch('css');
	?>
</head>
<body>
    <main class="main-content">
        <!-- O conteúdo específico de cada página será injetado aqui -->
        <?php echo $this->Flash->render(); ?>
        <?php echo $this->fetch('content'); ?>
    </main>
    
	<?php
		// Carregando as bibliotecas JavaScript
		echo $this->Html->script('https://code.jquery.com/jquery-3.6.0.min.js');
		echo $this->Html->script('https://code.jquery.com/ui/1.12.1/jquery-ui.min.js');
		echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js');

		// Espaço para scripts específicos da página
		echo $this->fetch('script'); 
	?>
</body>
</html>