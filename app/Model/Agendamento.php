<?php
App::uses('AppModel', 'Model');
/**
 * Agendamento Model
 *
 * @property Prestador $Prestador
 * @property Servico $Servico
 */
class Agendamento extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'prestador_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'servico_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'data_hora_inicio' => array(
			'datetime' => array(
				'rule' => array('datetime'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'nome_cliente' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Prestador' => array(
			'className' => 'Prestador',
			'foreignKey' => 'prestador_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Servico' => array(
			'className' => 'Servico',
			'foreignKey' => 'servico_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
