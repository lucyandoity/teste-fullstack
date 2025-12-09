<?php
App::uses('ClassRegistry', 'Utility');
App::uses('Cache', 'Cache');

class DashboardService {

    protected $_Provider;
    protected $_Service;
    protected $_ProviderService;

    /**
     * Cache key para as métricas do dashboard
     */
    const CACHE_KEY = 'dashboard_metrics';

    /**
     * Nome da configuração de cache
     */
    const CACHE_CONFIG = 'dashboard';

    public function __construct() {
        $this->_Provider = ClassRegistry::init('Provider');
        $this->_Service = ClassRegistry::init('Service');
        $this->_ProviderService = ClassRegistry::init('ProviderService');
    }

    /**
     * Retorna todas as métricas para o dashboard (com cache)
     *
     * @param bool $forceRefresh Força atualização do cache
     * @return array
     */
    public function getMetrics($forceRefresh = false) {
        if (!$forceRefresh) {
            $cached = Cache::read(self::CACHE_KEY, self::CACHE_CONFIG);
            if ($cached !== false) {
                return $cached;
            }
        }

        $metrics = array(
            'total_providers' => $this->_getTotalProviders(),
            'total_services_types' => $this->_getTotalServiceTypes(),
            'avg_ticket' => $this->_getAverageTicket(),
            'top_service' => $this->_getTopService(),
            'price_range' => $this->_getPriceRange(),
            'recent_providers' => $this->_getRecentProviders(),
            'services_chart_data' => $this->_getServicesChartData()
        );

        Cache::write(self::CACHE_KEY, $metrics, self::CACHE_CONFIG);

        return $metrics;
    }

    /**
     * Invalida o cache das métricas
     * Deve ser chamado quando há alterações em providers ou services
     *
     * @return bool
     */
    public function invalidateCache() {
        return Cache::delete(self::CACHE_KEY, self::CACHE_CONFIG);
    }

    protected function _getTotalProviders() {
        return $this->_Provider->find('count');
    }

    protected function _getTotalServiceTypes() {
        return $this->_Service->find('count');
    }

    protected function _getAverageTicket() {
        $result = $this->_ProviderService->find('first', array(
            'fields' => array('AVG(ProviderService.value) as avg_value')
        ));
        return !empty($result[0]['avg_value']) ? $result[0]['avg_value'] : 0;
    }

    protected function _getTopService() {
        // Encontra o serviço mais ofertado (vinculado a mais prestadores)
        $result = $this->_ProviderService->find('first', array(
            'fields' => array('Service.name', 'COUNT(ProviderService.service_id) as count'),
            'contain' => array('Service'),
            'group' => array('ProviderService.service_id'),
            'order' => array('count' => 'DESC')
        ));

        return !empty($result['Service']['name']) ? $result['Service']['name'] : 'Nenhum';
    }

    /**
     * Retorna faixa de preços (mínimo e máximo)
     */
    protected function _getPriceRange() {
        $result = $this->_ProviderService->find('first', array(
            'fields' => array(
                'MIN(ProviderService.value) as min_value',
                'MAX(ProviderService.value) as max_value'
            )
        ));

        return array(
            'min' => isset($result[0]['min_value']) ? (float)$result[0]['min_value'] : 0,
            'max' => isset($result[0]['max_value']) ? (float)$result[0]['max_value'] : 0
        );
    }

    /**
     * Retorna os 3 prestadores mais recentes com seus serviços
     */
    protected function _getRecentProviders() {
        $providers = $this->_Provider->find('all', array(
            'fields' => array('Provider.id', 'Provider.name', 'Provider.email'),
            'order' => array('Provider.created' => 'DESC'),
            'limit' => 3
        ));

        foreach ($providers as &$provider) {
            $services = $this->_ProviderService->find('all', array(
                'fields' => array('Service.name'),
                'contain' => array('Service'),
                'conditions' => array('ProviderService.provider_id' => $provider['Provider']['id'])
            ));
            $provider['Services'] = array_map(function($s) { return $s['Service']['name']; }, $services);
        }

        return $providers;
    }

    /**
     * Retorna dados para gráfico de serviços por quantidade de prestadores
     */
    protected function _getServicesChartData() {
        $results = $this->_ProviderService->find('all', array(
            'fields' => array(
                'Service.name',
                'COUNT(DISTINCT ProviderService.provider_id) as provider_count'
            ),
            'contain' => array('Service'),
            'group' => array('ProviderService.service_id'),
            'order' => array('provider_count' => 'DESC'),
            'limit' => 10
        ));

        $labels = array();
        $data = array();

        foreach ($results as $row) {
            $labels[] = $row['Service']['name'];
            $data[] = (int)$row[0]['provider_count'];
        }

        return array('labels' => $labels, 'data' => $data);
    }
}
