<?php
/**
 * ServiceService
 *
 * Camada de serviço responsável pela lógica de negócios relacionada a Serviços.
 * Separa as regras de negócio da camada de apresentação (Controller).
 *
 * @package app.Lib.Service
 */

App::uses('AppModel', 'Model');

class ServiceService {

/**
 * Instância do Model Service
 *
 * @var Service
 */
    protected $_Service;

/**
 * Instância do Model Provider
 *
 * @var Provider
 */
    protected $_Provider;

/**
 * Construtor
 */
    public function __construct() {
        $this->_Service = ClassRegistry::init('Service');
        $this->_Provider = ClassRegistry::init('Provider');
    }

/**
 * Busca um serviço pelo ID
 *
 * @param int $id ID do serviço
 * @return array|false Dados do serviço
 * @throws NotFoundException
 */
    public function findById($id) {
        if (!$this->_Service->exists($id)) {
            throw new NotFoundException(__('Serviço não encontrado'));
        }

        return $this->_Service->find('first', array(
            'conditions' => array('Service.id' => $id),
            'contain' => array(
                'ProviderService' => array(
                    'Provider'
                )
            )
        ));
    }

/**
 * Lista todos os prestadores para seleção
 *
 * @return array Lista de prestadores no formato id => name
 */
    public function getProvidersList() {
        return $this->_Provider->find('list', array(
            'fields' => array('Provider.id', 'Provider.name'),
            'order' => array('Provider.name' => 'asc')
        ));
    }

/**
 * Cria um novo serviço
 *
 * @param array $data Dados do serviço
 * @return array Resultado da operação
 */
    public function create($data) {
        $this->_Service->create();

        if ($this->_Service->save($data)) {
            return array(
                'success' => true,
                'message' => __('Serviço salvo com sucesso.'),
                'id' => $this->_Service->id
            );
        }

        return array(
            'success' => false,
            'message' => __('Não foi possível salvar o serviço. Verifique os dados e tente novamente.'),
            'validationErrors' => $this->_Service->validationErrors
        );
    }

/**
 * Atualiza um serviço existente
 *
 * @param int $id ID do serviço
 * @param array $data Dados atualizados
 * @return array Resultado da operação
 * @throws NotFoundException
 */
    public function update($id, $data) {
        if (!$this->_Service->exists($id)) {
            throw new NotFoundException(__('Serviço não encontrado'));
        }

        $data['Service']['id'] = $id;

        if ($this->_Service->save($data)) {
            return array(
                'success' => true,
                'message' => __('Serviço atualizado com sucesso.')
            );
        }

        return array(
            'success' => false,
            'message' => __('Erro ao atualizar o serviço. Verifique os dados e tente novamente.'),
            'validationErrors' => $this->_Service->validationErrors
        );
    }

/**
 * Remove um serviço
 *
 * @param int $id ID do serviço
 * @return array Resultado da operação
 * @throws NotFoundException
 */
    public function delete($id) {
        if (!$this->_Service->exists($id)) {
            throw new NotFoundException(__('Serviço não encontrado'));
        }

        if ($this->_Service->delete($id)) {
            return array(
                'success' => true,
                'message' => __('Serviço excluído com sucesso.')
            );
        }

        return array(
            'success' => false,
            'message' => __('Não foi possível excluir o serviço. Tente novamente.')
        );
    }

/**
 * Configura condições de busca para listagem
 *
 * @param array $queryParams Parâmetros de busca
 * @return array Configurações para o Paginator
 */
    public function buildSearchConditions($queryParams = array()) {
        $conditions = array();

        // Filtro por prestador
        if (!empty($queryParams['provider_id'])) {
            $conditions['Service.provider_id'] = $queryParams['provider_id'];
        }

        // Busca por nome ou descrição
        if (!empty($queryParams['search'])) {
            $search = $queryParams['search'];
            $conditions['OR'] = array(
                'Service.name LIKE' => '%' . $search . '%',
                'Service.description LIKE' => '%' . $search . '%'
            );
        }

        $order = array('Service.created' => 'desc');
        if (!empty($queryParams['sort']) && !empty($queryParams['direction'])) {
            $allowedFields = array('name');
            $allowedDirections = array('asc', 'desc');

            $sortField = $queryParams['sort'];
            $sortDirection = strtolower($queryParams['direction']);

            if (in_array($sortField, $allowedFields) && in_array($sortDirection, $allowedDirections)) {
                $order = array('Service.' . $sortField => $sortDirection);
            }
        }

        return array(
            'conditions' => $conditions,
            'limit' => 10,
            'order' => $order
        );
    }
}
