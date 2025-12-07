<?php $this->assign('title', 'Serviços'); ?>
<div class="container">

	<div class="header">
		<div class="header-left">
			<h1>
				<i class="fas fa-tools" style="margin-right: 8px"></i>
				Lista de Serviços
			</h1>
			<p>Veja e gerencie os serviços cadastrados no sistema.</p>
		</div>
		<?php echo $this->Html->link(
			'<i class="fas fa-home"></i> Home',
			array('controller' => 'prestadores', 'action' => 'index'),
			array('class' => 'btn btn-secondary', 'escape' => false)
		); ?>
		<div class="header-right">
			<?php echo $this->Html->link(
				'<i class="fas fa-plus"></i> Novo Serviço',
				array('action' => 'add'),
				array('class' => 'btn btn-add', 'escape' => false)
			); ?>
		</div>
	</div>

	<div class="details-sheet">
		<div style="overflow-x:auto;">
			<table class="table-related">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
						<th><?php echo $this->Paginator->sort('nome', 'Nome'); ?></th>
						<th><?php echo $this->Paginator->sort('descricao', 'Descrição'); ?></th>
						<th><?php echo $this->Paginator->sort('valor', 'Valor (R$)'); ?></th>
						<th><?php echo $this->Paginator->sort('created', 'Criado em'); ?></th>
						<th><?php echo $this->Paginator->sort('modified', 'Atualizado em'); ?></th>
						<th class="actions">Ações</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($servicos as $servico): ?>
						<tr>
							<td><?php echo h($servico['Servico']['id']); ?></td>
							<td><?php echo h($servico['Servico']['nome']); ?></td>
							<td><?php echo h($servico['Servico']['descricao']); ?></td>
							<td>
								<?php
								echo isset($servico['Servico']['valor'])
									? number_format($servico['Servico']['valor'], 2, ',', '.')
									: '—';
								?>
							</td>
							<td><?php echo h($servico['Servico']['created']); ?></td>
							<td><?php echo h($servico['Servico']['modified']); ?></td>
							<td class="actions">
								<?php
								echo $this->Html->link('<i class="fas fa-eye"></i>', array('action' => 'view', $servico['Servico']['id']), array('escape' => false, 'class' => 'table-btn', 'title' => 'Ver'));
								echo $this->Html->link('<i class="fas fa-edit"></i>', array('action' => 'edit', $servico['Servico']['id']), array('escape' => false, 'class' => 'table-btn', 'title' => 'Editar'));
								echo $this->Form->postLink('<i class="fas fa-trash"></i>', array('action' => 'delete', $servico['Servico']['id']), array('escape' => false, 'class' => 'table-btn', 'title' => 'Excluir', 'confirm' => 'Deseja excluir o serviço?'));
								?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="paging form-actions" style="margin:18px 0 6px 0;justify-content:left;">
		<?php
		echo $this->Paginator->prev('<i class="fas fa-angle-left"></i> anterior', ['escape' => false, 'class' => 'btn btn-cancel'], null, ['class' => 'btn btn-cancel disabled']);
		echo $this->Paginator->numbers(['separator' => ' ', 'tag' => 'span']);
		echo $this->Paginator->next('próxima <i class="fas fa-angle-right"></i>', ['escape' => false, 'class' => 'btn btn-cancel'], null, ['class' => 'btn btn-cancel disabled']);
		?>
	</div>
	<div style="color:#889;margin-bottom:12px;font-size:14px;">
		<?php
		echo $this->Paginator->counter(array(
			'format' => 'Página {:page} de {:pages} • Exibindo {:current} serviços de um total de {:count}'
		));
		?>
	</div>
</div>

<style>
	.details-sheet {
		background: #fafafa;
		padding: 28px 22px 16px 22px;
		border-radius: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.045);
		margin: 10px 0 16px 0;
	}

	.table-related {
		width: 100%;
		border-collapse: separate;
		border-spacing: 0;
		background: #fff;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
		border-radius: 8px;
		overflow: hidden;
	}

	.table-related th,
	.table-related td {
		padding: 9px 10px;
		text-align: left;
		border-bottom: 1px solid #eee;
	}

	.table-related th {
		color: #558;
		font-size: 14px;
		font-weight: 600;
		background: #f8faff;
	}

	.table-related tbody tr:hover td {
		background: #f0f7fa;
	}

	.table-btn {
		display: inline-block;
		border: none;
		background: none;
		color: #3b82f6;
		font-size: 16px;
		margin-right: 8px;
		cursor: pointer;
		padding: 2px 3px;
	}

	.table-btn:last-child {
		margin-right: 0
	}

	.paging .btn {
		margin-right: 4px;
	}

	@media (max-width: 900px) {
		.details-sheet {
			padding: 14px 5px 9px 5px;
		}

		.table-related th,
		.table-related td {
			padding: 6px 5px;
			font-size: 13px;
		}
	}
</style>