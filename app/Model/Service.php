<?php
App::uses('AppModel', 'Model');

/**
 * Service Model
 *
 * Modelo responsável pelo Catálogo de Serviços.
 * Representa os tipos de serviço disponíveis no sistema (Lista Mestre).
 * O preço é definido na tabela pivô ProviderService.
 *
 * @property ProviderService $ProviderService
 */
class Service extends AppModel {

/**
 * Nome da tabela no banco de dados
 *
 * @var string
 */
    public $useTable = 'services';

/**
 * Nome de exibição do registro (usado em find('list'))
 *
 * @var string
 */
    public $displayField = 'name';

/**
 * Comportamentos do Model
 *
 * @var array
 */
    public $actsAs = array('Containable');

/**
 * Regras de validação
 *
 * @var array
 */
    public $validate = array(
        'name' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'O nome do serviço é obrigatório.',
                'required' => true,
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O nome deve ter no máximo 255 caracteres.',
            ),
            'minLength' => array(
                'rule' => array('minLength', 2),
                'message' => 'O nome deve ter pelo menos 2 caracteres.',
            ),
        ),
        'description' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 5000),
                'message' => 'A descrição deve ter no máximo 5000 caracteres.',
                'allowEmpty' => true,
            ),
        ),
    );

/**
 * Associação hasMany com ProviderService
 *
 * Um tipo de serviço pode ser oferecido por muitos prestadores
 * (através da tabela pivô provider_services).
 *
 * @var array
 */
    public $hasMany = array(
        'ProviderService' => array(
            'className' => 'ProviderService',
            'foreignKey' => 'service_id',
            'dependent' => true
        )
    );
}
