<?php $this->assign('title', 'Editar Serviço'); ?>
<div class="container">

	<div class="header">
		<div class="header-left">
			<h1>
				<i class="fas fa-tools" style="margin-right: 8px"></i>
				Editar Serviço
			</h1>
			<p>Modifique os dados do serviço abaixo.</p>
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
		</div>
	</div>

	<?php echo $this->Form->create('Servico', ['class' => 'form-modern']); ?>
	<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>
	<div class="form-grid">
		<div class="form-group">
			<?php echo $this->Form->input('nome', [
				'label' => 'Nome do Serviço',
				'class' => 'form-control'
			]); ?>
		</div>
		<div class="form-group">
			<?php echo $this->Form->input('descricao', [
				'label' => 'Descrição',
				'class' => 'form-control'
			]); ?>
		</div>
		<div class="form-group">
			<?php echo $this->Form->input('valor', [
				'label' => 'Valor (R$)',
				'class' => 'form-control',
				'type' => 'number',
				'step' => '0.01',
				'min' => '0'
			]); ?>
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-save">
			<i class="fas fa-save"></i> Salvar
		</button>
		<?php echo $this->Html->link(
			'<i class="fas fa-times"></i> Cancelar',
			array('controller' => 'servicos', 'action' => 'index'),
			array('class' => 'btn btn-cancel', 'escape' => false)
		); ?>
	</div>
	<?php echo $this->Form->end(); ?>

	<?php echo $this->Flash->render(); ?>
</div>

<div class="form-actions" style="justify-content: flex-start; margin-top: 30px; gap: 12px;">
	<?php
	echo $this->Form->postLink(
		'<i class="fas fa-trash"></i> Excluir serviço',
		array('action' => 'delete', $this->Form->value('Servico.id')),
		array(
			'class' => 'btn btn-cancel',
			'escape' => false,
			'style' => 'color:#ef4444;border-color:#ef4444;background:#fff;margin-bottom:12px;'
		),
		__('Tem certeza que deseja excluir este serviço?')
	);
	?>
</div>

<style>
	.details-sheet {
		background: #fafafa;
		padding: 28px 22px 15px 22px;
		border-radius: 10px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.045);
		margin: 10px 0 20px 0;
	}

	.form-grid {
		display: flex;
		flex-wrap: wrap;
		gap: 20px 40px;
	}

	.form-group {
		flex: 1 1 240px;
		min-width: 220px;
		margin-bottom: 12px;
		display: flex;
		flex-direction: column;
	}

	.form-actions {
		display: flex;
		align-items: center;
		gap: 14px;
		margin-top: 22px;
	}

	.btn-save,
	.btn-cancel,
	.btn-secondary,
	.btn-add {
		display: inline-block;
		border-radius: 6px;
		padding: 10px 18px;
		font-size: 16px;
		border: 1px solid #eee;
		box-shadow: 0 1px 2px rgb(0 0 0 / 7%);
		text-decoration: none;
		transition: 0.13s;
		cursor: pointer;
	}

	.btn-save {
		background: #3b82f6;
		color: #fff;
		border: 1px solid #2563eb;
	}

	.btn-save:hover {
		background: #2563eb;
	}

	.btn-cancel {
		background: #fff;
		color: #ef4444;
		border: 1px solid #ef4444;
	}

	.btn-cancel:hover {
		background: #fbeaea;
	}

	.btn-secondary {
		background: #fff;
		color: #777;
		border: 1px solid #ccc;
	}

	.btn-secondary:hover {
		background: #eff1f3;
	}

	.btn-add {
		background: #22c55e;
		color: #fff;
		border: 1px solid #16a34a;
	}

	.btn-add:hover {
		background: #15803d;
	}

	@media (max-width: 900px) {
		.form-grid {
			flex-direction: column;
			gap: 8px;
		}

		.form-group {
			min-width: 100%;
		}
	}
</style>