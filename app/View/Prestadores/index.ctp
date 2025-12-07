<?php $this->assign('title', 'Prestadores de Serviço'); ?>

<div class="container">
    <div class="header">
        <div class="header-left">
            <h1>Prestadores de Serviço</h1>
            <p>Gerencie seus prestadores de serviço</p>
        </div>
        <div class="header-right">
            <?php echo $this->Html->link(
                '<i class="fas fa-calendar"></i> Ver Agendamentos',
                array('controller' => 'agendamentos', 'action' => 'index'),
                array('class' => 'btn btn-secondary', 'escape' => false)
            ); ?>
            <button type="button" id="openImportModalBtn" class="btn btn-secondary">
                <i class="fas fa-upload"></i> Importar
            </button>
            <?php echo $this->Html->link(
                '<i class="fas fa-plus"></i> Add novo prestador',
                array('controller' => 'prestadores', 'action' => 'add'),
                array('class' => 'btn btn-add', 'escape' => false)
            ); ?>
        </div>
    </div>

    <div class="search-box">
        <?php echo $this->Form->create('Prestador', array('url' => array('action' => 'index'), 'type' => 'get', 'novalidate' => true)); ?>
        <i class="fas fa-search search-icon"></i>
        <?php echo $this->Form->input('search', array('label' => false, 'div' => false, 'placeholder' => 'Buscar...', 'id' => 'searchInput', 'value' => isset($this->request->query['search']) ? h($this->request->query['search']) : '', 'autocomplete' => 'off')); ?>
        <?php echo $this->Html->link('<i class="fas fa-times"></i>', array('action' => 'index'), array('id' => 'clearSearchBtn', 'class' => 'clear-search-btn', 'escape' => false, 'title' => 'Limpar busca')); ?>
        <?php echo $this->Form->end(); ?>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Prestador</th>
                    <th>Telefone</th>
                    <th>Serviço</th>
                    <th>Valor</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prestadores as $prestador): ?>
                    <tr>
                        <td>
                            <div class="provider-info">
                                <div class="avatar" style="background-color: <?php echo $prestador['Prestador']['avatar_color']; ?>;">
                                    <?php
                                    if (!empty($prestador['Prestador']['foto']) && file_exists(WWW_ROOT . 'img/uploads/prestadores/' . $prestador['Prestador']['foto'])) {
                                        echo $this->Html->image('uploads/prestadores/' . $prestador['Prestador']['foto'], array('alt' => 'Foto de ' . h($prestador['Prestador']['nome'])));
                                    } else {
                                        echo h($prestador['Prestador']['iniciais']);
                                    }
                                    ?>
                                </div>
                                <div class="provider-details">
                                    <span class="provider-name"><?php echo h($prestador['Prestador']['nome']); ?></span>
                                    <span class="provider-email"><?php echo h($prestador['Prestador']['email']); ?></span>
                                </div>
                            </div>
                        </td>
                        <td><?php echo h($prestador['Prestador']['telefone']); ?></td>
                        <td>
                            <?php
                            if (!empty($prestador['Servico']['nome'])) {
                                echo h($prestador['Servico']['nome']);
                            } else {
                                echo '<span class="text-muted">Nenhum</span>';
                            }
                            ?>
                        </td>
                        <td class="valor-coluna">
                            <?php
                            if (!empty($prestador['Servico']['nome'])) {
                                echo 'R$ ' . number_format($prestador['Prestador']['valor_servico'], 2, ',', '.');
                            } else {
                                echo '<span class="text-muted">-</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <div class="actions">
                                <?php echo $this->Html->link('<i class="fas fa-pen"></i>', array('action' => 'edit', $prestador['Prestador']['id']), array('class' => 'action-btn edit', 'escape' => false, 'title' => 'Editar')); ?>
                                <?php echo $this->Form->postLink('<i class="fas fa-trash"></i>', array('action' => 'delete', $prestador['Prestador']['id']), array('class' => 'action-btn delete', 'escape' => false, 'title' => 'Excluir'), __('Deseja realmente excluir o prestador %s?', h($prestador['Prestador']['nome']))); ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <div class="pagination-info">
            <?php echo $this->Paginator->counter('Página {:page} de {:pages}, mostrando {:current} registros de um total de {:count}'); ?>
        </div>
        <div class="pagination-buttons">
            <?php echo $this->Paginator->prev('Anterior', array('tag' => 'button'), null, array('class' => 'prev disabled', 'tag' => 'button')); ?>
            <?php echo $this->Paginator->next('Próximo', array('tag' => 'button'), null, array('class' => 'next disabled', 'tag' => 'button')); ?>
        </div>
    </div>
</div>

<!-- Modal de Importação com IDs exclusivos -->
<div id="importModal" class="modal-overlay">
    <div class="modal-container">
        <h2 class="modal-title">Faça o upload da sua lista de prestadores</h2>
        <?php echo $this->Form->create('Prestador', ['id' => 'importForm', 'type' => 'file', 'url' => ['action' => 'importar_xls']]); ?>
        <div class="upload-area" id="uploadAreaImport">
            <i class="fas fa-cloud-arrow-up upload-icon"></i>
            <div class="upload-text"><span>Clique para enviar</span> ou arraste e solte</div>
            <div class="upload-hint">XLS, XLSX (max. 25 MB)</div>
            <?php echo $this->Form->input('arquivo', ['type' => 'file', 'label' => false, 'div' => false, 'class' => 'file-input', 'id' => 'fileInputImport', 'accept' => '.xls,.xlsx']); ?>
        </div>
        <div class="file-preview" id="filePreviewImport"></div>
        <div id="import-feedback" style="display: none;"></div>
        <div class="modal-form-actions">
            <button type="button" class="btn btn-cancel" id="cancelImportBtn">Cancelar</button>
            <button type="submit" class="btn btn-save" id="submitImportBtn" disabled>Adicionar</button>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<?php
echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js');

$this->Html->scriptBlock("
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearSearchBtn');
        if (!searchInput || !clearBtn) return;
        function toggleClearButton() { clearBtn.style.display = (searchInput.value.length > 0) ? 'block' : 'none'; }
        toggleClearButton();
        searchInput.addEventListener('input', toggleClearButton);
    });
", array('inline' => false));

$this->Html->scriptBlock("
$(document).ready(function() {
    if ($('#openImportModalBtn').length === 0) return;

    const modal = $('#importModal');
    const openBtn = $('#openImportModalBtn');
    const cancelBtn = $('#cancelImportBtn');
    const submitBtn = $('#submitImportBtn');
    const uploadArea = $('#uploadAreaImport');
    const fileInput = $('#fileInputImport');
    const filePreview = $('#filePreviewImport');
    const feedbackDiv = $('#import-feedback');
    let selectedFile = null;

    openBtn.click(function() {
        modal.css('display', 'flex').hide().fadeIn(200);
    });

    function resetAndCloseModal() {
        removeFile();
        feedbackDiv.empty().hide();
        modal.fadeOut(200);
    }
    
    cancelBtn.click(resetAndCloseModal);
    
    modal.click(function(e) { 
        if ($(e.target).is(modal)) {
            resetAndCloseModal();
        }
    });

    // Clique para abrir arquivo apenas na área de upload, ignorando o input e o botão de remover
    uploadArea.click(function(e) {
        if ($(e.target).is(fileInput)) return; // evita recursão
        if ($(e.target).closest('.remove-file').length) return; // não abre ao clicar em remover
        fileInput.click();
    });

    // Drag & drop
    fileInput.change(e => processFile(e.target.files[0]));
    uploadArea.on('dragover', e => { e.preventDefault(); uploadArea.addClass('drag-over'); });
    uploadArea.on('dragleave', e => { e.preventDefault(); uploadArea.removeClass('drag-over'); });
    uploadArea.on('drop', e => {
        e.preventDefault();
        uploadArea.removeClass('drag-over');
        processFile(e.originalEvent.dataTransfer.files[0]);
    });

    function processFile(file) {
        if (!file) return;
        selectedFile = file;
        
        filePreview.html(
            `<div class='file-info'>
                <div class='file-details'>
                    <div class='file-icon'><i class='fas fa-file-excel'></i></div>
                    <div class='file-meta'>
                        <div class='file-name'>\${file.name}</div>
                        <div class='file-size'>\${(file.size / 1024).toFixed(1)} KB</div>
                    </div>
                </div>
                <button type='button' class='remove-file' id='removeFileBtn'><i class='fas fa-xmark'></i></button>
            </div>`
        ).addClass('show');
        
        submitBtn.prop('disabled', false);
        feedbackDiv.empty().hide();
    }
    
    function removeFile() {
        selectedFile = null;
        fileInput.val('');
        filePreview.removeClass('show').empty();
        submitBtn.prop('disabled', true);
    }

    filePreview.on('click', '#removeFileBtn', removeFile);

    $('#importForm').submit(function(e) {
        e.preventDefault();
        if (!selectedFile) return;

        const formData = new FormData(this);
        submitBtn.prop('disabled', true).text('Importando...');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    feedbackDiv.html(`<div class='success-message'>\${response.message}</div>`).show();
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                     feedbackDiv.html(`<div class='error-message'>\${response.message}</div>`).show();
                }
            },
            error: function(jqXHR) {
                const errorMsg = jqXHR.responseJSON ? jqXHR.responseJSON.message : 'Erro no servidor. Verifique o console do navegador.';
                feedbackDiv.html(`<div class='error-message'>\${errorMsg}</div>`).show();
            },
            complete: function() {
                submitBtn.prop('disabled', false).text('Adicionar');
            }
        });
    });
});
", ['inline' => false]);
?>