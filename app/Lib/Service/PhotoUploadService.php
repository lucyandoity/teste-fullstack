<?php
/**
 * Photo Upload Service
 *
 * Serviço responsável pelo upload e gerenciamento de fotos.
 * Reutilizável para qualquer entidade que precise de upload de imagens.
 *
 * @package app.Lib.Service
 */

class PhotoUploadService {

/**
 * Extensões de imagem permitidas para upload
 *
 * @var array
 */
    protected $_allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

/**
 * Tamanho máximo do arquivo em bytes (5MB)
 *
 * @var int
 */
    protected $_maxFileSize = 5242880;

/**
 * Diretório de uploads relativo a webroot/img
 *
 * @var string
 */
    protected $_uploadDir = 'uploads';

/**
 * Construtor
 *
 * @param array $options Opções de configuração (allowedExtensions, maxFileSize, uploadDir)
 */
    public function __construct($options = array()) {
        if (!empty($options['allowedExtensions'])) {
            $this->_allowedExtensions = $options['allowedExtensions'];
        }
        if (!empty($options['maxFileSize'])) {
            $this->_maxFileSize = $options['maxFileSize'];
        }
        if (!empty($options['uploadDir'])) {
            $this->_uploadDir = $options['uploadDir'];
        }
    }

/**
 * Processa o upload de uma foto
 *
 * @param array $file Dados do arquivo ($_FILES format)
 * @return array Resultado com 'success', 'path' ou 'error'
 */
    public function upload($file) {
        // Verifica se existe arquivo enviado
        if (empty($file['name'])) {
            return array('success' => true, 'path' => null);
        }

        // Valida o arquivo
        $validation = $this->validate($file);
        if (!$validation['valid']) {
            return array('success' => false, 'error' => $validation['message']);
        }

        // Gera nome único
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newName = $this->_generateUniqueFileName($ext);

        // Garante que o diretório existe
        $uploadPath = $this->_getUploadPath();
        if (!$this->_ensureUploadDirectory($uploadPath)) {
            return array(
                'success' => false,
                'error' => __('Erro de permissão: Não foi possível criar a pasta de uploads.')
            );
        }

        // Move o arquivo
        $fullPath = $uploadPath . DS . $newName;
        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            return array(
                'success' => true,
                'path' => $this->_uploadDir . '/' . $newName
            );
        }

        return array(
            'success' => false,
            'error' => __('Falha ao mover o arquivo. Verifique permissões da pasta.')
        );
    }

/**
 * Valida o arquivo enviado
 *
 * @param array $file Dados do arquivo ($_FILES)
 * @return array Resultado com 'valid' e 'message'
 */
    public function validate($file) {
        // Verifica erros de upload
        if (isset($file['error']) && $file['error'] !== UPLOAD_ERR_OK) {
            return array(
                'valid' => false,
                'message' => $this->_getUploadErrorMessage($file['error'])
            );
        }

        // Valida extensão
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->_allowedExtensions)) {
            return array(
                'valid' => false,
                'message' => __('Extensão de imagem inválida. Use: %s', implode(', ', $this->_allowedExtensions))
            );
        }

        // Valida tamanho
        if (isset($file['size']) && $file['size'] > $this->_maxFileSize) {
            return array(
                'valid' => false,
                'message' => __('O arquivo é muito grande. Tamanho máximo: %s MB', $this->_maxFileSize / 1048576)
            );
        }

        // Valida se é realmente uma imagem
        if (!empty($file['tmp_name'])) {
            $imageInfo = @getimagesize($file['tmp_name']);
            if ($imageInfo === false) {
                return array(
                    'valid' => false,
                    'message' => __('O arquivo enviado não é uma imagem válida.')
                );
            }
        }

        return array('valid' => true);
    }

/**
 * Remove uma foto do servidor
 *
 * @param string $photoPath Caminho relativo da foto (ex: 'uploads/abc123.jpg')
 * @return bool Sucesso na remoção
 */
    public function remove($photoPath) {
        if (empty($photoPath)) {
            return false;
        }

        $fullPath = WWW_ROOT . 'img' . DS . str_replace('/', DS, $photoPath);
        if (file_exists($fullPath) && is_file($fullPath)) {
            return @unlink($fullPath);
        }
        return false;
    }

/**
 * Gera um nome único para o arquivo
 *
 * @param string $extension Extensão do arquivo
 * @return string Nome único
 */
    protected function _generateUniqueFileName($extension) {
        return uniqid('photo_', true) . '.' . $extension;
    }

/**
 * Retorna o caminho completo do diretório de uploads
 *
 * @return string Caminho do diretório
 */
    protected function _getUploadPath() {
        return WWW_ROOT . 'img' . DS . $this->_uploadDir;
    }

/**
 * Garante que o diretório de uploads existe
 *
 * @param string $path Caminho do diretório
 * @return bool Sucesso na criação/verificação
 */
    protected function _ensureUploadDirectory($path) {
        if (!file_exists($path)) {
            return @mkdir($path, 0755, true);
        }
        return is_writable($path);
    }

/**
 * Retorna mensagem de erro de upload amigável
 *
 * @param int $errorCode Código de erro do PHP
 * @return string Mensagem de erro
 */
    protected function _getUploadErrorMessage($errorCode) {
        $errors = array(
            UPLOAD_ERR_INI_SIZE => __('O arquivo excede o tamanho máximo permitido pelo servidor.'),
            UPLOAD_ERR_FORM_SIZE => __('O arquivo excede o tamanho máximo permitido pelo formulário.'),
            UPLOAD_ERR_PARTIAL => __('O upload foi interrompido. Tente novamente.'),
            UPLOAD_ERR_NO_FILE => __('Nenhum arquivo foi enviado.'),
            UPLOAD_ERR_NO_TMP_DIR => __('Erro de configuração do servidor.'),
            UPLOAD_ERR_CANT_WRITE => __('Falha ao gravar arquivo no servidor.'),
            UPLOAD_ERR_EXTENSION => __('Upload bloqueado por extensão do PHP.')
        );

        return isset($errors[$errorCode])
            ? $errors[$errorCode]
            : __('Erro desconhecido no upload.');
    }
}
