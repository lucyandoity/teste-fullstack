<?php $this->assign('title', 'Detalhes do Agendamento'); ?>
<div class="container">

	<div class="header">
		<div class="header-left">
			<h1>
				<i class="fas fa-calendar-check" style="margin-right: 8px"></i>
				Detalhes do Agendamento
			</h1>
			<p>Veja todas as informações deste atendimento.</p>
		</div>
		<div class="header-right">
			<?php echo $this->Html->link(
				'<i class="fas fa-arrow-left"></i> Voltar para Agenda',
				array('controller' => 'agendamentos', 'action' => 'index'),
				array('class' => 'btn btn-secondary', 'escape' => false)
			); ?>
			<?php echo $this->Html->link(
				'<i class="fas fa-pen"></i> Editar',
				array('action' => 'edit', $agendamento['Agendamento']['id']),
				array('class' => 'btn btn-save', 'escape' => false)
			); ?>
		</div>
	</div>

	<div class="details-sheet">
		<div class="details-grid">
			<div>
				<div class="details-label">Prestador</div>
				<div class="details-value">
					<?php echo h($agendamento['Prestador']['nome']) ?>
				</div>
			</div>

			<div>
				<div class="details-label">Serviço</div>
				<div class="details-value">
					<?php
						echo $this->Html->link(
							h($agendamento['Servico']['nome']),
							array('controller' => 'servicos', 'action' => 'view', $agendamento['Servico']['id']),
							array('class' => 'details-link')
						);
					?>
				</div>
			</div>

			<div>
				<div class="details-label">Início</div>
				<div class="details-value"><?php echo h($agendamento['Agendamento']['data_hora_inicio']); ?></div>
			</div>

			<div>
				<div class="details-label">Fim</div>
				<div class="details-value"><?php echo h($agendamento['Agendamento']['data_hora_fim']); ?></div>
			</div>

			<div>
				<div class="details-label">Cliente</div>
				<div class="details-value"><?php echo h($agendamento['Agendamento']['nome_cliente']); ?></div>
			</div>

			<div>
				<div class="details-label">Telefone</div>
				<div class="details-value"><?php echo h($agendamento['Agendamento']['telefone_cliente']); ?></div>
			</div>

			<div>
				<div class="details-label">Status</div>
				<div class="details-value">
					<?php echo h($agendamento['Agendamento']['status']); ?>
				</div>
			</div>

			<div>
				<div class="details-label">Criado em</div>
				<div class="details-value"><?php echo h($agendamento['Agendamento']['created']); ?></div>
			</div>

			<div>
				<div class="details-label">Última alteração</div>
				<div class="details-value"><?php echo h($agendamento['Agendamento']['modified']); ?></div>
			</div>
		</div>
		<div class="details-block">
			<div class="details-label">Observações</div>
			<div class="details-observacoes">
				<?php echo nl2br(h($agendamento['Agendamento']['observacoes'])); ?>
			</div>
		</div>
	</div>

	<div class="form-actions" style="justify-content: flex-start; margin-top: 30px; gap: 12px;">
		<?php
		echo $this->Form->postLink(
			'<i class="fas fa-trash"></i> Excluir',
			array('action' => 'delete', $agendamento['Agendamento']['id']),
			array(
				'class' => 'btn btn-cancel',
				'escape' => false,
				'style' => 'color:#ef4444;border-color:#ef4444;background:#fff;',
			),
			'Tem certeza que deseja excluir este agendamento?'
		);
		echo $this->Html->link(
			'<i class="fas fa-calendar"></i> Novo Agendamento',
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
.details-link {
	color: #3b82f6; text-decoration: none;
}
.details-link:hover { text-decoration: underline; }
.details-block {
	margin-top: 22px;
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
@media (max-width: 800px) {
	.details-grid { flex-direction: column; gap: 8px; }
	.details-grid > div { min-width: 100%; }
}
</style>