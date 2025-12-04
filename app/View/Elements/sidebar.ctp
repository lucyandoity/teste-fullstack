<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px; min-height: 100vh;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4 fw-bold">Doity Teste</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <?php
            $activeProviders = ($this->params['controller'] == 'providers') ? 'active bg-danger' : 'text-white';
            ?>
            <?php echo $this->Html->link(
                '<i class="bi bi-people-fill me-2"></i> Prestadores',
                array('controller' => 'providers', 'action' => 'index'),
                array('class' => "nav-link $activeProviders", 'escape' => false)
            ); ?>
        </li>
        <li class="nav-item">
            <?php
            $activeServices = ($this->params['controller'] == 'services') ? 'active bg-danger' : 'text-white';
            ?>
            <?php echo $this->Html->link(
                '<i class="bi bi-briefcase-fill me-2"></i> Serviços',
                array('controller' => 'services', 'action' => 'index'),
                array('class' => "nav-link $activeServices", 'escape' => false)
            ); ?>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>Seu João</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="#">Sair</a></li>
        </ul>
    </div>
</div>
