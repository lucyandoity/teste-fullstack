<?php
/**
 * Service Query Service
 *
 * Serviço responsável por consultas e listagem de serviços.
 * Implementa busca com filtros, ordenação e paginação.
 *
 * @package app.Lib.Service.Service
 */

App::uses('AppModel', 'Model');

class ServiceQueryService {

/**
 * Instância do Model Service
 *
 * @var Service
 */
    protected $_Service;

/**
 * Construtor
 */
    public function __construct() {
        $this->_Service = ClassRegistry::init('Service');
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

