<section class="p-6 md:p-10 lg:p-12">
  <div class="max-w-7xl mx-auto">
    <header class="mb-8">
      <h1 class="text-3xl font-semibold text-text-dark">Prestadores de Serviço</h1>
      <p class="text-text-secondary mt-1">Veja sua lista de prestadores de serviço</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-xl border border-border-light shadow-sm">
        <p class="text-sm font-medium text-text-secondary">Total Prestadores</p>
        <p class="text-2xl font-bold text-text-dark"><?php echo isset($totalPrestadores) ? $totalPrestadores : 0; ?></p>
      </div>
      <div class="bg-white p-6 rounded-xl border border-border-light shadow-sm">
        <p class="text-sm font-medium text-text-secondary">Serviços Cadastrados</p>
        <p class="text-2xl font-bold text-text-dark"><?php echo isset($totalServicos) ? $totalServicos : 0; ?></p>
      </div>
      <div class="bg-white p-6 rounded-xl border border-border-light shadow-sm">
        <p class="text-sm font-medium text-text-secondary">Média de Valor</p>
        <p class="text-2xl font-bold text-text-dark">R$
          <?php echo isset($mediaValor) ? number_format($mediaValor, 2, ',', '.') : '0,00'; ?>
        </p>
      </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
      <div class="relative w-full sm:w-96">
        <?php echo $this->Form->create(false, array('type' => 'get', 'url' => array('action' => 'index'))); ?>
        <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
        <input type="text" name="q" placeholder="Buscar"
          class="w-full pl-10 pr-4 py-2 border border-border-light rounded-lg focus:ring-1 focus:ring-primary-red focus:border-primary-red transition duration-150"
          value="<?php echo isset($this->request->query['q']) ? h($this->request->query['q']) : ''; ?>" />
        <?php echo $this->Form->end(); ?>
      </div>
      <div class="flex space-x-3">
        <?php echo $this->Html->link(
          '<i data-lucide="upload" class="w-5 h-5"></i><span class="text-sm font-medium">Importar</span>',
          array('controller' => 'importacoes', 'action' => 'index'),
          array('escape' => false, 'class' => 'flex items-center space-x-2 px-4 py-2 border border-border-light text-text-dark rounded-lg hover:bg-gray-50 transition duration-150')
        ); ?>

        <?php echo $this->Html->link(
          '<i data-lucide="file-down" class="w-5 h-5"></i><span class="text-sm font-medium">Exportar</span>',
          array('action' => 'export_csv'),
          array('escape' => false, 'class' => 'flex items-center space-x-2 px-4 py-2 border border-border-light text-text-dark rounded-lg hover:bg-gray-50 transition duration-150', 'target' => '_blank')
        ); ?>

        <?php echo $this->Html->link(
          '<i data-lucide="plus" class="w-5 h-5"></i><span class="text-sm font-medium">Add novo prestador</span>',
          array('action' => 'add'),
          array('escape' => false, 'class' => 'flex items-center space-x-2 px-4 py-2 bg-primary-red text-white rounded-lg shadow-md hover:bg-red-600 transition duration-150')
        ); ?>
      </div>
    </div>

    <div class="bg-white border border-border-light rounded-xl shadow-custom overflow-x-auto">
      <table class="min-w-full divide-y divide-border-light">
        <thead class="bg-soft-gray">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">
              <?php echo $this->Paginator->sort('nome', 'Prestador'); ?>
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">
              <?php echo $this->Paginator->sort('telefone'); ?>
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">Serviços
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">Valor</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-text-secondary uppercase tracking-wider">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border-light">
          <?php foreach ($prestadores as $prestador): ?>
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <?php
                  $imgUrl = !empty($prestador['Prestador']['foto']) ? $this->webroot . 'img/' . $prestador['Prestador']['foto'] : 'https://placehold.co/40x40/f3f4f6/374151?text=' . strtoupper(substr($prestador['Prestador']['nome'], 0, 2));
                  ?>
                  <img class="h-10 w-10 rounded-full object-cover" src="<?php echo $imgUrl; ?>"
                    onerror="this.onerror=null;this.src='https://placehold.co/40x40/f3f4f6/374151?text=USER';"
                    alt="Avatar">
                  <div class="ml-4">
                    <div class="text-sm font-medium text-text-dark"><?php echo h($prestador['Prestador']['nome']); ?>
                    </div>
                    <div class="text-sm text-text-secondary"><?php echo h($prestador['Prestador']['email']); ?></div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-text-dark">
                <?php
                $phone = $prestador['Prestador']['telefone'];
                $formatted = preg_replace('/^(\d{2})(\d{5})(\d{4})$/', '($1) $2-$3', $phone);
                if ($formatted === $phone) {
                  $formatted = preg_replace('/^(\d{2})(\d{4})(\d{4})$/', '($1) $2-$3', $phone);
                }
                echo h($formatted);

                // WhatsApp Link
                $wppNum = preg_replace('/\D/', '', $phone);
                if ($wppNum) {
                  echo $this->Html->link(
                    '<svg class="w-6 h-6 ml-2 text-green-500 hover:text-green-600 inline-block" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.654-.698c.99.637 2.152 1.011 3.298 1.011h.005c3.181 0 5.768-2.586 5.768-5.766-.001-3.18-2.587-5.768-5.767-5.768zm0 13c-1.397 0-2.484-.336-3.708-1.002l-2.008.528.536-1.965c-.779-1.232-1.288-2.288-1.288-3.791 0-3.693 3.004-6.696 6.695-6.696s6.697 3.003 6.697 6.696c0 3.694-3.004 6.697-6.697 6.697zm3.179-4.809c-.197-.099-1.17-.578-1.353-.645-.182-.066-.315-.099-.447.099-.132.198-.513.645-.628.777-.116.132-.232.149-.43.049-.198-.1-.836-.308-1.592-.983-.591-.526-.991-1.175-1.107-1.373-.116-.198-.012-.305.087-.403.09-.089.198-.231.297-.347.099-.116.132-.198.198-.33.066-.132.033-.248-.017-.347-.05-.099-.447-1.076-.612-1.472-.161-.385-.325-.333-.447-.339-.115-.006-.247-.007-.38-.007s-.347.05-.529.247c-.182.198-.694.678-.694 1.652s.711 1.916.81 2.049c.099.132 1.398 2.132 3.385 2.992.472.204.84.327 1.13.419.48.152.916.13 1.258.079.381-.057 1.17-.478 1.335-.94.165-.462.165-.858.116-.94-.05-.083-.182-.132-.38-.231z"/></svg>',
                    'https://wa.me/55' . $wppNum,
                    array('escape' => false, 'target' => '_blank', 'title' => 'Chamar no WhatsApp')
                  );
                }
                ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-text-dark">
                <?php
                if (!empty($prestador['Servico'])) {
                  echo h($prestador['Servico'][0]['nome']);
                  if (count($prestador['Servico']) > 1) {
                    echo ' <span class="text-xs text-gray-500">+' . (count($prestador['Servico']) - 1) . '</span>';
                  }
                } else {
                  echo '-';
                }
                ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-text-dark">
                <?php
                if (!empty($prestador['Servico'])) {
                  echo 'R$ ' . number_format($prestador['Servico'][0]['valor'], 2, ',', '.');
                } else {
                  echo '-';
                }
                ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <?php echo $this->Html->link(
                  '<i data-lucide="edit-2" class="w-5 h-5"></i>',
                  array('action' => 'add', $prestador['Prestador']['id']),
                  array('escape' => false, 'class' => 'text-gray-400 hover:text-primary-red p-1 rounded-full transition duration-150 inline-block')
                ); ?>
                <?php echo $this->Form->postLink(
                  '<i data-lucide="trash-2" class="w-5 h-5"></i>',
                  array('action' => 'delete', $prestador['Prestador']['id']),
                  array('escape' => false, 'class' => 'text-gray-400 hover:text-primary-red p-1 rounded-full transition duration-150 inline-block', 'confirm' => 'Tem certeza que deseja excluir?')
                ); ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="flex justify-between items-center px-6 py-3 bg-white border-t border-border-light">
        <span class="text-sm text-text-secondary">
          <?php echo $this->Paginator->counter(array('format' => 'Página {:page} de {:pages}')); ?>
        </span>
        <div class="space-x-2">
          <?php echo $this->Paginator->prev('Anterior', array('class' => 'px-4 py-2 border border-border-light text-text-dark rounded-lg text-sm hover:bg-gray-50 transition duration-150'), null, array('class' => 'hidden')); ?>
          <?php echo $this->Paginator->next('Próximo', array('class' => 'px-4 py-2 border border-primary-red bg-primary-red text-white rounded-lg text-sm hover:bg-red-600 transition duration-150'), null, array('class' => 'hidden')); ?>
        </div>
      </div>
    </div>
  </div>
</section>