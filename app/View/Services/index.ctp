<div class="row justify-content-center">
    <div class="col-12">

        <div class="card-doity">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1 fs-3">Catálogo de Serviços</h2>
                    <p class="text-muted mb-0">
                        <?php if (isset($this->Paginator) && $this->Paginator->counter()): ?>
                            <?php echo $this->Paginator->counter('{:count} serviço(s) no catálogo'); ?>
                        <?php else: ?>
                            Gerencie os tipos de serviços disponíveis para os prestadores
                        <?php endif; ?>
                    </p>
                </div>
                <div>
                    <a href="<?php echo $this->Html->url(array('action' => 'add')); ?>" class="btn btn-danger text-white d-flex align-items-center gap-2 px-3">
                        <i class="bi bi-plus-lg"></i>
                        <span>Add novo serviço</span>
                    </a>
                </div>
            </div>

            <div class="mb-4">
                <?php echo $this->Form->create('Service', array('type' => 'get', 'url' => array('action' => 'index'))); ?>
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
                                'placeholder' => 'Buscar serviço...',
                                'value' => $this->request->query('search')
                            )); ?>
                            <button type="submit" class="btn btn-outline-secondary border-start-0 bg-white">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <?php
                        $currentSort = $this->request->query('sort');
                        $currentDirection = $this->request->query('direction') ?: 'asc';
                        ?>
                        <select name="sort" class="form-select w-100" onchange="this.form.submit()">
                            <option value="">Ordenar por</option>
                            <option value="name" <?php echo $currentSort === 'name' ? 'selected' : ''; ?>>Nome</option>
                        </select>
                    </div>
                    <div class="col-6 col-lg-3">
                        <select name="direction" class="form-select w-100" onchange="this.form.submit()">
                            <option value="asc" <?php echo $currentDirection === 'asc' ? 'selected' : ''; ?>>Crescente</option>
                            <option value="desc" <?php echo $currentDirection === 'desc' ? 'selected' : ''; ?>>Decrescente</option>
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
                            <th scope="col" class="py-3 ps-3 text-secondary small fw-bold" style="width: 30%;">Nome</th>
                            <th scope="col" class="py-3 text-secondary small fw-bold" style="width: 50%;">Descrição</th>
                            <th scope="col" class="py-3 pe-3 text-end" style="width: 20%;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($services)): ?>
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="mb-3">
                                            <i class="bi bi-box-seam text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                                        </div>
                                        <p class="text-muted mb-3">
                                            <?php if (!empty($this->request->query('search'))): ?>
                                                Nenhum serviço encontrado para "<strong><?php echo h($this->request->query('search')); ?></strong>".
                                            <?php else: ?>
                                                Nenhum tipo de serviço cadastrado no catálogo.
                                            <?php endif; ?>
                                        </p>
                                        <?php if (empty($this->request->query('search'))): ?>
                                            <a href="<?php echo $this->Html->url(array('action' => 'add')); ?>" class="btn btn-primary btn-sm">
                                                <i class="bi bi-plus-lg me-1"></i> Cadastrar primeiro serviço
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo $this->Html->url(array('action' => 'index')); ?>" class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-arrow-left me-1"></i> Voltar para catálogo completo
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($services as $service): ?>
                            <tr style="border-bottom: 1px solid #EAECF0;" class="service-row">
                                <td class="ps-3 py-4 fw-bold text-dark">
                                    <a href="<?php echo $this->Html->url(array('action' => 'view', $service['Service']['id'])); ?>" class="text-dark text-decoration-none hover-underline">
                                        <?php echo h($service['Service']['name']); ?>
                                    </a>
                                </td>
                                <td class="text-muted py-4">
                                    <?php echo h($service['Service']['description']); ?>
                                </td>
                                <td class="text-end pe-3 py-4">
                                    <div class="d-flex gap-3 justify-content-end">
                                        <a href="<?php echo $this->Html->url(array('action' => 'edit', $service['Service']['id'])); ?>" class="text-secondary action-btn" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php echo $this->Form->postLink(
                                            '<i class="bi bi-trash"></i>',
                                            array('action' => 'delete', $service['Service']['id']),
                                            array('escape' => false, 'class' => 'text-secondary action-btn', 'title' => 'Excluir'),
                                            __('Tem certeza que deseja remover o serviço "%s"?', $service['Service']['name'])
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
