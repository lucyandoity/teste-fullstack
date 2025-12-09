<div class="providers view">
    <h2><?php echo __('Prestador'); ?></h2>
    <dl>
        <dt><?php echo __('ID'); ?></dt>
        <dd><?php echo h($provider['Provider']['id']); ?>&nbsp;</dd>

        <dt><?php echo __('Nome'); ?></dt>
        <dd><?php echo h($provider['Provider']['name']); ?>&nbsp;</dd>

        <dt><?php echo __('Email'); ?></dt>
        <dd><?php echo h($provider['Provider']['email']); ?>&nbsp;</dd>

        <dt><?php echo __('Telefone'); ?></dt>
        <dd><?php echo h($provider['Provider']['phone']); ?>&nbsp;</dd>

        <dt><?php echo __('Data de Criação'); ?></dt>
        <dd><?php echo h($provider['Provider']['created']); ?>&nbsp;</dd>

        <dt><?php echo __('Data de Atualização'); ?></dt>
        <dd><?php echo h($provider['Provider']['modified']); ?>&nbsp;</dd>
    </dl>

    <h3><?php echo __('Serviços Vinculados'); ?></h3>
    <?php if (!empty($provider['ProviderService'])): ?>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?php echo __('Serviço'); ?></th>
                <th><?php echo __('Valor'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($provider['ProviderService'] as $providerService): ?>
            <tr>
                <td><?php echo h($providerService['Service']['name']); ?></td>
                <td><?php echo 'R$ ' . number_format((float)$providerService['value'], 2, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="notice"><?php echo __('Nenhum serviço vinculado a este prestador.'); ?></p>
    <?php endif; ?>
</div>

<div class="actions">
    <h3><?php echo __('Ações'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('Editar Prestador'), array('action' => 'edit', $provider['Provider']['id'])); ?></li>
        <li><?php echo $this->Form->postLink(__('Excluir Prestador'), array('action' => 'delete', $provider['Provider']['id']), array('confirm' => __('Tem certeza que deseja excluir o prestador #%s?', $provider['Provider']['id']))); ?></li>
        <li><?php echo $this->Html->link(__('Listar Prestadores'), array('action' => 'index')); ?></li>
        <li><?php echo $this->Html->link(__('Novo Prestador'), array('action' => 'add')); ?></li>
    </ul>
</div>
