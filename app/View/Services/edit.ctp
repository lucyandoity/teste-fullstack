<div class="row justify-content-center">
    <div class="col-lg-12">

        <div class="card-doity">
            <h2 class="fw-bold mb-1 fs-3">Adicionar Serviço ao Catálogo</h2>

            <div class="mt-4 mb-4">
                <h5 class="fw-bold mb-1" style="font-size: 1.1rem;">Dados do Serviço</h5>
                <p class="text-muted small">Cadastre um novo tipo de serviço para ser ofertado.</p>
            </div>

            <hr class="my-4 text-muted" style="opacity: 0.1">

            <?php echo $this->Form->create('Service', array('inputDefaults' => array('div' => false))); ?>
			<?php echo $this->Form->input('id'); ?>

            <div class="row mb-4 align-items-center">
                <label class="col-md-3 form-label text-dark fw-medium mb-0">Nome</label>
                <div class="col-md-7">
                    <?php echo $this->Form->input('name', array(
                        'label' => false,
                        'class' => 'form-control',
                        'placeholder' => 'Ex: Manutenção de Ar Condicionado',
                        'required' => true
                    )); ?>
                </div>
            </div>

            <hr class="my-4 text-muted" style="opacity: 0.1">

            <div class="row mb-4 align-items-start">
                <label class="col-md-3 form-label text-dark fw-medium mb-0 pt-2">Descrição</label>
                <div class="col-md-7">
                    <?php echo $this->Form->input('description', array(
                        'label' => false,
                        'class' => 'form-control',
                        'rows' => 4,
                        'placeholder' => 'Descreva detalhes padrão deste serviço (opcional)...'
                    )); ?>
                </div>
            </div>

            <hr class="my-5 text-muted" style="opacity: 0.1">

            <div class="d-flex justify-content-end gap-3">
                <a href="<?php echo $this->Html->url(array('action' => 'index')); ?>" class="btn btn-light border bg-white text-secondary">Cancelar</a>
                <button type="submit" class="btn btn-danger px-4">Salvar</button>
            </div>

            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
