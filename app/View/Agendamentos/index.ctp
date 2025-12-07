<?php $this->assign('title', 'Agendamentos') ?>

<div class="container">
    <div class="header">
        <div class="header-left">
            <h1>Agendamentos</h1>
            <p>Acompanhe todos os agendamentos feitos</p>
        </div>
        <div class="header-right">
            <?php echo $this->Html->link(
                '<i class="fas fa-plus"></i> Novo Agendamento',
                array('controller' => 'agendamentos', 'action' => 'add'),
                array('class' => 'btn btn-add', 'escape' => false)
            ); ?>
            <?php echo $this->Html->link(
                '<i class="fas fa-undo"></i> Voltar',
                array('controller' => 'prestadores', 'action' => 'index'),
                array('class' => 'btn btn-secondary', 'escape' => false)
            ); ?>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Prestador</th>
                    <th>Serviço</th>
                    <th>Cliente</th>
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agendamentos as $agendamento): ?>
                    <tr>
                        <td>
                            <?php
                            if (!empty($agendamento['Prestador'])) {
                                echo h($agendamento['Prestador']['nome']);
                            } else {
                                echo "<span class='text-muted'>-</span>";
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($agendamento['Servico'])) {
                                echo h($agendamento['Servico']['nome']);
                            } else {
                                echo "<span class='text-muted'>-</span>";
                            }
                            ?>
                        </td>
                        <td><?php echo h($agendamento['Agendamento']['nome_cliente']); ?></td>
                        <td>
                            <?php
                            echo !empty($agendamento['Agendamento']['data_hora_inicio'])
                                ? date('d/m/Y H:i', strtotime($agendamento['Agendamento']['data_hora_inicio']))
                                : "<span class='text-muted'>-</span>";
                            ?>
                        </td>
                        <td>
                            <?php
                            echo !empty($agendamento['Agendamento']['data_hora_fim'])
                                ? date('d/m/Y H:i', strtotime($agendamento['Agendamento']['data_hora_fim']))
                                : "<span class='text-muted'>-</span>";
                            ?>
                        </td>
                        <td>
                            <span class="ag-status status-<?php echo strtolower($agendamento['Agendamento']['status']); ?>">
                                <?php echo h($agendamento['Agendamento']['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <?php echo $this->Html->link('<i class="fas fa-search"></i>', array('action' => 'view', $agendamento['Agendamento']['id']), array('class' => 'action-btn view', 'escape' => false, 'title' => 'Visualizar')); ?>
                                <?php echo $this->Html->link('<i class="fas fa-pen"></i>', array('action' => 'edit', $agendamento['Agendamento']['id']), array('class' => 'action-btn edit', 'escape' => false, 'title' => 'Editar')); ?>
                                <?php echo $this->Form->postLink('<i class="fas fa-trash"></i>', array('action' => 'delete', $agendamento['Agendamento']['id']), array('class' => 'action-btn delete', 'escape' => false, 'title' => 'Excluir'), __('Deseja realmente excluir o agendamento?')); ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>