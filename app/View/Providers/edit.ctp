<?php
$fullName = isset($this->request->data['Provider']['name']) ? $this->request->data['Provider']['name'] : '';
$parts = explode(' ', $fullName, 2);
$firstName = isset($parts[0]) ? $parts[0] : '';
$lastName = isset($parts[1]) ? $parts[1] : '';

$photoData = isset($this->request->data['Provider']['photo']) ? $this->request->data['Provider']['photo'] : null;
$currentPhoto = is_string($photoData) && !empty($photoData) ? $photoData : null;
?>

<div class="row justify-content-center">
    <div class="col-lg-12">

        <div class="card-doity">
            <h2 class="fw-bold mb-1 fs-3">Editar Prestador</h2>

            <div class="mt-4 mb-4">
                <h5 class="fw-bold mb-1" style="font-size: 1.1rem;">Informações pessoais</h5>
                <p class="text-muted small">Atualize as informações do prestador.</p>
            </div>

            <hr class="my-4 text-muted" style="opacity: 0.1">

            <?php echo $this->Form->create('Provider', array('type' => 'file', 'inputDefaults' => array('div' => false))); ?>
            <?php echo $this->Form->input('id'); ?>

            <div class="row mb-4 align-items-center">
                <label class="col-md-3 form-label text-dark fw-medium mb-0">Nome</label>
                <div class="col-md-7">
                    <div class="row g-3">
                        <div class="col-6">
                            <?php echo $this->Form->input('first_name', array(
                                'label' => false,
                                'class' => 'form-control',
                                'placeholder' => 'Nome',
                                'value' => $firstName,
                                'required' => true
                            )); ?>
                        </div>
                        <div class="col-6">
                            <?php echo $this->Form->input('last_name', array(
                                'label' => false,
                                'class' => 'form-control',
                                'placeholder' => 'Sobrenome',
                                'value' => $lastName,
                                'required' => true
                            )); ?>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4 text-muted" style="opacity: 0.1">

            <div class="row mb-4 align-items-center">
                <label class="col-md-3 form-label text-dark fw-medium mb-0">Email</label>
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <?php echo $this->Form->input('email', array(
                            'label' => false,
                            'class' => 'form-control border-start-0 ps-2',
                            'placeholder' => 'eduardo@doity.com.br'
                        )); ?>
                    </div>
                </div>
            </div>

            <hr class="my-4 text-muted" style="opacity: 0.1">

            <div class="row mb-4 align-items-start mt-2">
                <div class="col-md-3">
                    <label class="form-label mb-0">Sua foto</label>
                    <div class="text-muted small" style="font-size: 0.75rem;">Foto atual</div>
                </div>
                <div class="col-md-7">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-secondary" style="width: 64px; height: 64px; overflow: hidden; flex-shrink: 0;">
                            <?php if ($currentPhoto && file_exists(WWW_ROOT . 'img' . DS . $currentPhoto)): ?>
                                <img id="photo-preview" src="<?php echo $this->webroot . 'img/' . $currentPhoto; ?>" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                                <i class="bi bi-person fs-3" id="avatar-icon" style="display: none;"></i>
                            <?php else: ?>
                                <i class="bi bi-person fs-3" id="avatar-icon"></i>
                                <img id="photo-preview" src="#" alt="Preview" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                            <?php endif; ?>
                        </div>

                        <div class="flex-grow-1 position-relative border border-dashed rounded text-center bg-white" style="border: 1px dashed #d0d5dd; transition: all 0.2s;">
                            <?php echo $this->Form->input('photo', array(
                                'type' => 'file',
                                'label' => false,
                                'class' => 'position-absolute top-0 start-0 w-100 h-100',
                                'style' => 'opacity: 0; cursor: pointer; z-index: 10;',
                                'onchange' => "document.getElementById('file-name-display').textContent = this.files[0].name; if(this.files && this.files[0]) { var reader = new FileReader(); reader.onload = function(e) { document.getElementById('photo-preview').src = e.target.result; document.getElementById('photo-preview').style.display = 'block'; document.getElementById('avatar-icon').style.display = 'none'; }; reader.readAsDataURL(this.files[0]); }"
                            )); ?>

                            <div class="p-4 d-flex flex-column align-items-center justify-content-center">
                                <div class="p-2 bg-light rounded-circle mb-2">
                                    <i class="bi bi-cloud-upload fs-5 text-secondary"></i>
                                </div>
                                <div class="small text-muted mb-1">
                                    <span class="fw-bold text-danger">Clique para alterar</span> a foto
                                </div>
                                <div class="text-muted mb-2" style="font-size: 0.7rem;">SVG, PNG, JPG or GIF (max. 800x400px)</div>
                                <div id="file-name-display" class="badge bg-light text-dark border mt-1" style="font-weight: normal;">
                                    <?php echo $currentPhoto ? 'Foto salva atualmente' : 'Nenhum arquivo selecionado'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4 text-muted" style="opacity: 0.1">

            <div class="row mb-4 align-items-center">
                <label class="col-md-3 form-label text-dark fw-medium mb-0">Telefone</label>
                <div class="col-md-7">
                    <?php echo $this->Form->input('phone', array(
                        'label' => false,
                        'class' => 'form-control',
                        'placeholder' => '(82) 99999-9999'
                    )); ?>
                </div>
            </div>

            <hr class="my-5 text-muted" style="opacity: 0.1">

            <div class="mb-4">
                <div class="mb-3">
                    <h5 class="fw-bold m-0" style="font-size: 1.1rem;">Serviços Prestados</h5>
                </div>

                <div id="services-container">
                    <?php
                    if (!empty($this->request->data['ProviderService'])):
                        foreach ($this->request->data['ProviderService'] as $index => $service):
                    ?>
                        <div class="service-item mb-4 pt-3 border-top" style="border-color: #f0f0f0 !important; position: relative;">

                            <div class="row mb-2">
                                <div class="col-md-7 offset-md-3 text-end">
                                    <button type="button" class="btn-remove-service btn btn-sm text-danger p-0 text-decoration-none" style="font-size: 0.85rem;">
                                        <i class="bi bi-trash me-1"></i> Remover este serviço
                                    </button>
                                </div>
                            </div>

                            <?php echo $this->Form->input("ProviderService.$index.id", array('type' => 'hidden')); ?>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 form-label text-dark fw-medium mb-0">Qual serviço?</label>
                                <div class="col-md-7">
                                    <?php echo $this->Form->input("ProviderService.$index.service_id", array(
                                        'label' => false,
                                        'class' => 'form-select',
                                        'options' => $services,
                                        'empty' => 'Selecione o serviço...'
                                    )); ?>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 form-label text-dark fw-medium mb-0">Valor do serviço</label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted">R$</span>
                                        <?php
                                        $serviceValue = isset($service['value']) && is_numeric($service['value']) ? $service['value'] : 0;
                                        echo $this->Form->input("ProviderService.$index.value", array(
                                            'label' => false,
                                            'div' => false,
                                            'type' => 'text',
                                            'class' => 'form-control border-start-0 ps-2',
                                            'value' => number_format($serviceValue, 2, ',', '')
                                        )); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        endforeach;
                    else:
                    ?>
                        <div class="service-item mb-4 pt-3 border-top" style="border-color: #f0f0f0 !important;">
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 form-label text-dark fw-medium mb-0">Qual serviço?</label>
                                <div class="col-md-7">
                                    <?php echo $this->Form->input('ProviderService.0.service_id', array(
                                        'label' => false, 'class' => 'form-select', 'options' => $services, 'empty' => 'Selecione...'
                                    )); ?>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 form-label text-dark fw-medium mb-0">Valor do serviço</label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted">R$</span>
                                        <?php echo $this->Form->input('ProviderService.0.value', array(
                                            'label' => false, 'type' => 'text', 'class' => 'form-control border-start-0 ps-2', 'placeholder' => '0,00'
                                        )); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <div class="col-md-3"></div> <div class="col-md-7 text-end">
                        <button type="button" id="btn-add-service" class="btn btn-outline-danger">
                            <i class="bi bi-plus-lg me-1"></i> Adicionar outro serviço
                        </button>
                    </div>
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
    <div class="service-item mb-4 pt-3 border-top" style="border-color: #f0f0f0 !important; position: relative;">
        <div class="row mb-2">
            <div class="col-md-7 offset-md-3 text-end">
                <button type="button" class="btn-remove-service btn btn-sm text-danger p-0 text-decoration-none" style="font-size: 0.85rem;">
                    <i class="bi bi-trash me-1"></i> Remover este serviço
                </button>
            </div>
        </div>

        <div class="row mb-3 align-items-center">
            <label class="col-md-3 form-label text-dark fw-medium mb-0">Qual serviço?</label>
            <div class="col-md-7">
                <select name="data[ProviderService][{INDEX}][service_id]" class="form-select">
                    <option value="">Selecione o serviço...</option>
                    <?php foreach ($services as $id => $name): ?>
                        <option value="<?php echo $id; ?>"><?php echo h($name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row mb-3 align-items-center">
            <label class="col-md-3 form-label text-dark fw-medium mb-0">Valor do serviço</label>
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted">R$</span>
                    <input name="data[ProviderService][{INDEX}][value]" type="text" class="form-control border-start-0 ps-2" placeholder="0,00">
                </div>
            </div>
        </div>
    </div>
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var serviceIndex = <?php echo isset($this->request->data['ProviderService']) ? count($this->request->data['ProviderService']) : 1; ?>;

    $('#btn-add-service').click(function() {
        var template = $('#service-template').html();
        var newItem = template.replace(/{INDEX}/g, serviceIndex);
        $('#services-container').append(newItem);
        serviceIndex++;
    });

    $(document).on('click', '.btn-remove-service', function() {
        $(this).closest('.service-item').remove();
    });
});
</script>
