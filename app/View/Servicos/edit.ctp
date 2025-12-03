<div class="servicos form">
<?php echo $this->Form->create('Servico'); ?>
	<fieldset>
		<legend><?php echo __('Edit Servico'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('nome');
		echo $this->Form->input('descricao');
		echo $this->Form->input('valor');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Servico.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Servico.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Servicos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Prestadores'), array('controller' => 'prestadores', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Prestadores'), array('controller' => 'prestadores', 'action' => 'add')); ?> </li>
	</ul>
</div>
