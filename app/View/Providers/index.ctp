<div class="row justify-content-center">
    <div class="col-12">

        <div class="card-doity">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h2 class="fw-bold mb-1 fs-3">Prestadores de Serviço</h2>
                    <p class="text-muted mb-0 small">
                        <?php if (isset($providersCount)): ?>
                            <?php echo $providersCount; ?> prestador(es) encontrado(s)
                        <?php else: ?>
                            Veja sua lista de prestadores de serviço
                        <?php endif; ?>
                    </p>
                </div>

                <div class="d-flex gap-2 flex-shrink-0">
                    <button type="button" class="btn btn-light border d-flex align-items-center gap-2 px-3" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="bi bi-upload"></i>
                        <span class="d-none d-sm-inline">Importar</span>
                    </button>
                    <a href="<?php echo $this->Html->url(array('action' => 'add')); ?>" class="btn btn-danger text-white d-flex align-items-center gap-2 px-3">
                        <i class="bi bi-plus-lg"></i>
                        <span class="d-none d-sm-inline">Add novo prestador</span>
                    </a>
                </div>
            </div>

            <div class="mb-4">
                <?php echo $this->Form->create('Provider', array('type' => 'get', 'url' => array('action' => 'index'))); ?>
                <div class="row g-2">
                    <div class="col-12 col-lg-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 ps-3">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <?php echo $this->Form->input('search', array(
                                'label' => false,
                                'div' => false,
                                'class' => 'form-control border-start-0 border-end-0 ps-2',
                                'placeholder' => 'Buscar por nome, email, telefone ou serviço...',
                                'value' => $this->request->query('search')
                            )); ?>
                            <button type="submit" class="btn btn-outline-secondary border-start-0 bg-white">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <select name="sort" class="form-select w-100" onchange="this.form.submit()">
                            <option value="">Ordenar por</option>
                            <option value="name" <?php echo ($this->request->query('sort') == 'name') ? 'selected' : ''; ?>>Nome</option>
                            <option value="email" <?php echo ($this->request->query('sort') == 'email') ? 'selected' : ''; ?>>Email</option>
                            <option value="value" <?php echo ($this->request->query('sort') == 'value') ? 'selected' : ''; ?>>Valor</option>
                        </select>
                    </div>
                    <div class="col-6 col-lg-3">
                        <select name="direction" class="form-select w-100" onchange="this.form.submit()">
                            <option value="asc" <?php echo ($this->request->query('direction') == 'asc') ? 'selected' : ''; ?>>Crescente</option>
                            <option value="desc" <?php echo ($this->request->query('direction') == 'desc' || !$this->request->query('direction')) ? 'selected' : ''; ?>>Decrescente</option>
                        </select>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>

            <?php if (!empty($this->request->query('search'))): ?>
                <div class="alert alert-light border d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-4" style="background-color: #F9FAFB;">
                    <div class="text-muted small">
                        <i class="bi bi-funnel me-1"></i>
                        Exibindo resultados para: <strong><?php echo h($this->request->query('search')); ?></strong>
                    </div>
                    <a href="<?php echo $this->Html->url(array('action' => 'index')); ?>" class="text-decoration-none small text-danger fw-bold text-nowrap">
                        <i class="bi bi-x-lg me-1"></i> Limpar filtro
                    </a>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="py-3 ps-3 text-secondary small fw-bold" style="min-width: 200px;">
                                Prestador
                            </th>

                            <th scope="col" class="py-3 text-secondary small fw-bold d-none d-md-table-cell" style="width: 20%;">
                                Telefone
                            </th>

                            <th scope="col" class="py-3 text-secondary small fw-bold d-none d-lg-table-cell" style="width: 40%;">
                                <div class="d-flex justify-content-between pe-3">
                                    <span>Serviços</span>
                                    <span>Valor</span>
                                </div>
                            </th>

                            <th scope="col" class="py-3 pe-3 text-end d-none d-lg-table-cell" style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($providers)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="mb-3">
                                            <i class="bi bi-people text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                                        </div>
                                        <p class="text-muted mb-3">
                                            <?php if (!empty($this->request->query('search'))): ?>
                                                Nenhum prestador encontrado para "<strong><?php echo h($this->request->query('search')); ?></strong>".
                                            <?php else: ?>
                                                Nenhum prestador cadastrado ainda.
                                            <?php endif; ?>
                                        </p>
                                        <?php if (empty($this->request->query('search'))): ?>
                                            <a href="<?php echo $this->Html->url(array('action' => 'add')); ?>" class="btn btn-primary btn-sm">
                                                <i class="bi bi-plus-lg me-1"></i> Cadastrar primeiro prestador
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo $this->Html->url(array('action' => 'index')); ?>" class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-arrow-left me-1"></i> Voltar para lista completa
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($providers as $provider): ?>
                            <tr style="border-bottom: 1px solid #EAECF0;" class="provider-row">
                                <td class="ps-3 py-4 align-top">
                                    <div class="d-flex align-items-start">
                                        <?php
                                        $photoPath = !empty($provider['Provider']['photo']) ? $provider['Provider']['photo'] : null;
                                        if ($photoPath && file_exists(WWW_ROOT . 'img' . DS . $photoPath)) {
                                            echo $this->Html->image($photoPath, array('class' => 'rounded-circle me-3 flex-shrink-0', 'width' => '40', 'height' => '40', 'style' => 'object-fit: cover;'));
                                        } else {
                                            echo '<div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 text-secondary fw-bold flex-shrink-0" style="width: 40px; height: 40px; background-color: #F2F4F7;">' . strtoupper(substr($provider['Provider']['name'], 0, 2)) . '</div>';
                                        }
                                        ?>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="fw-bold text-dark"><?php echo h($provider['Provider']['name']); ?></div>
                                                    <div class="text-muted small"><?php echo h($provider['Provider']['email']); ?></div>
                                                </div>
                                                <div class="d-lg-none d-flex gap-3 ms-2">
                                                    <a href="<?php echo $this->Html->url(array('action' => 'edit', $provider['Provider']['id'])); ?>" class="text-secondary" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <?php echo $this->Form->postLink(
                                                        '<i class="bi bi-trash"></i>',
                                                        array('action' => 'delete', $provider['Provider']['id']),
                                                        array('escape' => false, 'class' => 'text-secondary', 'title' => 'Excluir'),
                                                        __('Tem certeza que deseja excluir %s?', $provider['Provider']['name'])
                                                    ); ?>
                                                </div>
                                            </div>

                                            <div class="d-md-none mt-2">
                                                <span class="text-muted small"><i class="bi bi-telephone me-1"></i><?php echo h($provider['Provider']['phone']); ?></span>
                                            </div>

                                            <div class="d-lg-none mt-3">
                                                <?php if (!empty($provider['ProviderService'])): ?>
                                                    <table class="table table-sm table-borderless mb-0" style="font-size: 12px;">
                                                        <?php foreach ($provider['ProviderService'] as $ps): ?>
                                                            <?php $serviceName = isset($ps['Service']['name']) ? $ps['Service']['name'] : 'Serviço'; ?>
                                                            <tr>
                                                                <td class="text-secondary ps-0 py-1" style="width: 60%;"><?php echo h($serviceName); ?></td>
                                                                <td class="text-dark fw-medium text-end pe-0 py-1">R$ <?php echo number_format($ps['value'], 2, ',', '.'); ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </table>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-muted align-top py-4 d-none d-md-table-cell">
                                    <i class="bi bi-telephone me-1"></i><?php echo h($provider['Provider']['phone']); ?>
                                </td>

                                <td class="align-top py-4 d-none d-lg-table-cell">
                                    <?php if (!empty($provider['ProviderService'])): ?>
                                        <div class="d-flex flex-column gap-2">
                                            <?php foreach ($provider['ProviderService'] as $ps): ?>
                                                <?php $serviceName = isset($ps['Service']['name']) ? $ps['Service']['name'] : 'Serviço s/ nome'; ?>
                                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-1" style="border-color: #f2f4f7 !important;">
                                                    <span class="text-dark small me-3"><?php echo h($serviceName); ?></span>
                                                    <span class="fw-medium text-muted small text-nowrap">
                                                        R$ <?php echo number_format($ps['value'], 2, ',', '.'); ?>
                                                    </span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-end pe-3 align-top py-4 d-none d-lg-table-cell">
                                    <div class="d-flex gap-3 justify-content-end">
                                        <a href="<?php echo $this->Html->url(array('action' => 'edit', $provider['Provider']['id'])); ?>" class="text-secondary action-btn" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php echo $this->Form->postLink(
                                            '<i class="bi bi-trash"></i>',
                                            array('action' => 'delete', $provider['Provider']['id']),
                                            array('escape' => false, 'class' => 'text-secondary action-btn', 'title' => 'Excluir'),
                                            __('Tem certeza que deseja excluir %s?', $provider['Provider']['name'])
                                        ); ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-2">
                <div class="text-muted small">
                    <?php echo $this->Paginator->counter(array('format' => __('Página {:page} de {:pages}'))); ?>
                </div>
                <div class="d-flex gap-2">
                    <?php
                    $this->Paginator->options(array(
                        'url' => array(
                            '?' => array(
                                'search' => $this->request->query('search'),
                                'sort' => $this->request->query('sort'),
                                'direction' => $this->request->query('direction')
                            )
                        )
                    ));

                    if ($this->Paginator->hasPrev()) {
                        echo $this->Paginator->prev('Anterior', array('class' => 'btn btn-light border btn-sm px-3'), null, array('class' => 'btn btn-light border btn-sm px-3 disabled'));
                    } else {
                        echo '<button class="btn btn-light border btn-sm px-3" disabled>Anterior</button>';
                    }
                    if ($this->Paginator->hasNext()) {
                        echo $this->Paginator->next('Próximo', array('class' => 'btn btn-light border btn-sm px-3'), null, array('class' => 'btn btn-light border btn-sm px-3 disabled'));
                    } else {
                        echo '<button class="btn btn-light border btn-sm px-3" disabled>Próximo</button>';
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .modal-backdrop.show {
        opacity: 0.25 !important;
    }
</style>

<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">

            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold fs-5" id="importModalLabel">Faça o upload da sua lista de servidores</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <?php echo $this->Form->create('Provider', array('url' => array('action' => 'import'), 'type' => 'file', 'id' => 'ImportForm')); ?>

            <div class="modal-body px-4 pt-3 pb-4">
                <div class="position-relative border border-dashed rounded-3 text-center bg-white p-5" style="border: 1px dashed #D0D5DD; transition: all 0.2s;" id="drop-zone">

                    <?php echo $this->Form->input('file', array(
                        'type' => 'file',
                        'label' => false,
                        'class' => 'position-absolute top-0 start-0 w-100 h-100',
                        'style' => 'opacity: 0; cursor: pointer; z-index: 10;',
                        'accept' => '.csv',
                        'onchange' => "updateImportFile(this)"
                    )); ?>

                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="p-2 bg-light rounded-circle mb-3" style="background-color: #F2F4F7;">
                            <i class="bi bi-cloud-upload fs-4 text-secondary"></i>
                        </div>
                        <div class="small text-muted mb-1">
                            <span class="fw-bold text-danger">Clique para enviar</span> ou arraste e solte
                        </div>
                        <div class="text-muted" style="font-size: 0.75rem;">CSV, XLS, XLSX (max. 25 MB)</div>
                    </div>
                </div>

                <div id="import-file-preview" class="border rounded-3 p-3 align-items-center justify-content-between d-none mt-3" style="border-color: #EAECF0 !important; display: flex;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded p-2 text-danger" style="background-color: #FEF3F2;">
                            <i class="bi bi-file-earmark-spreadsheet fs-5"></i>
                        </div>
                        <div>
                            <div id="import-file-name" class="fw-medium text-dark small">arquivo.csv</div>
                            <div id="import-file-size" class="text-muted" style="font-size: 0.7rem;">0 KB</div>
                        </div>
                    </div>
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                </div>
            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <div class="d-flex w-100 gap-2">
                    <button type="button" class="btn btn-light border w-50" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger text-white w-50">Adicionar</button>
                </div>
            </div>

            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<script>
function updateImportFile(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];

        document.getElementById('import-file-preview').classList.remove('d-none');

        document.getElementById('import-file-name').textContent = file.name;
        document.getElementById('import-file-size').textContent = (file.size / 1024).toFixed(2) + ' KB';
    }
}
</script>
