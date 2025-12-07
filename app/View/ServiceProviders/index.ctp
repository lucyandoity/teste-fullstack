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
                <?php echo $this->Html->link(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M17.5 12.5V15.8333C17.5 16.2754 17.3244 16.6993 17.0118 17.0118C16.6993 17.3244 16.2754 17.5 15.8333 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V12.5M14.1667 6.66667L10 2.5M10 2.5L5.83333 6.66667M10 2.5V12.5" stroke="#414651" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg> Importar', 
                    array('action' => 'import'), 
                    array('class' => 'nav-link btn-import', 'escape' => false)
                ); ?>
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
                    <i class="ph ph-magnifying-glass search-icon"></i>
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
                                            <path d="M2.5 4.99996H4.16667M4.16667 4.99996H17.5M4.16667 4.99996V16.6666C4.16667 17.1087 4.34226 17.5326 4.65482 17.8451C4.96738 18.1577 5.39131 18.3333 5.83333 18.3333H14.1667C14.6087 18.3333 15.0326 18.1577 15.3452 17.8451C15.6577 17.5326 15.8333 17.1087 15.8333 16.6666V4.99996H4.16667ZM6.66667 4.99996V3.33329C6.66667 2.89127 6.84226 2.46734 7.15482 2.15478C7.46738 1.84222 7.89131 1.66663 8.33333 1.66663H11.6667C12.1087 1.66663 12.5326 1.84222 12.8452 2.15478C13.1577 2.46734 13.3333 2.89127 13.3333 3.33329V4.99996M8.33333 9.16663V14.1666M11.6667 9.16663V14.1666" stroke="#535862" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
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

    <!-- Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detalhes do Prestador</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="provider-photo-modal">
                    <img id="modal-photo" src="" alt="Foto do prestador">
                </div>
                <div class="provider-info-modal">
                    <p><strong>Nome:</strong> <span id="modal-name"></span></p>
                    <p><strong>Email:</strong> <span id="modal-email"></span></p>
                    <p><strong>Telefone:</strong> <span id="modal-phone"></span></p>
                    <p><strong>Serviço:</strong> <span id="modal-service"></span></p>
                    <p><strong>Descrição:</strong> <span id="modal-description"></span></p>
                    <p><strong>Preço:</strong> R$ <span id="modal-price"></span></p>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
$(document).ready(function() {
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

    $('.modal-close, .modal').on('click', function(e) {
        if (e.target === this) {
            $('#viewModal').removeClass('show');
        }
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('#viewModal').removeClass('show');
        }
    });
});
</script>