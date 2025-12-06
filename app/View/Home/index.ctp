<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold mb-1 text-dark">Painel de Controle</h2>
        <p class="text-muted">Visão geral dos prestadores e serviços do Seu João.</p>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card-doity h-100 border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Total Prestadores</span>
                    <h2 class="mb-0 mt-2 fw-bold text-dark"><?php echo h($metrics['total_providers']); ?></h2>
                </div>
                <div class="p-2 rounded bg-light text-primary">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
            </div>
            <span class="badge bg-success bg-opacity-10 text-success small">Ativos agora</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-doity h-100 border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Serviços no Catálogo</span>
                    <h2 class="mb-0 mt-2 fw-bold text-dark"><?php echo h($metrics['total_services_types']); ?></h2>
                </div>
                <div class="p-2 rounded bg-light text-warning">
                    <i class="bi bi-grid-fill fs-4"></i>
                </div>
            </div>
            <div class="small text-muted">Categorias disponíveis</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-doity h-100 border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Média de Valor</span>
                    <h2 class="mb-0 mt-2 fw-bold text-dark">R$ <?php echo number_format($metrics['avg_ticket'], 2, ',', '.'); ?></h2>
                </div>
                <div class="p-2 rounded bg-light text-success">
                    <i class="bi bi-currency-dollar fs-4"></i>
                </div>
            </div>
            <div class="small text-muted">Por serviço prestado</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-doity h-100 border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div style="overflow: hidden;">
				<span class="text-muted small fw-bold text-uppercase">Maior n° de Prestadores</span>
                    <h4 class="mb-0 mt-2 fw-bold text-dark text-truncate" title="<?php echo h($metrics['top_service']); ?>">
                        <?php echo h($metrics['top_service']); ?>
                    </h4>
                </div>
                <div class="p-2 rounded bg-light text-danger">
                    <i class="bi bi-trophy-fill fs-4"></i>
                </div>
            </div>
            <div class="small text-muted">O favorito dos prestadores</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-doity p-4 border-0 shadow-sm">
            <h5 class="fw-bold mb-4">Ações Rápidas</h5>
            <div class="d-flex gap-3 flex-wrap">
                <a href="<?php echo $this->Html->url(array('controller' => 'providers', 'action' => 'add')); ?>" class="btn btn-danger btn-md px-4 d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus-fill"></i> Novo Prestador
                </a>

                <a href="<?php echo $this->Html->url(array('controller' => 'services', 'action' => 'add')); ?>" class="btn btn-outline-secondary btn-md px-4 d-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle"></i> Novo Serviço no Catálogo
                </a>

                <a href="<?php echo $this->Html->url(array('controller' => 'providers', 'action' => 'index')); ?>" class="btn btn-light btn-md px-4 d-flex align-items-center gap-2 text-secondary border">
                    <i class="bi bi-list-ul"></i> Ver Lista Completa
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Faixa de Preços e Últimos Prestadores -->
<div class="row g-4 mt-2">
    <!-- Faixa de Preços -->
    <div class="col-md-4">
        <div class="card-doity p-4 border-0 shadow-sm h-100">
            <h5 class="fw-bold mb-4"><i class="bi bi-cash-stack me-2"></i>Faixa de Preços</h5>
            <div class="d-flex justify-content-around">
                <div class="text-center">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-2">Mínimo</span>
                    <h3 class="fw-bold text-success">R$ <?php echo number_format($metrics['price_range']['min'], 2, ',', '.'); ?></h3>
                </div>
                <div class="border-start"></div>
                <div class="text-center">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-2">Máximo</span>
                    <h3 class="fw-bold text-danger">R$ <?php echo number_format($metrics['price_range']['max'], 2, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos Prestadores -->
    <div class="col-md-8">
        <div class="card-doity p-4 border-0 shadow-sm h-100">
            <h5 class="fw-bold mb-4"><i class="bi bi-clock-history me-2"></i>Últimos Prestadores Cadastrados</h5>
            <?php if (empty($metrics['recent_providers'])): ?>
                <p class="text-muted mb-0">Nenhum prestador cadastrado ainda.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Serviços</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($metrics['recent_providers'] as $provider): ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo h($provider['Provider']['name']); ?></td>
                                    <td class="text-muted"><?php echo h($provider['Provider']['email']); ?></td>
                                    <td>
                                        <?php if (!empty($provider['Services'])): ?>
                                            <?php foreach ($provider['Services'] as $service): ?>
                                                <span class="badge bg-light text-dark me-1"><?php echo h($service); ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Gráfico de Serviços -->
<div class="row g-4 mt-2 mb-4">
    <div class="col-12">
        <div class="card-doity p-4 border-0 shadow-sm">
            <h5 class="fw-bold mb-4"><i class="bi bi-bar-chart-fill me-2"></i>Prestadores por Serviço</h5>
            <?php if (empty($metrics['services_chart_data']['labels'])): ?>
                <p class="text-muted mb-0">Ainda não há dados suficientes para exibir o gráfico.</p>
            <?php else: ?>
                <div style="max-height: 350px;">
                    <canvas id="servicesChart"></canvas>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!empty($metrics['services_chart_data']['labels'])): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('servicesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($metrics['services_chart_data']['labels']); ?>,
            datasets: [{
                label: 'Prestadores',
                data: <?php echo json_encode($metrics['services_chart_data']['data']); ?>,
                backgroundColor: 'rgba(220, 53, 69, 0.8)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    },
                    title: {
                        display: true,
                        text: 'Quantidade de Prestadores'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Serviços'
                    }
                }
            }
        }
    });
});
</script>
<?php endif; ?>
