<?php $this->assign('title', 'Prestadores de Serviço'); ?>

<div class="container">
    <div class="header">
        <div class="header-left">
            <h1>Prestadores de Serviço</h1>
            <p>Gerencie seus prestadores de serviço</p>
        </div>
        <div class="header-right">
            <?php echo $this->Html->link(
                '<i class="fas fa-plus"></i> Add novo prestador',
                array('controller' => 'prestadores', 'action' => 'add'),
                array('class' => 'btn btn-add', 'escape' => false)
            ); ?>
        </div>
    </div>

    <!-- TODO: Caixa de Busca -->

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Prestador</th>
                    <th>Telefone</th>
                    <th>Serviços Associados</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prestadores as $prestador): ?>
                <tr>
                    <td>
                        <div class="provider-info">
                            <!-- TODO: Avatar com iniciais ou imagem -->
                            <div class="avatar" style="background-color: #8B5CF6;">
                                <?php echo strtoupper(substr($prestador['Prestador']['nome'], 0, 1)); ?>
                            </div>
                            <div class="provider-details">
                                <span class="provider-name"><?php echo h($prestador['Prestador']['nome']); ?></span>
                                <span class="provider-email"><?php echo h($prestador['Prestador']['email']); ?></span>
                            </div>
                        </div>
                    </td>
                    <td><?php echo h($prestador['Prestador']['telefone']); ?></td>
                    <td>
                        <?php 
                            if (!empty($prestador['Servico'])) {
                                echo count($prestador['Servico']) . ' serviço(s)';
                            } else {
                                echo 'Nenhum';
                            }
                        ?>
                    </td>
                    <td>
                        <div class="actions">
                            <?php echo $this->Html->link(
                                '<i class="fas fa-pen"></i>',
                                array('action' => 'edit', $prestador['Prestador']['id']),
                                array('class' => 'action-btn edit', 'escape' => false, 'title' => 'Editar')
                            ); ?>
                            
                            <?php echo $this->Form->postLink(
                                '<i class="fas fa-trash"></i>',
                                array('action' => 'delete', $prestador['Prestador']['id']),
                                array('class' => 'action-btn delete', 'escape' => false, 'title' => 'Excluir'),
                                __('Deseja realmente excluir # %s?', $prestador['Prestador']['id'])
                            ); ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <div class="pagination-info">
            <?php
            echo $this->Paginator->counter(
                'Página {:page} de {:pages}, mostrando {:current} registros de um total de {:count}'
            );
            ?>
        </div>
        <div class="pagination-buttons">
            <?php
                echo $this->Paginator->prev('Anterior', array('tag' => 'button'), null, array('class' => 'prev disabled', 'tag' => 'button'));
                echo $this->Paginator->next('Próximo', array('tag' => 'button'), null, array('class' => 'next disabled', 'tag' => 'button'));
            ?>
        </div>
    </div>
</div>