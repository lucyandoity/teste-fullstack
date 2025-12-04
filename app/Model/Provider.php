<?php
App::uses('AppModel', 'Model');

/**
 * Provider Model
 *
 * Modelo responsável pela entidade Prestador de Serviço.
 * Define validações, relacionamentos e comportamentos.
 *
 * @property Service $Service
 */
class Provider extends AppModel {

/**
 * Nome da tabela no banco de dados
 *
 * @var string
 */
    public $useTable = 'providers';

/**
 * Nome de exibição do registro
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
 * Define as validações para cada campo do formulário,
 * com mensagens em português para melhor UX.
 *
 * @var array
 */
    public $validate = array(
        'name' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'O nome é obrigatório.',
                'required' => true,
                'allowEmpty' => false,
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O nome deve ter no máximo 255 caracteres.',
            ),
            'minLength' => array(
                'rule' => array('minLength', 3),
                'message' => 'O nome deve ter pelo menos 3 caracteres.',
            ),
        ),
        'email' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'O e-mail é obrigatório.',
                'required' => true,
            ),
            'email' => array(
                'rule' => array('email'),
                'message' => 'Informe um e-mail válido.',
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'Este e-mail já está cadastrado.',
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O e-mail deve ter no máximo 255 caracteres.',
            ),
        ),
        'phone' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'O telefone é obrigatório.',
                'required' => true,
            ),
            'phone' => array(
                'rule' => array('custom', '/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/'),
                'message' => 'Informe um telefone válido. Ex: 11 99999-9999',
                'allowEmpty' => false,
            ),
        ),
    );

/**
 * Associação hasMany com Service
 *
 * Um prestador pode ter vários serviços cadastrados.
 * Ao excluir o prestador, os serviços associados são removidos (CASCADE).
 *
 * @var array
 */
    public $hasMany = array(
        'Service' => array(
            'className' => 'Service',
            'foreignKey' => 'provider_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => 'Service.name ASC',
        )
    );

/**
 * Callback beforeSave
 *
 * Executa sanitização dos dados antes de salvar.
 *
 * @param array $options Opções de salvamento
 * @return bool
 */
    public function beforeSave($options = array()) {
        // Sanitiza o telefone removendo caracteres especiais para armazenamento limpo
        if (!empty($this->data[$this->alias]['phone'])) {
            $this->data[$this->alias]['phone'] = $this->_formatPhone(
                $this->data[$this->alias]['phone']
            );
        }

        // Normaliza o email para minúsculas
        if (!empty($this->data[$this->alias]['email'])) {
            $this->data[$this->alias]['email'] = strtolower(
                trim($this->data[$this->alias]['email'])
            );
        }

        return parent::beforeSave($options);
    }

/**
 * Formata o telefone para o padrão XX XXXXX-XXXX
 *
 * @param string $phone Telefone a ser formatado
 * @return string Telefone formatado
 */
    protected function _formatPhone($phone) {
        // Remove tudo que não é número
        $numbers = preg_replace('/\D/', '', $phone);

        // Formata baseado na quantidade de dígitos
        if (strlen($numbers) === 11) {
            return sprintf('%s %s-%s',
                substr($numbers, 0, 2),
                substr($numbers, 2, 5),
                substr($numbers, 7, 4)
            );
        } elseif (strlen($numbers) === 10) {
            return sprintf('%s %s-%s',
                substr($numbers, 0, 2),
                substr($numbers, 2, 4),
                substr($numbers, 6, 4)
            );
        }

        return $phone;
    }
}
