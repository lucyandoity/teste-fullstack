<div class="services view">
<h2><?php echo __('Serviço do Catálogo'); ?></h2>
	<dl>
		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($service['Service']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Nome'); ?></dt>
		<dd><?php echo h($service['Service']['name']); ?>&nbsp;</dd>

		<dt><?php echo __('Descrição'); ?></dt>
		<dd><?php echo h($service['Service']['description']); ?>&nbsp;</dd>

		<dt><?php echo __('Criado em'); ?></dt>
		<dd><?php echo h($service['Service']['created']); ?>&nbsp;</dd>

		<dt><?php echo __('Atualizado em'); ?></dt>
		<dd><?php echo h($service['Service']['modified']); ?>&nbsp;</dd>
	</dl>

	<h3><?php echo __('Prestadores que Oferecem Este Serviço'); ?></h3>
	<?php if (!empty($service['ProviderService'])): ?>
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th><?php echo __('Prestador'); ?></th>
				<th><?php echo __('Valor'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($service['ProviderService'] as $providerService): ?>
			<tr>
				<td><?php echo $this->Html->link($providerService['Provider']['name'], array('controller' => 'providers', 'action' => 'view', $providerService['Provider']['id'])); ?></td>
				<td><?php echo 'R$ ' . number_format((float)$providerService['value'], 2, ',', '.'); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
	<p class="notice"><?php echo __('Nenhum prestador oferece este serviço ainda.'); ?></p>
	<?php endif; ?>
</div>
<div class="actions">
	<h3><?php echo __('Ações'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Serviço'), array('action' => 'edit', $service['Service']['id'])); ?></li>
		<li><?php echo $this->Form->postLink(__('Excluir Serviço'), array('action' => 'delete', $service['Service']['id']), array('confirm' => __('Tem certeza que deseja excluir o serviço #%s?', $service['Service']['id']))); ?></li>
		<li><?php echo $this->Html->link(__('Listar Serviços'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Novo Serviço'), array('action' => 'add')); ?></li>
	</ul>
</div>
