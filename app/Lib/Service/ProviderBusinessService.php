<?php
/**
 * Provider Business Service
 *
 * Camada de serviço responsável pela lógica de negócios relacionada a Prestadores.
 * Separa as regras de negócio da camada de apresentação (Controller).
 *
 * @package app.Lib.Service
 */

App::uses('AppModel', 'Model');

class ProviderBusinessService {

/**
 * Instância do Model Provider
 *
 * @var Provider
 */
    protected $_Provider;

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
 * Diretório de uploads
 *
 * @var string
 */
    protected $_uploadDir = 'uploads';

/**
 * Construtor
 */
    public function __construct() {
        $this->_Provider = ClassRegistry::init('Provider');
    }

/**
 * Lista prestadores com paginação e busca
 *
 * @param array $queryParams Parâmetros de busca
 * @return array Configurações para o Paginator
 */
    public function buildSearchConditions($queryParams = array()) {
        $conditions = array();

        if (!empty($queryParams['search'])) {
            $search = $queryParams['search'];
            $conditions['OR'] = array(
                'Provider.name LIKE' => '%' . $search . '%',
                'Provider.email LIKE' => '%' . $search . '%',
                'Provider.phone LIKE' => '%' . $search . '%'
            );
        }

        return array(
            'conditions' => $conditions,
            'contain' => array(
                'ProviderService' => array(
                    'Service' => array(
                        'fields' => array('Service.id', 'Service.name')
                    )
                )
            ),
            'limit' => 10,
            'order' => array('Provider.created' => 'desc')
        );
    }

/**
 * Busca um prestador pelo ID
 *
 * @param int $id ID do prestador
 * @return array|false Dados do prestador ou false se não encontrado
 * @throws NotFoundException
 */
    public function findById($id) {
        if (!$this->_Provider->exists($id)) {
            throw new NotFoundException(__('Prestador não encontrado'));
        }

        return $this->_Provider->find('first', array(
            'conditions' => array('Provider.id' => $id),
            'contain' => array(
                'ProviderService' => array(
                    'Service'
                )
            )
        ));
    }

/**
 * Cria um novo prestador
 *
 * @param array $data Dados do prestador
 * @return array Resultado da operação com status e mensagem
 */
    public function create($data) {
    $dataSource = $this->_Provider->getDataSource();
    $dataSource->begin();

    try {
        $this->_Provider->create();

        $uploadResult = $this->_processPhotoUpload($data);

        if (!$uploadResult['success']) {
            return array(
                'success' => false,
                'message' => isset($uploadResult['error']) ? $uploadResult['error'] : __('Erro no upload.')
            );
        }

        $data = $uploadResult['data'];

        if ($this->_Provider->saveAssociated($data, array('deep' => true))) {

            $dataSource->commit();

            return array(
                'success' => true,
                'message' => __('Prestador e serviços salvos com sucesso.'),
                'id' => $this->_Provider->id
            );
        } else {
            throw new Exception(__('Erro de validação'));
        }

    } catch (Exception $e) {
        $dataSource->rollback();

        return array(
            'success' => false,
            'message' => __('Não foi possível salvar. Verifique os campos destacados.'),
            'validationErrors' => $this->_Provider->validationErrors
        );
    }
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
        if (!$this->_Provider->exists($id)) {
            throw new NotFoundException(__('Prestador não encontrado'));
        }

        $dataSource = $this->_Provider->getDataSource();
        $dataSource->begin();

        try {
            $data['Provider']['id'] = $id;

            $uploadResult = $this->_processPhotoUpload($data);
            if (!$uploadResult['success'] && isset($uploadResult['error'])) {
                return array(
                    'success' => false,
                    'message' => $uploadResult['error']
                );
            }
            $data = $uploadResult['data'];

            // Remove vínculos antigos de ProviderService antes de salvar os novos
            $ProviderService = ClassRegistry::init('ProviderService');
            $ProviderService->deleteAll(array('ProviderService.provider_id' => $id), false);

            // Salva Provider + novos vínculos (ProviderService)
            if ($this->_Provider->saveAssociated($data, array('deep' => true))) {
                $dataSource->commit();
                return array(
                    'success' => true,
                    'message' => __('Prestador atualizado com sucesso.')
                );
            } else {
                throw new Exception(__('Erro de validação'));
            }

        } catch (Exception $e) {
            $dataSource->rollback();
            return array(
                'success' => false,
                'message' => __('Erro ao atualizar o prestador. Verifique os dados e tente novamente.'),
                'validationErrors' => $this->_Provider->validationErrors
            );
        }
    }

/**
 * Remove um prestador
 *
 * @param int $id ID do prestador
 * @return array Resultado da operação
 * @throws NotFoundException
 */
    public function delete($id) {
        if (!$this->_Provider->exists($id)) {
            throw new NotFoundException(__('Prestador não encontrado'));
        }

        // Busca a foto atual para remover após exclusão
        $provider = $this->_Provider->find('first', array(
            'conditions' => array('Provider.id' => $id),
            'fields' => array('Provider.photo')
        ));

        if ($this->_Provider->delete($id)) {
            if (!empty($provider['Provider']['photo'])) {
                $this->_removePhoto($provider['Provider']['photo']);
            }

            return array(
                'success' => true,
                'message' => __('Prestador excluído com sucesso.')
            );
        }

        return array(
            'success' => false,
            'message' => __('Não foi possível excluir o prestador. Tente novamente.')
        );
    }

/**
 * Processa o upload de foto do prestador
 *
 * @param array $data Dados do formulário
 * @return array Dados processados com resultado do upload
 */
    protected function _processPhotoUpload($data) {
		// 1. Verifica se existe arquivo enviado
		if (empty($data['Provider']['photo']['name'])) {
			unset($data['Provider']['photo']);
			return array('success' => true, 'data' => $data);
		}

		$file = $data['Provider']['photo'];
		$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		$allowed = array('jpg', 'jpeg', 'png', 'gif');

		// 2. Valida Extensão
		if (!in_array($ext, $allowed)) {
			return array('success' => false, 'error' => 'Formato de imagem inválido (use JPG ou PNG).');
		}

		// 3. Prepara Diretório
		$new_name = uniqid() . '.' . $ext;
		$path = WWW_ROOT . 'img' . DS . 'uploads';

		// Tenta criar pasta se não existir
		if (!file_exists($path)) {
			if (!@mkdir($path, 0777, true) && !is_dir($path)) {
				return array('success' => false, 'error' => 'Erro de permissão: Não foi possível criar a pasta de uploads.');
			}
		}

		// 4. Move o arquivo
		if (move_uploaded_file($file['tmp_name'], $path . DS . $new_name)) {
			$data['Provider']['photo'] = 'uploads/' . $new_name;
			return array('success' => true, 'data' => $data);
		} else {
			return array('success' => false, 'error' => 'Falha ao mover o arquivo. Verifique permissões da pasta img.');
		}
	}

/**
 * Valida o arquivo enviado
 *
 * @param array $file Dados do arquivo ($_FILES)
 * @return array Resultado da validação
 */
    protected function _validateUploadedFile($file) {
        // Verifica erros de upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
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
        if ($file['size'] > $this->_maxFileSize) {
            return array(
                'valid' => false,
                'message' => __('O arquivo é muito grande. Tamanho máximo: %s MB', $this->_maxFileSize / 1048576)
            );
        }

        // Valida se é realmente uma imagem
        $imageInfo = @getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return array(
                'valid' => false,
                'message' => __('O arquivo enviado não é uma imagem válida.')
            );
        }

        return array('valid' => true);
    }

/**
 * Gera um nome único para o arquivo
 *
 * @param string $extension Extensão do arquivo
 * @return string Nome único
 */
    protected function _generateUniqueFileName($extension) {
        return uniqid('provider_', true) . '.' . $extension;
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
            return mkdir($path, 0755, true);
        }
        return is_writable($path);
    }

/**
 * Remove uma foto do servidor
 *
 * @param string $photoPath Caminho relativo da foto
 * @return bool Sucesso na remoção
 */
    protected function _removePhoto($photoPath) {
        $fullPath = WWW_ROOT . 'img' . DS . str_replace('/', DS, $photoPath);
        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }
        return false;
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
