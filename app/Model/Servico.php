<?php
App::uses('AppModel', 'Model');
/**
 * Servico Model
 *
 * @property Prestadores $Prestadores
 */
class Servico extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'nome' => array(
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
 * hasAndBelongsToMany associations
 *
 * @var array
 */
public $hasAndBelongsToMany = array(
    'Prestador' => array(                      // Nome da Associação (singular, CamelCase)
        'className' => 'Prestador',            // Nome da Classe do Model
        'joinTable' => 'prestadores_servicos',
        'foreignKey' => 'servico_id',
        'associationForeignKey' => 'prestador_id',
        'unique' => 'keepExisting',
    )
);

}
