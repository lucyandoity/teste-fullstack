<?php
App::uses('AppModel', 'Model');
/**
 * Prestador Model
 *
 * @property Servico $Servico
 */
class Prestador extends AppModel
{

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'nome' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'O campo nome é obrigatório',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email' => array(
			'rule' => array('email', true),
			'message' => 'Por favor, forneça um endereço de email válido.'
		),
		'isUnique' => array(
			'rule' => 'isUnique',
			'message' => 'Esse e-mail já está em uso.'
		)
	);

	// The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * hasAndBelongsToMany associations
	 *
	 * @var array
	 */
	public $hasAndBelongsToMany = array(
		'Servico' => array(
			'className' => 'Servico',
			'joinTable' => 'prestadores_servicos',
			'foreignKey' => 'prestador_id',
			'associationForeignKey' => 'servico_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

	public $hasMany = array(
		'Agendamento' => array(
			'className' => 'Agendamento',
			'foreignKey' => 'prestador_id',
			'dependent' => false 
		)
	);
}
