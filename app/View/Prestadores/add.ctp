<div class="prestadores form">
<?php echo $this->Form->create('Prestador'); ?>
	<fieldset>
		<legend><?php echo __('Add Prestador'); ?></legend>
	<?php
		echo $this->Form->input('nome');
		echo $this->Form->input('Servico');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Prestadores'), array('action' => 'index')); ?></li>
	</ul>
</div>

