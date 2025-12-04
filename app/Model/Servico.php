<?php
App::uses('AppModel', 'Model');
/**
 * Servico Model
 *
 * @property Prestador $Prestador
 * @property Agendamento $Agendamento
 */
class Servico extends AppModel
{
	public $displayField = 'nome';

	/**
	 * Validation rules
	 * (Seu array de validação continua o mesmo)
	 * @var array
	 */
	public $validate = array(
		'nome' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);

	// --- INÍCIO DA ATUALIZAÇÃO ---

	/**
	 * hasMany associations
	 * UM Serviço agora TEM MUITOS Prestadores e Agendamentos.
	 * @var array
	 */
	public $hasMany = array(
		'Prestador' => array(
			'className' => 'Prestador',
			'foreignKey' => 'servico_id',
			'dependent' => false // false para não deletar os prestadores se o serviço for deletado
		),
		'Agendamento' => array(
			'className' => 'Agendamento',
			'foreignKey' => 'servico_id',
			'dependent' => false
		)
	);

	// --- FIM DA ATUALIZAÇÃO ---
}