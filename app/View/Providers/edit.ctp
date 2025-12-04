<div class="row justify-content-center">
    <div class="col-lg-10">

        <div class="card-doity">
            <h2 class="fw-bold mb-1 fs-3">Editar Prestador de Serviço</h2>

            <div class="mt-4 mb-4">
                <h5 class="fw-bold mb-1" style="font-size: 1.1rem;">Informações pessoais</h5>
                <p class="text-muted small">Atualize as informações do prestador.</p>
            </div>

            <hr class="my-4 text-muted" style="opacity: 0.1">

            <?php echo $this->Form->create('Provider', array('type' => 'file', 'inputDefaults' => array('div' => 'mb-4'))); ?>
            <?php echo $this->Form->input('id'); ?>

            <div class="row">
                <div class="col-md-6">
                    <?php echo $this->Form->input('name', array(
                        'label' => 'Nome',
                        'class' => 'form-control',
                        'placeholder' => 'Eduardo'
                    )); ?>
                </div>
                <div class="col-md-6">
                    <?php echo $this->Form->input('email', array(
                        'label' => 'Email',
                        'class' => 'form-control',
                        'placeholder' => 'eduardo@doity.com.br'
                    )); ?>
                </div>
            </div>

            <div class="row mb-4 align-items-start mt-2">
                <div class="col-md-2">
                    <label class="form-label">Sua foto</label>
                    <div class="text-muted small" style="font-size: 0.75rem;">Ela aparecerá no seu perfil.</div>
                </div>
                <div class="col-md-10">
                    <div class="d-flex align-items-center gap-3">
                        <?php if (!empty($this->request->data['Provider']['photo'])): ?>
                            <img src="<?php echo $this->Html->url('/img/' . $this->request->data['Provider']['photo']); ?>"
                                 alt="Foto atual" class="rounded-circle" style="width: 64px; height: 64px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-secondary" style="width: 64px; height: 64px;">
                                <i class="bi bi-person fs-3"></i>
                            </div>
                        <?php endif; ?>
                        <div class="flex-grow-1 p-4 border border-dashed rounded text-center bg-white" style="border: 1px dashed #d0d5dd;">
                            <div class="d-flex flex-column align-items-center">
                                <i class="bi bi-cloud-upload fs-4 text-secondary mb-2"></i>
                                <div class="small text-muted mb-1">
                                    <span class="fw-bold text-danger">Clique para enviar</span> ou arraste e solte
                                </div>
                                <div class="text-muted" style="font-size: 0.7rem;">SVG, PNG, JPG or GIF (max. 800x400px)</div>
                                <?php echo $this->Form->input('photo', array('type' => 'file', 'label' => false, 'class' => 'form-control mt-2', 'style' => 'opacity: 0.5; width: 80%;')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <?php echo $this->Form->input('phone', array(
                    'label' => 'Telefone',
                    'class' => 'form-control',
                    'placeholder' => '(_) ___-___'
                )); ?>
            </div>

            <hr class="my-5 text-muted" style="opacity: 0.1">

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0" style="font-size: 1.1rem;">Serviços Prestados</h5>
                    <button type="button" id="btn-add-service" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-plus-lg me-1"></i> Adicionar outro serviço
                    </button>
                </div>

                <div id="services-container">
                    <?php if (!empty($this->request->data['ProviderService'])): ?>
                        <?php foreach ($this->request->data['ProviderService'] as $index => $providerService): ?>
                            <div class="service-item card-doity p-3 mb-3 bg-light border-0" style="position: relative;">
                                <?php if ($index > 0): ?>
                                    <button type="button" class="btn-remove-service btn btn-sm text-danger position-absolute top-0 end-0 mt-2 me-2" title="Remover este serviço">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                <?php endif; ?>

                                <?php echo $this->Form->hidden("ProviderService.{$index}.id"); ?>

                                <div class="row <?php echo ($index > 0) ? 'mt-2' : ''; ?>">
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label small fw-bold">Qual serviço você vai prestar?</label>
                                        <?php echo $this->Form->input("ProviderService.{$index}.service_id", array(
                                            'label' => false,
                                            'class' => 'form-select',
                                            'options' => $services,
                                            'empty' => 'Selecione o serviço...'
                                        )); ?>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label small fw-bold">Valor (R$)</label>
                                        <?php echo $this->Form->input("ProviderService.{$index}.value", array(
                                            'label' => false,
                                            'type' => 'text',
                                            'class' => 'form-control',
                                            'placeholder' => '0,00'
                                        )); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="service-item card-doity p-3 mb-3 bg-light border-0">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label small fw-bold">Qual serviço você vai prestar?</label>
                                    <?php echo $this->Form->input('ProviderService.0.service_id', array(
                                        'label' => false,
                                        'class' => 'form-select',
                                        'options' => $services,
                                        'empty' => 'Selecione o serviço...'
                                    )); ?>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label small fw-bold">Valor (R$)</label>
                                    <?php echo $this->Form->input('ProviderService.0.value', array(
                                        'label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => '0,00'
                                    )); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <hr class="my-5 text-muted" style="opacity: 0.1">

            <div class="d-flex justify-content-end gap-3">
                <a href="<?php echo $this->Html->url(array('action' => 'index')); ?>" class="btn btn-light border bg-white text-secondary">Cancelar</a>
                <button type="submit" class="btn btn-danger px-4">Salvar Alterações</button>
            </div>

            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<script type="text/template" id="service-template">
    <div class="service-item card-doity p-3 mb-3 bg-light border-0" style="position: relative;">
        <button type="button" class="btn-remove-service btn btn-sm text-danger position-absolute top-0 end-0 mt-2 me-2" title="Remover este serviço">
            <i class="bi bi-trash"></i>
        </button>

        <div class="row mt-2">
            <div class="col-md-8 mb-3">
                <label class="form-label small fw-bold">Qual serviço você vai prestar?</label>
                <select name="data[ProviderService][{INDEX}][service_id]" class="form-select">
                    <option value="">Selecione o serviço...</option>
                    <?php foreach ($services as $id => $name): ?>
                        <option value="<?php echo $id; ?>"><?php echo h($name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label small fw-bold">Valor (R$)</label>
                <input name="data[ProviderService][{INDEX}][value]" type="text" class="form-control" placeholder="0,00">
            </div>
        </div>
    </div>
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var serviceIndex = <?php echo !empty($this->request->data['ProviderService']) ? count($this->request->data['ProviderService']) : 1; ?>;

    // Adicionar novo serviço
    $('#btn-add-service').click(function() {
        var template = $('#service-template').html();
        var newItem = template.replace(/{INDEX}/g, serviceIndex);
        $('#services-container').append(newItem);
        serviceIndex++;
    });

    // Remover serviço (delegação de evento para itens dinâmicos)
    $(document).on('click', '.btn-remove-service', function() {
        $(this).closest('.service-item').remove();
    });
});
</script>
