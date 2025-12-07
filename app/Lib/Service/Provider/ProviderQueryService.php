<?php
/**
 * Provider Query Service
 *
 * Serviço responsável por consultas e listagem de prestadores.
 * Implementa busca otimizada com filtros, ordenação e paginação.
 *
 * @package app.Lib.Service
 */

App::uses('AppModel', 'Model');

class ProviderQueryService {

/**
 * Instância do Model Provider
 *
 * @var Provider
 */
    protected $_Provider;

/**
 * Limite padrão de itens por página
 *
 * @var int
 */
    protected $_defaultLimit = 6;

/**
 * Campos permitidos para ordenação
 *
 * @var array
 */
    protected $_allowedSortFields = array('name', 'email', 'value');

/**
 * Direções permitidas para ordenação
 *
 * @var array
 */
    protected $_allowedDirections = array('asc', 'desc');

/**
 * Tamanho máximo do termo de busca
 *
 * @var int
 */
    protected $_maxSearchLength = 100;

/**
 * Construtor
 */
    public function __construct() {
        $this->_Provider = ClassRegistry::init('Provider');
    }

/**
 * Lista prestadores com filtros, ordenação e paginação
 *
 * @param array $queryParams Parâmetros de busca (search, sort, direction, page)
 * @return array Array com 'providers', 'totalCount' e 'paging'
 */
    public function listWithFilters($queryParams = array()) {
        $settings = $this->_buildQuerySettings($queryParams);

        // Busca otimizada: filtra no banco quando há termo de busca
        if (!empty($settings['searchTerm'])) {
            $allProviders = $this->_searchOptimized($settings);
        } else {
            $allProviders = $this->_Provider->find('all', array(
                'conditions' => $settings['conditions'],
                'contain' => $settings['contain'],
                'order' => $settings['order']
            ));
        }

        // Ordenação por valor
        if ($settings['sortByValue']) {
            $allProviders = $this->_sortByServiceValue($allProviders, $settings['sortDirection']);
        }

        // Paginação
        return $this->_paginate($allProviders, $queryParams);
    }

/**
 * Busca um prestador pelo ID
 *
 * @param int $id ID do prestador
 * @return array|false Dados do prestador
 * @throws NotFoundException
 */
    public function findById($id) {
        if (!$this->_Provider->exists($id)) {
            throw new NotFoundException(__('Prestador não encontrado'));
        }

        return $this->_Provider->find('first', array(
            'conditions' => array('Provider.id' => $id),
            'contain' => array(
                'ProviderService' => array('Service')
            )
        ));
    }

/**
 * Busca prestadores de forma otimizada usando SQL seguro
 *
 * @param array $settings Configurações de busca
 * @return array Prestadores encontrados
 */
    protected function _searchOptimized($settings) {
        $searchTerm = $settings['searchTerm'];
        $db = $this->_Provider->getDataSource();

        // Escapa valor usando método seguro do CakePHP
        $escapedTerm = $db->value('%' . $this->_sanitizeForLike($searchTerm) . '%');

        // Detecta busca por telefone
        $phoneDigitsOnly = preg_replace('/[^0-9]/', '', $searchTerm);
        $isPhoneSearch = strlen($phoneDigitsOnly) >= 8;

        // Subquery para serviços
        $serviceSubquery = $this->_buildServiceSubquery($db, $escapedTerm);

        // Condições com valores escapados
        $orConditions = array(
            "Provider.name LIKE {$escapedTerm}",
            "Provider.email LIKE {$escapedTerm}",
            "Provider.id IN ({$serviceSubquery})"
        );

        // Busca por telefone normalizado
        if ($isPhoneSearch) {
            $escapedPhone = $db->value('%' . $phoneDigitsOnly . '%');
            $orConditions[] = "REPLACE(REPLACE(REPLACE(REPLACE(Provider.phone, ' ', ''), '-', ''), '(', ''), ')', '') LIKE {$escapedPhone}";
        } else {
            $orConditions[] = "Provider.phone LIKE {$escapedTerm}";
        }

        return $this->_Provider->find('all', array(
            'conditions' => array('OR' => $orConditions),
            'contain' => $settings['contain'],
            'order' => $settings['order']
        ));
    }

/**
 * Constrói subquery para buscar por serviços
 *
 * @param DataSource $db DataSource do banco
 * @param string $escapedTerm Termo já escapado
 * @return string SQL da subquery
 */
    protected function _buildServiceSubquery($db, $escapedTerm) {
        $ProviderService = ClassRegistry::init('ProviderService');

        return $db->buildStatement(array(
            'fields' => array('DISTINCT ProviderService.provider_id'),
            'table' => $db->fullTableName($ProviderService),
            'alias' => 'ProviderService',
            'joins' => array(
                array(
                    'table' => 'services',
                    'alias' => 'Service',
                    'type' => 'INNER',
                    'conditions' => array('Service.id = ProviderService.service_id')
                )
            ),
            'conditions' => array("Service.name LIKE {$escapedTerm}"),
            'order' => null,
            'group' => null,
            'limit' => null
        ), $ProviderService);
    }

/**
 * Sanitiza string para uso em cláusula LIKE
 *
 * @param string $term Termo a sanitizar
 * @return string Termo sanitizado
 */
    protected function _sanitizeForLike($term) {
        $term = str_replace('\\', '\\\\', $term);
        $term = str_replace('%', '\\%', $term);
        $term = str_replace('_', '\\_', $term);
        return $term;
    }

/**
 * Constrói configurações de query a partir dos parâmetros
 *
 * @param array $queryParams Parâmetros da requisição
 * @return array Configurações
 */
    protected function _buildQuerySettings($queryParams = array()) {
        $searchTerm = '';
        if (!empty($queryParams['search'])) {
            $searchTerm = trim($queryParams['search']);
            $searchTerm = mb_substr($searchTerm, 0, $this->_maxSearchLength);
        }

        $order = array('Provider.created' => 'desc');
        $sortByValue = false;
        $sortDirection = 'desc';

        if (!empty($queryParams['sort']) && !empty($queryParams['direction'])) {
            $sortField = $queryParams['sort'];
            $sortDirection = strtolower($queryParams['direction']);

            if (in_array($sortField, $this->_allowedSortFields) &&
                in_array($sortDirection, $this->_allowedDirections)) {
                if ($sortField === 'value') {
                    $sortByValue = true;
                } else {
                    $order = array('Provider.' . $sortField => $sortDirection);
                }
            }
        }

        return array(
            'conditions' => array(),
            'contain' => array(
                'ProviderService' => array(
                    'Service' => array(
                        'fields' => array('Service.id', 'Service.name')
                    )
                )
            ),
            'order' => $order,
            'sortByValue' => $sortByValue,
            'sortDirection' => $sortDirection,
            'searchTerm' => $searchTerm
        );
    }

/**
 * Ordena prestadores pela soma dos valores dos serviços
 *
 * @param array $providers Lista de prestadores
 * @param string $direction Direção (asc/desc)
 * @return array Prestadores ordenados
 */
    protected function _sortByServiceValue($providers, $direction) {
        usort($providers, function($a, $b) use ($direction) {
            $sumA = $this->_calculateServiceValueSum($a);
            $sumB = $this->_calculateServiceValueSum($b);

            $result = $sumA <=> $sumB;
            return ($direction === 'desc') ? -$result : $result;
        });

        return $providers;
    }

/**
 * Calcula a soma dos valores dos serviços
 *
 * @param array $provider Dados do prestador
 * @return float Soma dos valores
 */
    protected function _calculateServiceValueSum($provider) {
        $sum = 0;
        if (!empty($provider['ProviderService'])) {
            foreach ($provider['ProviderService'] as $ps) {
                if (isset($ps['value'])) {
                    $sum += floatval($ps['value']);
                }
            }
        }
        return $sum;
    }

/**
 * Aplica paginação aos resultados
 *
 * @param array $items Lista de itens
 * @param array $queryParams Parâmetros da requisição
 * @return array Array com 'providers', 'totalCount' e 'paging'
 */
    protected function _paginate($items, $queryParams) {
        $limit = $this->_defaultLimit;
        $page = isset($queryParams['page']) ? max(1, (int)$queryParams['page']) : 1;
        $totalCount = count($items);
        $totalPages = $totalCount > 0 ? (int)ceil($totalCount / $limit) : 1;

        $page = min($page, $totalPages);
        $offset = ($page - 1) * $limit;

        $paginatedItems = array_slice($items, $offset, $limit);

        return array(
            'providers' => $paginatedItems,
            'totalCount' => $totalCount,
            'paging' => array(
                'page' => $page,
                'current' => count($paginatedItems),
                'count' => $totalCount,
                'prevPage' => ($page > 1),
                'nextPage' => ($page < $totalPages),
                'pageCount' => $totalPages,
                'order' => null,
                'limit' => $limit,
                'options' => array(),
                'paramType' => 'named'
            )
        );
    }
}
