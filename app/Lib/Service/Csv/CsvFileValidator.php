<?php
/**
 * CSV File Validator
 *
 * Responsável exclusivamente pela validação de segurança do arquivo CSV.
 * Implementa validações para prevenir upload de arquivos maliciosos.
 *
 * @package app.Lib.Service
 */
class CsvFileValidator {

/**
 * Tamanho máximo do arquivo em bytes (25MB - reduzido por segurança)
 *
 * @var int
 */
    protected $_maxFileSize = 26214400;

/**
 * Número máximo de linhas permitidas
 *
 * @var int
 */
    protected $_maxLines = 1000;

/**
 * Extensões permitidas
 *
 * @var array
 */
    protected $_allowedExtensions = array('csv');

/**
 * MIME types permitidos
 *
 * @var array
 */
    protected $_allowedMimeTypes = array(
        'text/csv',
        'text/plain',
        'application/csv',
        'application/vnd.ms-excel',
        'text/comma-separated-values'
    );

/**
 * Padrões de magic bytes que indicam tipo de arquivo incorreto
 * (arquivo disfarçado de CSV)
 *
 * @var array
 */
    protected $_fileMagicPatterns = array(
        '/^MZ/',              // Windows executável (PE)
        '/^\x7fELF/',         // Linux executável (ELF)
        '/^PK\x03\x04/',      // ZIP/Office/JAR
        '/^%PDF/',            // PDF
        '/^\xD0\xCF\x11\xE0/', // OLE (doc, xls, ppt antigos)
        '/^RIFF/',            // AVI, WAV
        '/^\xFF\xD8\xFF/',    // JPEG
        '/^\x89PNG/',         // PNG
        '/^GIF8/',            // GIF
        '/\x00/',             // Null bytes (binário, não texto)
    );

/**
 * Valida o arquivo de upload
 *
 * @param array $file Dados do arquivo ($_FILES)
 * @return array Array com 'valid', 'message' e opcionalmente 'lineCount'
 */
    public function validate($file) {
        $basicValidation = $this->_validateUpload($file);
        if (!$basicValidation['valid']) {
            return $basicValidation;
        }

        $extValidation = $this->_validateExtension($file['name']);
        if (!$extValidation['valid']) {
            return $extValidation;
        }

        $sizeValidation = $this->_validateSize($file['size']);
        if (!$sizeValidation['valid']) {
            return $sizeValidation;
        }

        $mimeValidation = $this->_validateMimeType($file['tmp_name']);
        if (!$mimeValidation['valid']) {
            return $mimeValidation;
        }

        $contentValidation = $this->_validateContent($file['tmp_name']);
        if (!$contentValidation['valid']) {
            return $contentValidation;
        }

        $lineValidation = $this->_validateLineCount($file['tmp_name']);
        if (!$lineValidation['valid']) {
            return $lineValidation;
        }

        return array(
            'valid' => true,
            'lineCount' => $lineValidation['count']
        );
    }

/**
 * Valida erros de upload
 *
 * @param array $file Dados do arquivo
 * @return array
 */
    protected function _validateUpload($file) {
        if (empty($file['name']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return array(
                'valid' => false,
                'message' => __('Nenhum arquivo foi enviado.')
            );
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return array(
                'valid' => false,
                'message' => $this->_getUploadErrorMessage($file['error'])
            );
        }

        return array('valid' => true);
    }

/**
 * Valida extensão do arquivo
 *
 * @param string $filename Nome do arquivo
 * @return array
 */
    protected function _validateExtension($filename) {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $this->_allowedExtensions)) {
            return array(
                'valid' => false,
                'message' => __('Formato de arquivo inválido. Use apenas CSV.')
            );
        }

        return array('valid' => true);
    }

/**
 * Valida tamanho do arquivo
 *
 * @param int $size Tamanho em bytes
 * @return array
 */
    protected function _validateSize($size) {
        if ($size > $this->_maxFileSize) {
            $maxMB = round($this->_maxFileSize / 1048576, 1);
            return array(
                'valid' => false,
                'message' => __('O arquivo excede o tamanho máximo de %sMB.', $maxMB)
            );
        }

        return array('valid' => true);
    }

/**
 * Valida MIME type real do arquivo
 *
 * @param string $filepath Caminho do arquivo temporário
 * @return array
 */
    protected function _validateMimeType($filepath) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filepath);
        finfo_close($finfo);

        if (!in_array($mimeType, $this->_allowedMimeTypes)) {
            return array(
                'valid' => false,
                'message' => __('O arquivo não é um CSV válido (tipo detectado: %s).', $mimeType)
            );
        }

        return array('valid' => true);
    }

/**
 * Valida conteúdo do arquivo por padrões maliciosos
 *
 * @param string $filepath Caminho do arquivo temporário
 * @return array
 */
    protected function _validateContent($filepath) {
        $handle = fopen($filepath, 'rb');
        if ($handle === false) {
            return array(
                'valid' => false,
                'message' => __('Não foi possível ler o arquivo.')
            );
        }

        $content = fread($handle, 8192);
        fclose($handle);

        foreach ($this->_fileMagicPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return array(
                    'valid' => false,
                    'message' => __('O arquivo não parece ser um CSV válido (formato binário detectado).')
                );
            }
        }

        return array('valid' => true);
    }

/**
 * Valida número de linhas
 *
 * @param string $filepath Caminho do arquivo temporário
 * @return array
 */
    protected function _validateLineCount($filepath) {
        $lineCount = 0;
        $handle = fopen($filepath, 'r');

        if ($handle === false) {
            return array(
                'valid' => false,
                'message' => __('Não foi possível ler o arquivo.')
            );
        }

        while (fgets($handle) !== false) {
            $lineCount++;

            if ($lineCount > $this->_maxLines) {
                fclose($handle);
                return array(
                    'valid' => false,
                    'message' => __('O arquivo excede o limite de %d linhas.', $this->_maxLines)
                );
            }
        }

        fclose($handle);

        return array(
            'valid' => true,
            'count' => $lineCount
        );
    }

/**
 * Retorna mensagem de erro de upload
 *
 * @param int $errorCode Código de erro
 * @return string Mensagem
 */
    protected function _getUploadErrorMessage($errorCode) {
        $errors = array(
            UPLOAD_ERR_INI_SIZE => __('O arquivo excede o tamanho máximo permitido.'),
            UPLOAD_ERR_FORM_SIZE => __('O arquivo excede o tamanho máximo do formulário.'),
            UPLOAD_ERR_PARTIAL => __('Upload incompleto. Tente novamente.'),
            UPLOAD_ERR_NO_FILE => __('Nenhum arquivo enviado.'),
            UPLOAD_ERR_NO_TMP_DIR => __('Erro de configuração do servidor.'),
            UPLOAD_ERR_CANT_WRITE => __('Falha ao gravar arquivo.'),
            UPLOAD_ERR_EXTENSION => __('Upload bloqueado.')
        );

        return isset($errors[$errorCode])
            ? $errors[$errorCode]
            : __('Erro desconhecido no upload.');
    }

/**
 * Getter para max file size
 *
 * @return int
 */
    public function getMaxFileSize() {
        return $this->_maxFileSize;
    }

/**
 * Getter para max lines
 *
 * @return int
 */
    public function getMaxLines() {
        return $this->_maxLines;
    }
}
