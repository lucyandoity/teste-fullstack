<div class="servicos view">
<h2><?php echo __('Servico'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($servico['Servico']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nome'); ?></dt>
		<dd>
			<?php echo h($servico['Servico']['nome']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descricao'); ?></dt>
		<dd>
			<?php echo h($servico['Servico']['descricao']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Valor'); ?></dt>
		<dd>
			<?php echo h($servico['Servico']['valor']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($servico['Servico']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($servico['Servico']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Servico'), array('action' => 'edit', $servico['Servico']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Servico'), array('action' => 'delete', $servico['Servico']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $servico['Servico']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Servicos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Servico'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Prestadores'), array('controller' => 'prestadores', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Prestadores'), array('controller' => 'prestadores', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Prestadores'); ?></h3>
	<?php if (!empty($servico['Prestadore'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Nome'); ?></th>
		<th><?php echo __('Sobrenome'); ?></th>
		<th><?php echo __('Email'); ?></th>
		<th><?php echo __('Telefone'); ?></th>
		<th><?php echo __('Foto'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($servico['Prestadore'] as $prestadore): ?>
		<tr>
			<td><?php echo $prestadore['id']; ?></td>
			<td><?php echo $prestadore['nome']; ?></td>
			<td><?php echo $prestadore['sobrenome']; ?></td>
			<td><?php echo $prestadore['email']; ?></td>
			<td><?php echo $prestadore['telefone']; ?></td>
			<td><?php echo $prestadore['foto']; ?></td>
			<td><?php echo $prestadore['created']; ?></td>
			<td><?php echo $prestadore['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'prestadores', 'action' => 'view', $prestadore['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'prestadores', 'action' => 'edit', $prestadore['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'prestadores', 'action' => 'delete', $prestadore['id']), array('confirm' => __('Are you sure you want to delete # %s?', $prestadore['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Prestadores'), array('controller' => 'prestadores', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
