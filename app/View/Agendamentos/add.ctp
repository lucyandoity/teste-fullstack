<?php $this->assign('title', 'Novo Agendamento'); ?>
<div class="container">

	<div class="header">
		<div class="header-left">
			<h1>Adicionar Agendamento</h1>
			<p>Cadastre um novo atendimento na agenda</p>
		</div>
		<div class="header-right">
			<?php echo $this->Html->link(
				'<i class="fas fa-calendar"></i> Ver Agenda',
				array('controller' => 'agendamentos', 'action' => 'index'),
				array('class' => 'btn btn-secondary', 'escape' => false)
			); ?>
			<?php echo $this->Html->link(
				'<i class="fas fa-arrow-left"></i> Voltar',
				array('controller' => 'prestadores', 'action' => 'index'),
				array('class' => 'btn btn-secondary', 'escape' => false)
			); ?>
		</div>
	</div>

	<?php echo $this->Form->create('Agendamento', ['class' => 'form-modern']); ?>
	<div class="form-grid">
		<div class="form-group">
			<?php echo $this->Form->input('prestador_id', [
				'label'   => 'Prestador',
				'class'   => 'form-control'
			]); ?>
		</div>
		<div class="form-group">
			<?php echo $this->Form->input('servico_id', [
				'label'  => 'Serviço',
				'class'  => 'form-control'
			]); ?>
		</div>
		<div class="form-group">
			<?php echo $this->Form->input('data_hora_inicio', [
				'type'    => 'text',
				'label'   => 'Início do Atendimento',
				'class'   => 'form-control datetimepicker',
			]); ?>
		</div>
		<div class="form-group">
			<?php echo $this->Form->input('data_hora_fim', [
				'type'    => 'text',
				'label'   => 'Fim do Atendimento',
				'class'   => 'form-control datetimepicker',
			]); ?>
		</div>
		<div class="form-group">
			<?php echo $this->Form->input('nome_cliente', [
				'label' => 'Nome do Cliente',
				'class' => 'form-control'
			]); ?>
		</div>
		<div class="form-group">
			<?php echo $this->Form->input('telefone_cliente', [
				'label' => 'Telefone do Cliente',
				'class' => 'form-control'
			]); ?>
		</div>
		<div class="form-group">
			<?php echo $this->Form->input('status', [
				'label'   => 'Status',
				'class'   => 'form-control',
				'options' => $optionsStatus,
				'empty'   => 'Selecione um status'
			]); ?>
		</div>
		<div class="form-group" style="flex-basis:100%">
			<?php echo $this->Form->input('observacoes', [
				'label' => 'Observações',
				'class' => 'form-control'
			]); ?>
		</div>
	</div>
	<div class="form-actions">
		<?php echo $this->Form->end('<i class="fas fa-save"></i> Salvar'); ?>
		<?php echo $this->Html->link('<i class="fas fa-times"></i> Cancelar', array('controller' => 'agendamentos', 'action' => 'index'), array('class' => 'btn btn-cancel', 'escape' => false)); ?>
	</div>
	<?php echo $this->Flash->render(); ?>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('.datetimepicker').datetimepicker({
			dateFormat: 'dd/mm/yy',
			dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
			dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
			dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
			monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
			monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
			nextText: 'Próximo',
			prevText: 'Anterior',
			timeFormat: 'HH:mm',
			hourText: 'Horas',
			minuteText: 'Minutos',
			timeText: 'Tempo',
			controlType: 'select'
		});
	});
</script>