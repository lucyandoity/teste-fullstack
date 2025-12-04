<div class="prestadores view">
<h2><?php echo __('Prestador'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($prestador['Prestador']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nome'); ?></dt>
		<dd>
			<?php echo h($prestador['Prestador']['nome']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($prestador['Prestador']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($prestador['Prestador']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($prestador['Prestador']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Prestador'), array('action' => 'edit', $prestador['Prestador']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Prestador'), array('action' => 'delete', $prestador['Prestador']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $prestador['Prestador']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Prestadores'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Prestador'), array('action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
    <h3><?php echo __('Related Servicos'); ?></h3>
    <?php if (!empty($prestador['Servico'])): ?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php echo __('Id'); ?></th>
        <th><?php echo __('Nome'); ?></th>
        <th><?php echo __('Descricao'); ?></th>
        <th><?php echo __('Valor'); ?></th>
        <th><?php echo __('Created'); ?></th>
        <th><?php echo __('Modified'); ?></th>
        <th class="actions"><?php echo __('Actions'); ?></th>
    </tr>
    <?php foreach ($prestador['Servico'] as $servico): ?>
        <tr>
            <td><?php echo $servico['id']; ?></td>
            <td><?php echo $servico['nome']; ?></td>
            <td><?php echo $servico['descricao']; ?></td>
            <td><?php echo $servico['valor']; ?></td>
            <td><?php echo $servico['created']; ?></td>
            <td><?php echo $servico['modified']; ?></td>
            <td class="actions">
                <?php echo $this->Html->link(__('View'), array('controller' => 'servicos', 'action' => 'view', $servico['id'])); ?>
                <?php echo $this->Html->link(__('Edit'), array('controller' => 'servicos', 'action' => 'edit', $servico['id'])); ?>
                <?php echo $this->Form->postLink(__('Delete'), array('controller' => 'servicos', 'action' => 'delete', $servico['id']), array('confirm' => __('Are you sure you want to delete # %s?', $servico['id']))); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>
</div>
