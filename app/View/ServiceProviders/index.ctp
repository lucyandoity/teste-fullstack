<?php
$this->assign('title', 'Prestadores de Serviço');
echo $this->Html->css('index');
?>

<body>
    <div class="container">
        <header>
            <div>
            <h1 class="title">Prestadores de Serviço</h1>
            <p class="subtitle">Veja sua lista de prestadores de serviço</p>
            </div>
            <nav>
                <a href="#" class="nav-link btn-import" id="btn-open-import">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M17.5 12.5V15.8333C17.5 16.2754 17.3244 16.6993 17.0118 17.0118C16.6993 17.3244 16.2754 17.5 15.8333 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V12.5M14.1667 6.66667L10 2.5M10 2.5L5.83333 6.66667M10 2.5V12.5" stroke="#414651" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Importar
                </a>
                <?php echo $this->Html->link(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M9.99999 4.16669V15.8334M4.16666 10H15.8333" stroke="white" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg> Add Novo Prestador', 
                    array('action' => 'create'), 
                    array('class' => 'nav-link btn-primary', 'escape' => false)
                ); ?>
            </nav>
        </header>

        <main>
            <div class="search-box">
                <?php 
                echo $this->Form->create(null, array(
                    'type' => 'get',
                    'url' => array('controller' => 'service_providers', 'action' => 'index'),
                    'class' => 'search-form'
                ));
                ?>
                <div class="search-input-wrapper">
                    <svg class="mag" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M17.5 17.5L13.875 13.875M15.8333 9.16667C15.8333 12.8486 12.8486 15.8333 9.16667 15.8333C5.48477 15.8333 2.5 12.8486 2.5 9.16667C2.5 5.48477 5.48477 2.5 9.16667 2.5C12.8486 2.5 15.8333 5.48477 15.8333 9.16667Z" stroke="#717680" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <input type="text" name="search" placeholder="Buscar" value="<?php echo h($search); ?>" class="search-input">
                    <?php if (!empty($search)): ?>
                        <?php echo $this->Html->link('<i class="ph ph-x"></i>', array('action' => 'index'), array('class' => 'clear-btn', 'escape' => false)); ?>
                    <?php endif; ?>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>

            <?php if (!empty($search)): ?>
                <p class="search-result">Resultados para: <strong>"<?php echo h($search); ?>"</strong></p>
            <?php endif; ?>

            <?php if (empty($serviceProviders)): ?>
                <div class="no-results">
                    <p>Nenhum prestador encontrado.</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Prestador</th>
                            <th>Telefone</th>
                            <th>Serviços</th>
                            <th>Valor</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($serviceProviders as $provider): ?>
                        <tr>
                            <td class="provider-info">
                                <?php if (!empty($provider['ServiceProvider']['photo'])): ?>
                                    <?php echo $this->Html->image($provider['ServiceProvider']['photo'], array('alt' => 'Foto', 'class' => 'provider-photo')); ?>
                                <?php else: ?>
                                    <span class="provider-avatar"><?php echo strtoupper(substr($provider['ServiceProvider']['first_name'], 0, 1) . substr($provider['ServiceProvider']['last_name'], 0, 1)); ?></span>
                                <?php endif; ?>
                                <div class="provider-details">
                                    <p class="provider-name"><?php echo h($provider['ServiceProvider']['first_name'] . ' ' . $provider['ServiceProvider']['last_name']); ?></p>
                                    <p class="provider-email"><?php echo h($provider['ServiceProvider']['email']); ?></p>
                                </div>
                            </td>
                            <td class="provider-phone"><?php echo h($provider['ServiceProvider']['phone']); ?></td>
                            <td class="provider-service"><?php echo h($provider['ServiceProvider']['service']); ?></td>
                            <td class="provider-price">R$ <?php echo number_format($provider['ServiceProvider']['price'], 2, ',', '.'); ?></td>
                            <td>
                                <div class="provider-actions">
                                    <?php echo $this->Html->link(
                                        '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M0.833328 10C0.833328 10 4.16666 3.33334 10 3.33334C15.8333 3.33334 19.1667 10 19.1667 10C19.1667 10 15.8333 16.6667 10 16.6667C4.16666 16.6667 0.833328 10 0.833328 10Z" stroke="#535862" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M10 12.5C11.3807 12.5 12.5 11.3807 12.5 10C12.5 8.61929 11.3807 7.5 10 7.5C8.61929 7.5 7.5 8.61929 7.5 10C7.5 11.3807 8.61929 12.5 10 12.5Z" stroke="#535862" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>', 
                                        '#', 
                                        array('class' => 'btn btn-info btn-view', 'data-id' => $provider['ServiceProvider']['id'], 'escape' => false)
                                    ); ?>
                                    <?php echo $this->Html->link(
                                        '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M10 16.6667H17.5M13.75 2.91669C14.0815 2.58517 14.5312 2.39893 15 2.39893C15.2321 2.39893 15.462 2.44465 15.6765 2.53349C15.891 2.62233 16.0858 2.75254 16.25 2.91669C16.4142 3.08084 16.5444 3.27572 16.6332 3.4902C16.722 3.70467 16.7678 3.93455 16.7678 4.16669C16.7678 4.39884 16.722 4.62871 16.6332 4.84319C16.5444 5.05766 16.4142 5.25254 16.25 5.41669L5.83333 15.8334L2.5 16.6667L3.33333 13.3334L13.75 2.91669Z" stroke="#535862" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>', 
                                        array('action' => 'edit', $provider['ServiceProvider']['id']), 
                                        array('class' => 'btn btn-warning', 'escape' => false)
                                    ); ?>
                                    <?php echo $this->Form->postLink(
                                        '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M2.5 4.99996H4.16667M4.16667 4.99996H17.5M4.16667 4.99996V16.6666C4.16667 17.1087 4.34226 17.5326 4.65482 17.8451C4.96738 18.1577 5.39131 18.3333 5.83333 18.3333H14.1667C14.6087 18.3333 15.0326 18.1577 15.3452 17.8451C15.6577 17.5326 15.8333 17.1087 15.8333 16.6666V4.99996H4.16667ZM6.66667 4.99996V3.33329C6.66667 2.89127 6.84226 2.46734 7.15482 2.15478C7.46738 1.84222 7.89131 1.66663 8.33333 1.66663H11.6667C12.1087 1.66663 12.5326 1.84222 12.8452 2.15478C13.1577 2.46734 13.3333 2.89127 13.3333 3.33329V4.99996" stroke="#535862" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>', 
                                        array('action' => 'delete', $provider['ServiceProvider']['id']), 
                                        array('class' => 'btn btn-danger', 'confirm' => 'Tem certeza que deseja excluir?', 'escape' => false)
                                    ); ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="5">
                                <div class="pagination">
                                    <?php echo $this->Paginator->counter(array('format' => 'Página {:page} de {:pages}')); ?>
                                    <div class="pagination-links">
                                        <?php
                                        echo $this->Paginator->prev('Anterior', array('escape' => false), null, array('class' => 'disabled'));
                                        echo $this->Paginator->next('Próximo', array('escape' => false), null, array('class' => 'disabled'));
                                        ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>
    </div>

    <!-- Modal do show -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detalhes do Prestador</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="provider-photo-modal">
                    <img id="modal-photo" src="" alt="Foto do prestador">
                    <p class="provider-namemodal"><span id="modal-name"></span></p>
                    <p class="provider-servicemodal"><span id="modal-service"></span></p>
                    <div class="flex-price">
                        <div class="label-with-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M10 1.66663V18.3333M14.1667 4.16663H7.91667C7.14312 4.16663 6.40125 4.47395 5.85427 5.02093C5.30729 5.56791 5 6.30978 5 7.08329C5 7.85681 5.30729 8.59868 5.85427 9.14566C6.40125 9.69264 7.14312 9.99996 7.91667 9.99996H12.0833C12.8569 9.99996 13.5987 10.3073 14.1457 10.8542C14.6927 11.4012 15 12.1431 15 12.9166C15 13.6902 14.6927 14.432 14.1457 14.979C13.5987 15.526 12.8569 15.8333 12.0833 15.8333H5" stroke="#717680" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Preço</span>
                        </div>
                        <span class="value">R$ <span id="modal-price"></span></span>
                    </div>
                </div>
                <div class="provider-info-modal">
                    <p><textarea disabled class="provider-descriptionmodal" id="modal-description"></textarea></p>
                    

                    <div class="flex-email">
                        <div class="label-with-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M17.5 5.83337C17.5 4.91671 16.75 4.16671 15.8333 4.16671H4.16667C3.25 4.16671 2.5 4.91671 2.5 5.83337M17.5 5.83337V14.1667C17.5 15.0834 16.75 15.8334 15.8333 15.8334H4.16667C3.25 15.8334 2.5 15.0834 2.5 14.1667V5.83337M17.5 5.83337L10 10.8334L2.5 5.83337" stroke="#717680" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Email</span>
                        </div>
                        <span class="value" id="modal-email"></span>
                    </div>

                    <div class="flex-phone">
                        <div class="label-with-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M18.3333 14.1V16.6C18.3342 16.8321 18.2866 17.0618 18.1936 17.2745C18.1006 17.4871 17.9643 17.678 17.7933 17.8349C17.6222 17.9918 17.4203 18.1112 17.2005 18.1856C16.9806 18.26 16.7477 18.2876 16.5167 18.2667C13.9523 17.9881 11.489 17.1118 9.32498 15.7084C7.31151 14.4289 5.60443 12.7218 4.32499 10.7084C2.91663 8.53438 2.04019 6.05916 1.76665 3.48337C1.74583 3.25294 1.77321 3.02069 1.84707 2.80139C1.92092 2.58209 2.03963 2.38064 2.19562 2.2098C2.35162 2.03896 2.54149 1.90258 2.75314 1.80921C2.9648 1.71584 3.19345 1.66774 3.42499 1.66671H5.92499C6.32953 1.66283 6.72148 1.80628 7.028 2.06972C7.33452 2.33317 7.53156 2.69954 7.58332 3.10004C7.68023 3.90009 7.86292 4.68604 8.12499 5.44171C8.2402 5.77004 8.26052 6.12311 8.18347 6.46214C8.10641 6.80117 7.93494 7.11169 7.68999 7.35837L6.64165 8.40671C7.82952 10.4967 9.5034 12.1706 11.5933 13.3584L12.6417 12.31C12.8884 12.0651 13.1989 11.8936 13.5379 11.8166C13.877 11.7395 14.23 11.7598 14.5583 11.875C15.314 12.1371 16.1 12.3198 16.9 12.4167C17.3048 12.4688 17.6745 12.6694 17.9388 12.9813C18.2032 13.2932 18.3435 13.6914 18.3333 14.1Z" stroke="#717680" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Telefone</span>
                        </div>
                        <span class="value" id="modal-phone"></span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Importação CSV -->
    <div id="importModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Faça o upload da sua lista de servidores</h2>
                <button class="modal-close" id="close-import-modal">&times;</button>
            </div>
            <div class="modal-body">
                <?php 
                echo $this->Form->create('ServiceProvider', array(
                    'url' => array('controller' => 'service_providers', 'action' => 'import'),
                    'type' => 'file',
                    'id' => 'import-form'
                ));
                ?>
                
                <div class="file-input-wrapper">
                    <?php 
                    echo $this->Form->file('csv_file', array(
                        'accept' => '.csv',
                        'class' => 'file-input',
                        'required' => true
                    ));
                    ?>
                    <div class="import-info">
                        <div class="import-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <g clip-path="url(#clip0_841_205)">
                                    <path d="M13.3333 13.3334L10 10M10 10L6.66666 13.3334M10 10V17.5M16.9917 15.325C17.8044 14.8819 18.4465 14.1808 18.8166 13.3322C19.1866 12.4837 19.2635 11.5361 19.0352 10.6389C18.8068 9.74182 18.2862 8.94629 17.5556 8.3779C16.8249 7.80951 15.9257 7.50064 15 7.50003H13.95C13.6978 6.5244 13.2276 5.61864 12.575 4.85085C11.9223 4.08307 11.104 3.47324 10.1817 3.0672C9.25946 2.66116 8.25712 2.46949 7.25009 2.5066C6.24307 2.5437 5.25755 2.80861 4.36764 3.28142C3.47774 3.75422 2.70659 4.42261 2.11218 5.23635C1.51777 6.05008 1.11557 6.98797 0.935814 7.97952C0.756055 8.97107 0.803418 9.99047 1.07434 10.9611C1.34527 11.9317 1.8327 12.8282 2.5 13.5834" stroke="#535862" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                            </svg>
                        </div>
                        <p class="import-description"><span class="import-description-bold">Clique para enviar</span> ou arraste e solte</p>
                        <p class="import-file-size">CSV (max. 25MB)</p>
                    </div>
                </div>

                <!-- Informações do arquivo -->
                <div class="file-preview" id="file-preview">
                    <div class="file-preview-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M8.66669 1.33337H4.00002C3.6464 1.33337 3.30726 1.47385 3.05721 1.7239C2.80716 1.97395 2.66669 2.31309 2.66669 2.66671V13.3334C2.66669 13.687 2.80716 14.0261 3.05721 14.2762C3.30726 14.5262 3.6464 14.6667 4.00002 14.6667H12C12.3536 14.6667 12.6928 14.5262 12.9428 14.2762C13.1929 14.0261 13.3334 13.687 13.3334 13.3334V6.00004M8.66669 1.33337L13.3334 6.00004M8.66669 1.33337V6.00004H13.3334" stroke="#FF2B34" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="file-preview-details">
                        <div class="file-preview-header">
                            <div class="file-preview-info">
                                <span class="file-preview-name" id="file-preview-name"></span>
                                <span class="file-preview-size" id="file-preview-size"></span>
                            </div>
                            <button type="button" class="file-remove" id="file-remove">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20" fill="none">
                                    <path d="M2.5 4.99996H4.16667M4.16667 4.99996H17.5M4.16667 4.99996V16.6666C4.16667 17.1087 4.34226 17.5326 4.65482 17.8451C4.96738 18.1577 5.39131 18.3333 5.83333 18.3333H14.1667C14.6087 18.3333 15.0326 18.1577 15.3452 17.8451C15.6577 17.5326 15.8333 17.1087 15.8333 16.6666V4.99996H4.16667ZM6.66667 4.99996V3.33329C6.66667 2.89127 6.84226 2.46734 7.15482 2.15478C7.46738 1.84222 7.89131 1.66663 8.33333 1.66663H11.6667C12.1087 1.66663 12.5326 1.84222 12.8452 2.15478C13.1577 2.46734 13.3333 2.89127 13.3333 3.33329V4.99996" stroke="#98A2B3" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        <div class="progress-wrapper">
                            <div class="progress-container">
                                <div class="progress-bar" id="progress-bar"></div>
                            </div>
                            <span class="progress-percent" id="progress-percent">0%</span>
                        </div>
                    </div>
                </div>

                <div class="import-format-info">
                    <p><strong>Formato esperado (com cabeçalho)</strong></p>
                    <code>first_name,last_name,email,phone,service,description,price</code>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="btn-cancel-import">Cancelar</button>
                    <button type="submit" class="btn-submit" id="btn-submit-import">Adicionar</button>
                </div>

                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</body>

<script>
$(document).ready(function() {
    // Modal de View
    $('.btn-view').on('click', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: '<?php echo $this->Html->url(array("action" => "view")); ?>/' + id,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#viewModal').addClass('loading');
            },
            success: function(data) {
                var provider = data.ServiceProvider;
                
                $('#modal-name').text(provider.first_name + ' ' + provider.last_name);
                $('#modal-email').text(provider.email);
                $('#modal-phone').text(provider.phone);
                $('#modal-service').text(provider.service);
                $('#modal-description').text(provider.description || 'Sem descrição');
                $('#modal-price').text(parseFloat(provider.price).toFixed(2).replace('.', ','));
                
                if (provider.photo) {
                    $('#modal-photo').attr('src', '<?php echo $this->Html->url("/img/"); ?>' + provider.photo).show();
                } else {
                    $('#modal-photo').hide();
                }
                
                $('#viewModal').addClass('show');
            },
            error: function() {
                alert('Erro ao carregar dados do prestador.');
            },
            complete: function() {
                $('#viewModal').removeClass('loading');
            }
        });
    });

    $('.modal-close, #viewModal').on('click', function(e) {
        if (e.target === this) {
            $('#viewModal').removeClass('show');
        }
    });

    // Modal Import
    $('#btn-open-import').on('click', function(e) {
        e.preventDefault();
        $('#importModal').addClass('show');
    });

    $('#close-import-modal, #btn-cancel-import, #importModal').on('click', function(e) {
        if (e.target === this) {
            resetImportModal();
        }
    });

    $('.modal-content').on('click', function(e) {
        e.stopPropagation();
    });

    // Função para formatar tamanho do arquivo
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        var k = 1024;
        var sizes = ['Bytes', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Função para resetar o modal
    function resetImportModal() {
        $('#importModal').removeClass('show');
        $('#import-form')[0].reset();
        $('#file-preview').removeClass('show');
        $('#progress-bar').css('width', '0%');
        $('#progress-percent').text('0%');
    }

    // Mostrar preview do arquivo ao selecionar
    $('.file-input').on('change', function() {
        var file = this.files[0];
        
        if (file) {
            $('#file-preview-name').text(file.name);
            $('#file-preview-size').text(formatFileSize(file.size));
            $('#file-preview').addClass('show');
            
            var progress = 0;
            $('#progress-bar').css('width', '0%');
            $('#progress-percent').text('0%');
            
            var interval = setInterval(function() {
                progress += Math.random() * 30;
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(interval);
                }
                $('#progress-bar').css('width', progress + '%');
                $('#progress-percent').text(Math.round(progress) + '%');
            }, 100);
        } else {
            $('#file-preview').removeClass('show');
        }
    });

    // Remover arquivo
    $('#file-remove').on('click', function() {
        $('.file-input').val('');
        $('#file-preview').removeClass('show');
        $('#progress-bar').css('width', '0%');
        $('#progress-percent').text('0%');
    });

    // Fechar modais com o esc
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('#viewModal').removeClass('show');
            resetImportModal();
        }
    });
});
</script>