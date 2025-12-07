<?php
/**
 * Service Business Service
 *
 * Serviço de fachada que coordena operações de serviços.
 * Delega responsabilidades específicas para serviços especializados:
 * - ServiceQueryService: busca, filtros, ordenação e paginação
 * - ServiceCrudService: criação, atualização e exclusão
 *
 * @package app.Lib.Service.Service
 */

App::uses('ServiceQueryService', 'Lib/Service/Service');
App::uses('ServiceCrudService', 'Lib/Service/Service');
App::uses('AppModel', 'Model');

class ServiceService {

/**
 * Serviço de consulta de serviços
 *
 * @var ServiceQueryService
 */
    protected $_queryService;

/**
 * Serviço CRUD de serviços
 *
 * @var ServiceCrudService
 */
    protected $_crudService;

/**
 * Construtor
 */
    public function __construct() {
        $this->_queryService = new ServiceQueryService();
        $this->_crudService = new ServiceCrudService();
    }

/**
 * Busca um serviço pelo ID
 *
 * @param int $id ID do serviço
 * @return array|false Dados do serviço ou false se não encontrado
 * @throws NotFoundException
 */
    public function findById($id) {
        return $this->_queryService->findById($id);
    }

/**
 * Configura condições de busca para listagem
 *
 * @param array $queryParams Parâmetros de busca
 * @return array Configurações para o Paginator
 */
    public function buildSearchConditions($queryParams = array()) {
        return $this->_queryService->buildSearchConditions($queryParams);
    }

/**
 * Cria um novo serviço
 *
 * @param array $data Dados do serviço
 * @return array Resultado da operação com status e mensagem
 */
    public function create($data) {
        return $this->_crudService->create($data);
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
        return $this->_crudService->update($id, $data);
    }

/**
 * Remove um serviço
 *
 * @param int $id ID do serviço
 * @return array Resultado da operação
 * @throws NotFoundException
 */
    public function delete($id) {
        return $this->_crudService->delete($id);
    }
}
