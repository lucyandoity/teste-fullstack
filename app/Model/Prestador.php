<?php
App::uses('AppModel', 'Model');

class Prestador extends AppModel
{
  public $displayField = 'nome';
  public $useTable = 'prestadores';

  public $validate = array(
    'nome' => array(
      'notBlank' => array(
        'rule' => array('notBlank'),
        'message' => 'Por favor informe o nome',
      ),
    ),
    'email' => array(
      'email' => array(
        'rule' => array('email'),
        'message' => 'Email inválido',
      ),
      'unique' => array(
        'rule' => 'isUnique',
        'message' => 'Este email já está cadastrado',
      ),
    ),
    'telefone' => array(
      'notBlank' => array(
        'rule' => array('notBlank'),
        'message' => 'Por favor informe o telefone',
      ),
      'unique' => array(
        'rule' => 'isUnique',
        'message' => 'Este telefone já está cadastrado',
      ),
    ),
  );

  public $hasMany = array(
    'Servico' => array(
      'className' => 'Servico',
      'foreignKey' => 'prestador_id',
      'dependent' => true,
      'conditions' => '',
      'fields' => '',
      'order' => '',
      'limit' => '',
      'offset' => '',
      'exclusive' => '',
      'finderQuery' => '',
      'counterQuery' => ''
    )
  );
  public function beforeValidate($options = array())
  {
    if (!empty($this->data[$this->alias]['telefone'])) {
      $this->data[$this->alias]['telefone'] = preg_replace('/\D/', '', $this->data[$this->alias]['telefone']);
    }
    return true;
  }
}
