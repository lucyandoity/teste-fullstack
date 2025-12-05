<?php
$this->assign('title', isset($this->request->data['Prestador']['id']) ? 'Editar Prestador' : 'Cadastro de Prestador');

echo $this->Form->create('Prestador', array(
    'id' => 'providerForm',
    'novalidate' => true,
    'type' => 'file'
));

if (!empty($this->request->data['Prestador']['id'])) {
    echo $this->Form->hidden('id');
}
?>

<h1><?php echo isset($this->request->data['Prestador']['id']) ? 'Editar Prestador' : 'Cadastro de Prestador de Serviço'; ?></h1>

<div class="section">
    <div class="section-title">Informações pessoais</div>
    <div class="section-subtitle">Cadastre suas informações e adicione uma foto.</div>

    <div class="form-group">
        <label class="form-label">Nome Completo</label>
        <?php
        echo $this->Form->input('nome', array(
            'label' => false,
            'div' => false,
            'class' => 'form-control',
            'placeholder' => 'Ex: Eduardo Oliveira'
        ));
        ?>
    </div>

    <div class="form-group">
        <label class="form-label">Email</label>
        <div class="input-icon">
            <i class="fas fa-envelope"></i>
            <?php
            echo $this->Form->input('email', array(
                'label' => false,
                'div' => false,
                'class' => 'form-control',
                'placeholder' => 'eduardo@doity.com.br'
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Sua foto</label>
        <div class="section-subtitle">Ela aparecerá no seu perfil.</div>
        <div class="upload-area">
            <div class="avatar-preview" id="avatarPreview">
                <?php
                if (!empty($this->request->data['Prestador']['foto_url'])) {
                    echo $this->Html->image($this->request->data['Prestador']['foto_url'], array('alt' => 'Avatar'));
                } else {
                    echo '<i class="fas fa-user avatar-placeholder"></i>';
                }
                ?>
            </div>
            <div class="upload-box" id="uploadBox">
                <i class="fas fa-cloud-arrow-up"></i>
                <div class="upload-text">
                    <span>Clique para enviar</span> ou arraste e solte
                </div>
                <div class="upload-hint">PNG, JPG (max. 2MB)</div>
                <?php
                echo $this->Form->input('foto', array(
                    'type' => 'file',
                    'label' => false,
                    'div' => false,
                    'class' => 'file-input',
                    'id' => 'fileInput'
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Telefone</label>
        <?php
        echo $this->Form->input('telefone', array(
            'label' => false,
            'div' => false,
            'class' => 'form-control',
            'placeholder' => '(82) 99999-9999',
            'id' => 'phoneNumber'
        ));
        ?>
    </div>
</div>

<div class="section">
    <div class="section-title">Serviço Principal</div>
    <div class="section-subtitle">Selecione o serviço principal que você presta e informe o valor.</div>

    <div class="form-group">
        <label class="form-label">Serviço Prestado</label>
        <?php
            echo $this->Form->input('servico_id', array(
                'label' => false,
                'div' => false,
                'class' => 'form-control',
                'options' => $servicos,
                'empty' => 'Selecione um serviço...'
            ));
        ?>
    </div>

    <div class="form-group">
        <label class="form-label">Valor do serviço</label>
        <div class="currency-input">
            <?php
                echo $this->Form->input('valor_servico', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'form-control',
                    'placeholder' => 'R$0,00'
                ));
            ?>
        </div>
    </div>
</div>

<div class="form-actions">
    <?php echo $this->Html->link('Cancelar', array('action' => 'index'), array('class' => 'btn btn-cancel')); ?>
    <button type="submit" class="btn btn-save">Salvar</button>
</div>

<?php echo $this->Form->end(); ?>