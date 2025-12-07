<?php $this->assign('title', 'Detalhes do Serviço'); ?>
<div class="container">

	<div class="header">
		<div class="header-left">
			<h1>
				<i class="fas fa-tools" style="margin-right: 8px"></i>
				Detalhes do Serviço
			</h1>
			<p>Veja os dados do serviço e prestadores associados.</p>
		</div>
		<?php echo $this->Html->link(
			'<i class="fas fa-home"></i> Home',
			array('controller' => 'prestadores', 'action' => 'index'),
			array('class' => 'btn btn-secondary', 'escape' => false)
		); ?>
		<div class="header-right">
			<?php echo $this->Html->link(
				'<i class="fas fa-arrow-left"></i> Voltar para Serviços',
				array('controller' => 'servicos', 'action' => 'index'),
				array('class' => 'btn btn-secondary', 'escape' => false)
			); ?>
			<?php echo $this->Html->link(
				'<i class="fas fa-pen"></i> Editar',
				array('action' => 'edit', $servico['Servico']['id']),
				array('class' => 'btn btn-save', 'escape' => false)
			); ?>
		</div>
	</div>

	<div class="details-sheet">
		<div class="details-grid">
			<div>
				<div class="details-label">Nome</div>
				<div class="details-value"><?php echo h($servico['Servico']['nome']); ?></div>
			</div>
			<div>
				<div class="details-label">Valor (R$)</div>
				<div class="details-value">
					<?php
						echo isset($servico['Servico']['valor'])
							? number_format($servico['Servico']['valor'], 2, ',', '.')
							: '-';
					?>
				</div>
			</div>
			<div>
				<div class="details-label">Criado em</div>
				<div class="details-value"><?php echo h($servico['Servico']['created']); ?></div>
			</div>
			<div>
				<div class="details-label">Última alteração</div>
				<div class="details-value"><?php echo h($servico['Servico']['modified']); ?></div>
			</div>
		</div>
		<div class="details-block">
			<div class="details-label">Descrição</div>
			<div class="details-observacoes">
				<?php echo nl2br(h($servico['Servico']['descricao'])); ?>
			</div>
		</div>
	</div>

	<div class="form-actions" style="justify-content: flex-start; margin-top:30px;gap:14px;">
		<?php
		echo $this->Form->postLink(
			'<i class="fas fa-trash"></i> Excluir Serviço',
			array('action' => 'delete', $servico['Servico']['id']),
			array(
				'class' => 'btn btn-cancel',
				'escape' => false,
				'style' => 'color:#ef4444;border-color:#ef4444;background:#fff;',
			),
			'Tem certeza que deseja excluir este serviço?'
		);
		echo $this->Html->link(
			'<i class="fas fa-plus"></i> Novo Serviço',
			array('action' => 'add'),
			array('class' => 'btn btn-add', 'escape' => false)
		);
		?>
	</div>
</div>

<style>
.details-sheet {
	background: #fafafa;
	padding: 28px 22px 15px 22px;
	border-radius: 10px;
	box-shadow: 0 2px 8px rgba(0,0,0,0.045);
	margin: 10px 0 20px 0;
}
.details-grid {
	display: flex;
	flex-wrap: wrap;
	gap: 20px 40px;
}
.details-grid > div {
	flex: 1 1 240px;
	min-width: 220px;
	margin-bottom: 12px;
}
.details-label {
	font-size: 13px;
	color: #999;
	font-weight: 500;
	margin-bottom: 3px;
	letter-spacing: .5px;
}
.details-value {
	font-size: 15px;
	color: #333;
	padding-bottom: 2px;
	border-bottom: 1px solid #f0f0f0;
	font-weight: 500;
}
.details-block {
	margin-top: 21px;
}
.details-observacoes {
	background: #fff;
	border-radius: 8px;
	padding: 17px 14px;
	border: 1px solid #eee;
	font-size: 15px;
	color: #444;
	min-height: 44px;
}
.table-related {
	width: 100%;
	border-collapse: separate;
	border-spacing: 0;
	background: #fff;
	box-shadow: 0 2px 8px rgba(0,0,0,0.03);
	border-radius: 8px;
	overflow: hidden;
}
.table-related th, .table-related td {
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
	font-size: 15px;
	margin-right: 8px;
	cursor: pointer;
	padding: 2px 3px;
}
.table-btn:last-child { margin-right: 0 }
@media (max-width: 800px) {
	.details-grid { flex-direction: column; gap: 8px; }
	.details-grid > div { min-width: 100%; }
}
</style>