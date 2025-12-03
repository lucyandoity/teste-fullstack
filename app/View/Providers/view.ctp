<div class="providers view">
<h2><?php echo __('Provider'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($provider['Provider']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($provider['Provider']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($provider['Provider']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone'); ?></dt>
		<dd>
			<?php echo h($provider['Provider']['phone']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Photo'); ?></dt>
		<dd>
			<?php echo h($provider['Provider']['photo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($provider['Provider']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($provider['Provider']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Provider'), array('action' => 'edit', $provider['Provider']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Provider'), array('action' => 'delete', $provider['Provider']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $provider['Provider']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Providers'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Provider'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Services'), array('controller' => 'services', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Service'), array('controller' => 'services', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Services'); ?></h3>
	<?php if (!empty($provider['Service'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Provider Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Value'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($provider['Service'] as $service): ?>
		<tr>
			<td><?php echo $service['id']; ?></td>
			<td><?php echo $service['provider_id']; ?></td>
			<td><?php echo $service['name']; ?></td>
			<td><?php echo $service['description']; ?></td>
			<td><?php echo $service['value']; ?></td>
			<td><?php echo $service['created']; ?></td>
			<td><?php echo $service['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'services', 'action' => 'view', $service['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'services', 'action' => 'edit', $service['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'services', 'action' => 'delete', $service['id']), array('confirm' => __('Are you sure you want to delete # %s?', $service['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Service'), array('controller' => 'services', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
