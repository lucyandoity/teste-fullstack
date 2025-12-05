<?php
/**
 * Provider Business Service
 *
 * Serviço de fachada que coordena operações de prestadores.
 * Delega responsabilidades específicas para serviços especializados:
 * - ProviderQueryService: busca, filtros, ordenação e paginação
 * - ProviderCrudService: criação, atualização e exclusão
 *
 * @package app.Lib.Service
 */

App::uses('ProviderQueryService', 'Lib/Service');
App::uses('ProviderCrudService', 'Lib/Service');

class ProviderBusinessService {

/**
 * Serviço de consulta de prestadores
 *
 * @var ProviderQueryService
 */
    protected $_queryService;

/**
 * Serviço CRUD de prestadores
 *
 * @var ProviderCrudService
 */
    protected $_crudService;

/**
 * Construtor
 */
    public function __construct() {
        $this->_queryService = new ProviderQueryService();
        $this->_crudService = new ProviderCrudService();
    }

/**
 * Lista prestadores com filtros, ordenação e paginação
 *
 * @param array $queryParams Parâmetros de busca (search, sort, direction, page)
 * @return array Array com 'providers', 'totalCount' e 'paging'
 */
    public function listWithFilters($queryParams = array()) {
        return $this->_queryService->listWithFilters($queryParams);
    }

/**
 * Busca um prestador pelo ID
 *
 * @param int $id ID do prestador
 * @return array|false Dados do prestador ou false se não encontrado
 * @throws NotFoundException
 */
    public function findById($id) {
        return $this->_queryService->findById($id);
    }

/**
 * Cria um novo prestador
 *
 * @param array $data Dados do prestador
 * @return array Resultado da operação com status e mensagem
 */
    public function create($data) {
        return $this->_crudService->create($data);
    }

/**
 * Atualiza um prestador existente
 *
 * @param int $id ID do prestador
 * @param array $data Dados atualizados
 * @return array Resultado da operação
 * @throws NotFoundException
 */
    public function update($id, $data) {
        return $this->_crudService->update($id, $data);
    }

/**
 * Remove um prestador
 *
 * @param int $id ID do prestador
 * @return array Resultado da operação
 * @throws NotFoundException
 */
    public function delete($id) {
        return $this->_crudService->delete($id);
    }
}
