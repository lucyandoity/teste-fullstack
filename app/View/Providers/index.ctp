<div class="providers index">
	<h2><?php echo __('Prestadores'); ?></h2>
	<div class="search-box" style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border: 1px solid #e1e1e1; border-radius: 4px;">
		<?php
		echo $this->Form->create('Provider', array('type' => 'get', 'url' => array('action' => 'index')));
		echo $this->Form->input('search', array(
			'label' => false,
			'placeholder' => 'Buscar por nome, email ou telefone...',
			'style' => 'width: 300px; display: inline-block;',
			'value' => $this->request->query('search')
		));
		echo $this->Form->submit(__('Buscar'), array('div' => false, 'style' => 'display: inline-block; margin-left: 10px;'));
		echo $this->Form->end();
		?>
	</div>
	<hr>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
			<th><?php echo $this->Paginator->sort('name', 'Nome'); ?></th>
			<th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
			<th><?php echo $this->Paginator->sort('phone', 'Telefone'); ?></th>
			<th><?php echo __('Serviços'); ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Criado em'); ?></th>
			<th class="actions"><?php echo __('Ações'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($providers as $provider): ?>
	<tr>
		<td><?php echo h($provider['Provider']['id']); ?>&nbsp;</td>
		<td><?php echo h($provider['Provider']['name']); ?>&nbsp;</td>
		<td><?php echo h($provider['Provider']['email']); ?>&nbsp;</td>
		<td><?php echo h($provider['Provider']['phone']); ?>&nbsp;</td>
		<td>
			<?php if (!empty($provider['ProviderService'])): ?>
				<?php
				$serviceNames = array();
				foreach ($provider['ProviderService'] as $ps) {
					if (!empty($ps['Service']['name'])) {
						$serviceNames[] = h($ps['Service']['name']);
					}
				}
				echo implode(', ', $serviceNames);
				?>
			<?php else: ?>
				<em style="color: #999;">Nenhum</em>
			<?php endif; ?>
		</td>
		<td><?php echo h($provider['Provider']['created']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Ver'), array('action' => 'view', $provider['Provider']['id'])); ?>
			<?php echo $this->Html->link(__('Editar'), array('action' => 'edit', $provider['Provider']['id'])); ?>
			<?php echo $this->Form->postLink(__('Excluir'), array('action' => 'delete', $provider['Provider']['id']), array('confirm' => __('Tem certeza que deseja excluir o prestador #%s?', $provider['Provider']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
		'format' => __('Página {:page} de {:pages}, mostrando {:current} registros de {:count} total')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('anterior'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('próximo') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Ações'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Novo Prestador'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Listar Serviços'), array('controller' => 'services', 'action' => 'index')); ?></li>
	</ul>
</div>
