<?php
/**
 * CSV Import Service
 *
 * Serviço de orquestração para importação de prestadores via arquivo CSV.
 * Delega validação de arquivo para CsvFileValidator e validação de dados para CsvRowValidator.
 *
 * Este serviço usa acesso direto aos Models (Provider, Service) ao invés de
 * ProviderCrudService porque a importação em lote requer:
 * - Transação global envolvendo múltiplos providers
 * - Lógica específica de agrupamento por email
 * - Skip de processamento de foto (não aplicável a CSV)
 *
 * @package app.Lib.Service
 */

App::uses('CsvFileValidator', 'Lib/Service/Csv');
App::uses('CsvRowValidator', 'Lib/Service/Csv');
App::uses('DashboardService', 'Lib/Service');

class CsvImportService {

/**
 * Validador de arquivo
 *
 * @var CsvFileValidator
 */
    protected $_fileValidator;

/**
 * Validador de linha
 *
 * @var CsvRowValidator
 */
    protected $_rowValidator;

/**
 * Serviço do Dashboard (para invalidação de cache)
 *
 * @var DashboardService
 */
    protected $_dashboardService;

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
 * Colunas esperadas no CSV (header)
 *
 * @var array
 */
    protected $_expectedColumns = array(
        'name',
        'email',
        'phone',
        'service_name',
        'service_value'
    );

/**
 * Erros encontrados durante importação
 *
 * @var array
 */
    protected $_errors = array();

/**
 * Contadores de resultado
 *
 * @var array
 */
    protected $_stats = array(
        'total' => 0,
        'imported' => 0,
        'skipped' => 0,
        'services_created' => 0
    );

/**
 * Construtor - Injeção de dependências
 *
 * @param CsvFileValidator|null $fileValidator
 * @param CsvRowValidator|null $rowValidator
 */
    public function __construct(
        CsvFileValidator $fileValidator = null,
        CsvRowValidator $rowValidator = null
    ) {
        $this->_fileValidator = $fileValidator ?: new CsvFileValidator();
        $this->_rowValidator = $rowValidator ?: new CsvRowValidator();
        $this->_dashboardService = new DashboardService();
        $this->_Service = ClassRegistry::init('Service');
        $this->_Provider = ClassRegistry::init('Provider');
    }

/**
 * Processa o arquivo CSV de importação
 *
 * @param array $file Dados do arquivo ($_FILES)
 * @return array Resultado com 'success', 'message', 'stats', 'errors'
 */
    public function import($file) {
        $this->_resetState();

        // 1. Validar arquivo (segurança)
        $fileValidation = $this->_fileValidator->validate($file);
        if (!$fileValidation['valid']) {
            return $this->_buildErrorResponse($fileValidation['message']);
        }

        // 2. Parsear CSV
        $parseResult = $this->_parseCsv($file['tmp_name']);
        if (!$parseResult['success']) {
            return $this->_buildErrorResponse($parseResult['message']);
        }

        $rows = $parseResult['rows'];
        if (empty($rows)) {
            return $this->_buildErrorResponse(__('O arquivo CSV está vazio.'));
        }

        // 3. Agrupar linhas por email (suporte a múltiplos serviços)
        $grouped = $this->_groupByEmail($rows);
        $this->_stats['total'] = count($grouped);

        // 4. Processar prestadores (com transação)
        return $this->_processProviders($grouped);
    }

/**
 * Reseta o estado do serviço para nova importação
 *
 * @return void
 */
    protected function _resetState() {
        $this->_errors = array();
        $this->_stats = array(
            'total' => 0,
            'imported' => 0,
            'skipped' => 0,
            'services_created' => 0
        );
    }

/**
 * Parseia o arquivo CSV
 *
 * @param string $filepath Caminho do arquivo temporário
 * @return array Array com 'success', 'rows' ou 'message'
 */
    protected function _parseCsv($filepath) {
        $rows = array();
        $handle = fopen($filepath, 'r');

        if ($handle === false) {
            return array(
                'success' => false,
                'message' => __('Não foi possível abrir o arquivo.')
            );
        }

        // Detecta delimitador
        $firstLine = fgets($handle);
        rewind($handle);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        $lineNumber = 0;

        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            $lineNumber++;

            // Primeira linha: header
            if ($lineNumber === 1) {
                $headerValidation = $this->_validateHeader($data);
                if (!$headerValidation['valid']) {
                    fclose($handle);
                    return array(
                        'success' => false,
                        'message' => $headerValidation['message']
                    );
                }
                continue;
            }

            // Ignora linhas vazias
            if (count($data) === 1 && empty(trim($data[0]))) {
                continue;
            }

            // Valida e sanitiza a linha
            $rowValidation = $this->_rowValidator->validate($data, $lineNumber);
            if (!$rowValidation['valid']) {
                $this->_errors[] = array(
                    'line' => $lineNumber,
                    'message' => $rowValidation['message']
                );
                continue;
            }

            $rows[] = array(
                'line' => $lineNumber,
                'data' => $rowValidation['sanitized']
            );
        }

        fclose($handle);

        return array(
            'success' => true,
            'rows' => $rows
        );
    }

/**
 * Valida o header do CSV
 *
 * @param array $header Colunas do header
 * @return array Array com 'valid' e opcionalmente 'message'
 */
    protected function _validateHeader($header) {
        $normalized = array_map(function($col) {
            return strtolower(trim($col));
        }, $header);

        $required = array('name', 'email', 'phone');
        foreach ($required as $col) {
            if (!in_array($col, $normalized)) {
                return array(
                    'valid' => false,
                    'message' => __('Coluna obrigatória ausente: %s', $col)
                );
            }
        }

        return array('valid' => true);
    }

/**
 * Agrupa linhas por email para suportar múltiplos serviços
 *
 * @param array $rows Linhas parseadas
 * @return array Prestadores agrupados com seus serviços
 */
    protected function _groupByEmail($rows) {
        $grouped = array();

        foreach ($rows as $row) {
            $data = $row['data'];
            $email = $this->_rowValidator->decodeField($data[1]);

            if (!isset($grouped[$email])) {
                $grouped[$email] = array(
                    'lines' => array($row['line']),
                    'name' => $this->_rowValidator->decodeField($data[0]),
                    'email' => $email,
                    'phone' => $this->_rowValidator->decodeField($data[2]),
                    'services' => array()
                );
            } else {
                $grouped[$email]['lines'][] = $row['line'];
            }

            // Adiciona serviço se presente
            $serviceName = isset($data[3]) ? $this->_rowValidator->decodeField($data[3]) : '';
            $serviceValue = isset($data[4]) ? $data[4] : '';

            if (!empty($serviceName)) {
                $grouped[$email]['services'][] = array(
                    'name' => $serviceName,
                    'value' => $this->_rowValidator->sanitizeMonetaryValue($serviceValue)
                );
            }
        }

        return array_values($grouped);
    }

/**
 * Processa os prestadores agrupados
 *
 * @param array $providers Prestadores agrupados
 * @return array Resultado da importação
 */
    protected function _processProviders($providers) {
        $dataSource = $this->_Provider->getDataSource();
        $dataSource->begin();

        try {
            foreach ($providers as $provider) {
                $result = $this->_saveProvider($provider);

                if ($result['success']) {
                    $this->_stats['imported']++;
                } else {
                    $this->_stats['skipped']++;
                    $lines = implode(', ', $provider['lines']);
                    $this->_errors[] = array(
                        'line' => $lines,
                        'message' => $result['message']
                    );
                }
            }

            // Rollback se maioria falhou
            if ($this->_stats['skipped'] > ($this->_stats['total'] / 2) && $this->_stats['total'] > 1) {
                throw new Exception(__('Muitos erros encontrados. Importação cancelada.'));
            }

            $dataSource->commit();

            // Invalida cache do dashboard após importação bem-sucedida
            $this->_dashboardService->invalidateCache();

            return $this->_buildSuccessResponse();

        } catch (Exception $e) {
            $dataSource->rollback();
            return $this->_buildErrorResponse($e->getMessage());
        }
    }

/**
 * Salva um prestador com seus serviços
 *
 * @param array $provider Dados do prestador agrupado
 * @return array Array com 'success' e opcionalmente 'message'
 */
    protected function _saveProvider($provider) {
        // Verifica se email já existe no banco
        $existing = $this->_Provider->find('first', array(
            'conditions' => array('Provider.email' => $provider['email']),
            'fields' => array('Provider.id')
        ));

        if ($existing) {
            return array(
                'success' => false,
                'message' => __('Email já cadastrado: %s', $provider['email'])
            );
        }

        // Prepara dados do prestador
        $providerData = array(
            'Provider' => array(
                'name' => $provider['name'],
                'email' => $provider['email'],
                'phone' => $provider['phone']
            )
        );

        // Adiciona serviços
        if (!empty($provider['services'])) {
            $providerServices = array();

            foreach ($provider['services'] as $service) {
                $serviceRecord = $this->_findOrCreateService($service['name']);

                if ($serviceRecord) {
                    $providerServices[] = array(
                        'service_id' => $serviceRecord['Service']['id'],
                        'value' => $service['value']
                    );
                }
            }

            if (!empty($providerServices)) {
                $providerData['ProviderService'] = $providerServices;
            }
        }

        // Salva
        $this->_Provider->create();
        if ($this->_Provider->saveAssociated($providerData, array('deep' => true))) {
            return array('success' => true);
        }

        $errors = $this->_Provider->validationErrors;
        $errorMsg = !empty($errors) ? implode(', ', array_values(reset($errors))) : __('Erro ao salvar');

        return array('success' => false, 'message' => $errorMsg);
    }

/**
 * Busca ou cria um serviço pelo nome
 *
 * @param string $serviceName Nome do serviço
 * @return array|false Serviço encontrado/criado
 */
    protected function _findOrCreateService($serviceName) {
        $service = $this->_Service->find('first', array(
            'conditions' => array(
                'LOWER(Service.name)' => strtolower($serviceName)
            )
        ));

        if ($service) {
            return $service;
        }

        // Cria novo serviço
        $this->_Service->create();
        if ($this->_Service->save(array('Service' => array('name' => $serviceName)))) {
            $this->_stats['services_created']++;
            return $this->_Service->find('first', array(
                'conditions' => array('Service.id' => $this->_Service->id)
            ));
        }

        return false;
    }

/**
 * Constrói resposta de sucesso
 *
 * @return array
 */
    protected function _buildSuccessResponse() {
        $message = __('%d prestador(es) importado(s) com sucesso.', $this->_stats['imported']);

        if ($this->_stats['services_created'] > 0) {
            $message .= ' ' . __('%d serviço(s) criado(s) automaticamente.', $this->_stats['services_created']);
        }

        if ($this->_stats['skipped'] > 0) {
            $message .= ' ' . __('%d linha(s) ignorada(s) por erros.', $this->_stats['skipped']);
        }

        return array(
            'success' => true,
            'message' => $message,
            'stats' => $this->_stats,
            'errors' => $this->_errors
        );
    }

/**
 * Constrói resposta de erro
 *
 * @param string $message Mensagem de erro principal
 * @return array
 */
    protected function _buildErrorResponse($message) {
        return array(
            'success' => false,
            'message' => $message,
            'stats' => $this->_stats,
            'errors' => $this->_errors
        );
    }
}
