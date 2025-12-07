<?php
/**
 * CSV Row Validator
 *
 * Responsável exclusivamente pela validação e sanitização de dados de linha do CSV.
 * Implementa validações de negócio e proteção XSS/SQL Injection.
 *
 * @package app.Lib.Service
 */
class CsvRowValidator {

/**
 * Colunas obrigatórias (índices)
 *
 * @var array
 */
    protected $_requiredFields = array(
        0 => 'name',
        1 => 'email',
        2 => 'phone'
    );

/**
 * Tamanho máximo de campos de texto
 *
 * @var int
 */
    protected $_maxFieldLength = 255;

/**
 * Padrões proibidos em dados (prevenção XSS e Code Injection)
 *
 * @var array
 */
    protected $_forbiddenPatterns = array(
        // XSS
        '/<script/i',
        '/javascript:/i',
        '/on\w+\s*=/i',       // onclick=, onload=, etc
        '/data:/i',
        '/vbscript:/i',
        // Code Injection
        '/<\?php/i',          // PHP
        '/<\?=/i',            // PHP short echo
        '/<\?/i',             // PHP short open (pode dar falso positivo)
        '/<%/i',              // ASP
        '/\${.*}/i',          // Template injection ${...}
    );

/**
 * Valida e sanitiza uma linha de dados
 *
 * @param array $data Dados da linha
 * @param int $lineNumber Número da linha para mensagens de erro
 * @return array Array com 'valid', 'message' e 'sanitized'
 */
    public function validate($data, $lineNumber = 0) {
        // Verifica número mínimo de colunas
        if (count($data) < 3) {
            return array(
                'valid' => false,
                'message' => __('Número insuficiente de colunas')
            );
        }

        // Sanitiza todos os campos
        $sanitized = $this->_sanitizeRow($data);

        // Valida campos obrigatórios
        $requiredValidation = $this->_validateRequired($sanitized);
        if (!$requiredValidation['valid']) {
            return $requiredValidation;
        }

        // Valida email
        $emailValidation = $this->_validateEmail($sanitized[1]);
        if (!$emailValidation['valid']) {
            return $emailValidation;
        }

        // Valida tamanho dos campos
        $lengthValidation = $this->_validateFieldLengths($sanitized);
        if (!$lengthValidation['valid']) {
            return $lengthValidation;
        }

        // Valida conteúdo malicioso
        $securityValidation = $this->_validateSecurity($sanitized);
        if (!$securityValidation['valid']) {
            return $securityValidation;
        }

        // Valida serviço: se service_name está preenchido, service_value é obrigatório
        $serviceName = isset($sanitized[3]) ? trim($sanitized[3]) : '';
        $serviceValue = isset($sanitized[4]) ? trim($sanitized[4]) : '';

        if (!empty($serviceName) && empty($serviceValue)) {
            return array(
                'valid' => false,
                'message' => __('Valor do serviço é obrigatório quando o nome do serviço está preenchido')
            );
        }

        // Valida valor monetário se presente
        if (!empty($serviceValue)) {
            $monetaryValidation = $this->_validateMonetaryValue($serviceValue);
            if (!$monetaryValidation['valid']) {
                return $monetaryValidation;
            }
        }

        return array(
            'valid' => true,
            'sanitized' => $sanitized
        );
    }

/**
 * Sanitiza toda a linha
 *
 * @param array $data Dados da linha
 * @return array Dados sanitizados
 */
    protected function _sanitizeRow($data) {
        $sanitized = array();

        foreach ($data as $index => $value) {
            $sanitized[$index] = $this->_sanitizeField($value, $index);
        }

        return $sanitized;
    }

/**
 * Sanitiza um campo individual
 *
 * @param string $value Valor do campo
 * @param int $index Índice da coluna
 * @return string Valor sanitizado
 */
    protected function _sanitizeField($value, $index) {
        $value = trim($value);

        // Remove caracteres de controle (exceto espaço)
        $value = preg_replace('/[\x00-\x1F\x7F]/', '', $value);

        // Converte entidades HTML
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        if ($index === 1) {
            $value = strtolower($value);
        }

        // Telefone: mantém apenas dígitos e caracteres permitidos
        if ($index === 2) {
            $value = preg_replace('/[^\d\s()\-+]/', '', $value);
        }

        return $value;
    }

/**
 * Valida campos obrigatórios
 *
 * @param array $data Dados sanitizados
 * @return array
 */
    protected function _validateRequired($data) {
        foreach ($this->_requiredFields as $index => $fieldName) {
            if (empty($data[$index])) {
                return array(
                    'valid' => false,
                    'message' => __('Campo obrigatório vazio: %s', $fieldName)
                );
            }
        }

        return array('valid' => true);
    }

/**
 * Valida formato de email
 *
 * @param string $email Email a validar
 * @return array
 */
    protected function _validateEmail($email) {
        // Decodifica para validação (foi codificado na sanitização)
        $decoded = html_entity_decode($email, ENT_QUOTES, 'UTF-8');

        if (!filter_var($decoded, FILTER_VALIDATE_EMAIL)) {
            return array(
                'valid' => false,
                'message' => __('Email inválido: %s', $email)
            );
        }

        return array('valid' => true);
    }

/**
 * Valida tamanho dos campos
 *
 * @param array $data Dados sanitizados
 * @return array
 */
    protected function _validateFieldLengths($data) {
        $fieldNames = array('name', 'email', 'phone', 'service_name', 'service_value');

        foreach ($data as $index => $value) {
            if (strlen($value) > $this->_maxFieldLength) {
                $fieldName = isset($fieldNames[$index]) ? $fieldNames[$index] : "coluna $index";
                return array(
                    'valid' => false,
                    'message' => __('Campo %s excede o limite de %d caracteres', $fieldName, $this->_maxFieldLength)
                );
            }
        }

        return array('valid' => true);
    }

/**
 * Valida conteúdo malicioso (XSS, injection patterns)
 *
 * @param array $data Dados sanitizados
 * @return array
 */
    protected function _validateSecurity($data) {
        foreach ($data as $value) {
            $decoded = html_entity_decode($value, ENT_QUOTES, 'UTF-8');

            foreach ($this->_forbiddenPatterns as $pattern) {
                if (preg_match($pattern, $decoded)) {
                    return array(
                        'valid' => false,
                        'message' => __('Conteúdo não permitido detectado')
                    );
                }
            }
        }

        return array('valid' => true);
    }

/**
 * Valida valor monetário
 *
 * @param string $value Valor a validar
 * @return array
 */
    protected function _validateMonetaryValue($value) {
        $decoded = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
        $cleaned = preg_replace('/[^\d,.]/', '', $decoded);

        if (empty($cleaned) || !preg_match('/^\d{1,10}([.,]\d{1,2})?$/', $cleaned)) {
            $cleaned = str_replace('.', '', $decoded);
            $cleaned = str_replace(',', '.', $cleaned);

            if (!is_numeric($cleaned)) {
                return array(
                    'valid' => false,
                    'message' => __('Valor monetário inválido: %s', $value)
                );
            }
        }

        return array('valid' => true);
    }

/**
 * Sanitiza valor monetário para formato numérico
 *
 * @param string $value Valor a sanitizar
 * @return float Valor numérico
 */
    public function sanitizeMonetaryValue($value) {
        if (empty($value)) {
            return 0.00;
        }

        $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');

        // Remove caracteres não numéricos exceto vírgula e ponto
        $value = preg_replace('/[^\d,.]/', '', $value);

        // Detecta formato brasileiro (1.234,56) vs americano (1,234.56)
        $lastComma = strrpos($value, ',');
        $lastDot = strrpos($value, '.');

        if ($lastComma !== false && $lastDot !== false) {
            // Tem ambos: o último é o separador decimal
            if ($lastComma > $lastDot) {
                // Formato brasileiro: 1.234,56
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
            } else {
                // Formato americano: 1,234.56
                $value = str_replace(',', '', $value);
            }
        } elseif ($lastComma !== false) {
            // Só vírgula: assume decimal
            $value = str_replace(',', '.', $value);
        }

        return round((float)$value, 2);
    }

/**
 * Retorna campo decodificado (reverte htmlspecialchars)
 *
 * @param string $value Valor sanitizado
 * @return string Valor decodificado
 */
    public function decodeField($value) {
        return html_entity_decode($value, ENT_QUOTES, 'UTF-8');
    }
}
