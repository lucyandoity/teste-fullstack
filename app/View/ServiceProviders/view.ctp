<?php
$this->assign('title', 'Detalhes do Prestador');
echo $this->Html->css('view');
?>

<body>
    <div class="container">
        <header>
            <h1>Detalhes do Prestador</h1>
            <nav>
                <?php echo $this->Html->link('Início', '/', array('class' => 'nav-link')); ?>
                <?php echo $this->Html->link('Voltar', array('action' => 'index'), array('class' => 'nav-link')); ?>
            </nav>
        </header>

        <main>
            <div class="card">
                <div class="card-header">
                    <?php if (!empty($serviceProvider['ServiceProvider']['photo'])): ?>
                        <div class="photo">
                            <?php echo $this->Html->image($serviceProvider['ServiceProvider']['photo'], array('alt' => 'Foto do prestador')); ?>
                        </div>
                    <?php else: ?>
                        <div class="photo photo-placeholder">
                            <span><?php echo strtoupper(substr($serviceProvider['ServiceProvider']['first_name'], 0, 1) . (substr($serviceProvider['ServiceProvider']['last_name'], 0, 1))); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="header-info">
                        <h2><?php echo h($serviceProvider['ServiceProvider']['first_name'] . ' ' . $serviceProvider['ServiceProvider']['last_name']); ?></h2>
                        <span class="service-badge"><?php echo h($serviceProvider['ServiceProvider']['service']); ?></span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">E-mail</span>
                            <span class="info-value"><?php echo h($serviceProvider['ServiceProvider']['email']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Telefone</span>
                            <span class="info-value"><?php echo h($serviceProvider['ServiceProvider']['phone']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Serviço</span>
                            <span class="info-value"><?php echo h($serviceProvider['ServiceProvider']['service']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Preço</span>
                            <span class="info-value price">R$ <?php echo number_format($serviceProvider['ServiceProvider']['price'], 2, ',', '.'); ?></span>
                        </div>
                    </div>

                    <?php if (!empty($serviceProvider['ServiceProvider']['description'])): ?>
                    <div class="description">
                        <span class="info-label">Descrição</span>
                        <p><?php echo h($serviceProvider['ServiceProvider']['description']); ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="card-footer">
                    <?php echo $this->Html->link('Editar', array('action' => 'edit', $serviceProvider['ServiceProvider']['id']), array('class' => 'btn btn-warning')); ?>
                    <?php echo $this->Form->postLink('Excluir', array('action' => 'delete', $serviceProvider['ServiceProvider']['id']), array('class' => 'btn btn-danger', 'confirm' => 'Tem certeza que deseja excluir?')); ?>
                </div>
            </div>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> - Sistema de Prestadores de Serviço</p>
        </footer>
    </div>
</body>