<div class="row justify-content-center">
    <div class="col-12">

        <div class="card-doity">

            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h2 class="fw-bold mb-1 fs-3">Prestadores de Serviço</h2>
                    <p class="text-muted mb-0">Veja sua lista de prestadores de serviço</p>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-light border d-flex align-items-center gap-2 px-3">
                        <i class="bi bi-upload"></i>
                        <span>Importar</span>
                    </button>
                    <a href="<?php echo $this->Html->url(array('action' => 'add')); ?>" class="btn btn-danger text-white d-flex align-items-center gap-2 px-3">
                        <i class="bi bi-plus-lg"></i>
                        <span>Add novo prestador</span>
                    </a>
                </div>
            </div>

            <div class="mb-4">
                <?php echo $this->Form->create('Provider', array('type' => 'get', 'url' => array('action' => 'index'))); ?>
                <div class="input-group" style="max-width: 100%;"> <span class="input-group-text bg-white border-end-0 ps-3">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <?php echo $this->Form->input('search', array(
                        'label' => false,
                        'div' => false,
                        'class' => 'form-control border-start-0 ps-2',
                        'placeholder' => 'Buscar',
                        'value' => $this->request->query('search')
                    )); ?>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
						<tr>
							<th scope="col" class="py-3 ps-3 text-secondary small text-uppercase fw-bold" style="width: 35%;">Prestador</th>
							<th scope="col" class="py-3 text-secondary small text-uppercase fw-bold" style="width: 20%;">Telefone</th>

							<th scope="col" class="py-3 text-secondary small text-uppercase fw-bold" style="width: 45%;">
								<div class="d-flex justify-content-between pe-3"> <span>Serviços Prestados</span>
									<span>Valor</span>
								</div>
							</th>

							<th scope="col" class="py-3 pe-3 text-end" style="width: 50px;"></th>
						</tr>
					</thead>
                    <tbody>
                        <?php if (empty($providers)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    Nenhum prestador encontrado.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($providers as $provider): ?>
                            <tr style="border-bottom: 1px solid #EAECF0;">
                                <td class="ps-3 py-4 align-top"> <div class="d-flex align-items-center">
                                        <?php
                                        $photoPath = !empty($provider['Provider']['photo']) ? $provider['Provider']['photo'] : null;
                                        if ($photoPath && file_exists(WWW_ROOT . 'img' . DS . $photoPath)) {
                                            echo $this->Html->image($photoPath, array('class' => 'rounded-circle me-3', 'width' => '40', 'height' => '40', 'style' => 'object-fit: cover;'));
                                        } else {
                                            echo '<div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 text-secondary fw-bold" style="width: 40px; height: 40px; background-color: #F2F4F7;">' . strtoupper(substr($provider['Provider']['name'], 0, 2)) . '</div>';
                                        }
                                        ?>
                                        <div>
                                            <div class="fw-bold text-dark" style="color: #101828;"><?php echo h($provider['Provider']['name']); ?></div>
                                            <div class="text-muted small"><?php echo h($provider['Provider']['email']); ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-muted align-top py-4"><?php echo h($provider['Provider']['phone']); ?></td>

                                <td class="align-top py-4">
                                    <?php if (!empty($provider['ProviderService'])): ?>
                                        <div class="d-flex flex-column gap-2"> <?php foreach ($provider['ProviderService'] as $ps): ?>
                                                <?php $serviceName = isset($ps['Service']['name']) ? $ps['Service']['name'] : 'Serviço s/ nome'; ?>

                                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-1" style="border-color: #f2f4f7 !important;">
                                                    <span class="text-dark small me-3">
                                                        <?php echo h($serviceName); ?>
                                                    </span>
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

                                <td class="text-end pe-3 align-top py-4">
                                    <div class="d-flex gap-3 justify-content-end">
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
