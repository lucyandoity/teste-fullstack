<?php
App::uses('AppModel', 'Model');
/**
 * Prestador Model
 *
 * @property Servico $Servico
 * @property Agendamento $Agendamento
 */
class Prestador extends AppModel
{

	/**
	 * Validation rules
	 * @var array
	 */
	public $validate = array(
		'nome' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'O campo nome é obrigatório',
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email', true),
				'message' => 'Tipo de e-mail inválido.'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'Esse e-mail já está em uso.'
			)
		),
		'valor_servico' => array(
			'decimal' => array(
				'rule' => array('decimal', 2), 
				'message' => 'Por favor, insira um valor válido (ex: 150,50).',
				'allowEmpty' => false,
			),
		),
	);

	/**
	 * belongsTo associations
	 * UM Prestador agora PERTENCE A UM Serviço.
	 * @var array
	 */
	public $belongsTo = array(
		'Servico' => array(
			'className' => 'Servico',
			'foreignKey' => 'servico_id',
		)
	);


	/**
	 * hasMany associations
	 * (Esta associação com Agendamento permanece igual)
	 * @var array
	 */
	public $hasMany = array(
		'Agendamento' => array(
			'className' => 'Agendamento',
			'foreignKey' => 'prestador_id',
			'dependent' => false
		)
	);
}
