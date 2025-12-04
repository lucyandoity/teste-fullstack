<?php
App::uses('AppModel', 'Model');

/**
 * ProviderService Model
 *
 * Tabela pivô que representa o vínculo entre Prestador e Serviço.
 * Armazena qual serviço do catálogo o prestador oferece e por qual preço.
 *
 * @property Provider $Provider
 * @property Service $Service
 */
class ProviderService extends AppModel {

/**
 * Nome da tabela no banco de dados
 *
 * @var string
 */
    public $useTable = 'provider_services';

/**
 * Comportamentos do Model
 *
 * @var array
 */
    public $actsAs = array('Containable');

/**
 * Associações belongsTo
 *
 * Este vínculo pertence a um Prestador e a um Serviço do catálogo.
 *
 * @var array
 */
    public $belongsTo = array(
        'Provider' => array(
            'className' => 'Provider',
            'foreignKey' => 'provider_id'
        ),
        'Service' => array(
            'className' => 'Service',
            'foreignKey' => 'service_id'
        )
    );

/**
 * Regras de validação
 *
 * @var array
 */
    public $validate = array(
        'service_id' => array(
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Selecione um serviço válido.',
                'allowEmpty' => false
            )
        ),
        'value' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'O valor é obrigatório.',
                'last' => true
            ),
            'validAmount' => array(
                'rule' => array('custom', '/^\d+([.,]\d{1,2})?$/'),
                'message' => 'Informe um valor válido (ex: 100, 100.00 ou 100,50)'
            )
        )
    );

/**
 * Callback beforeSave
 *
 * Sanitiza o valor monetário antes de salvar.
 *
 * @param array $options Opções de salvamento
 * @return bool
 */
    public function beforeSave($options = array()) {
        if (!empty($this->data[$this->alias]['value'])) {
            // Troca vírgula por ponto para compatibilidade com MySQL
            $this->data[$this->alias]['value'] = str_replace(',', '.', $this->data[$this->alias]['value']);
        }
        return true;
    }
}
