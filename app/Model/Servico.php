<?php
App::uses('AppModel', 'Model');

class Servico extends AppModel
{
  public $displayField = 'nome';
  public $useTable = 'servicos';

  public $validate = array(
    'nome' => array(
      'notBlank' => array(
        'rule' => array('notBlank'),
        'message' => 'Por favor informe o nome do serviço',
      ),
    ),
    'valor' => array(
      'decimal' => array(
        'rule' => array('decimal'),
        'message' => 'Por favor informe um valor válido',
      ),
    ),
  );

  public $belongsTo = array(
    'Prestador' => array(
      'className' => 'Prestador',
      'foreignKey' => 'prestador_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    )
  );
}
