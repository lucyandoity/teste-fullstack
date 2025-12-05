<?php
/**
 * Provider CRUD Service
 *
 * Serviço responsável por operações CRUD de prestadores.
 * Gerencia criação, atualização e exclusão.
 *
 * @package app.Lib.Service
 */

App::uses('AppModel', 'Model');
App::uses('PhotoUploadService', 'Lib/Service');

class ProviderCrudService {

/**
 * Instância do Model Provider
 *
 * @var Provider
 */
    protected $_Provider;

/**
 * Serviço de upload de fotos
 *
 * @var PhotoUploadService
 */
    protected $_photoService;

/**
 * Construtor
 */
    public function __construct() {
        $this->_Provider = ClassRegistry::init('Provider');
        $this->_photoService = new PhotoUploadService(array(
            'uploadDir' => 'uploads'
        ));
    }

/**
 * Cria um novo prestador
 *
 * @param array $data Dados do prestador
 * @return array Resultado com 'success', 'message', 'id' ou 'validationErrors'
 */
    public function create($data) {
        $dataSource = $this->_Provider->getDataSource();
        $dataSource->begin();

        try {
            $this->_Provider->create();

            // Concatena nome completo
            $data = $this->_processFullName($data);

            // Processa upload da foto
            $photoResult = $this->_processPhoto($data);
            if (!$photoResult['success']) {
                return $photoResult;
            }
            $data = $photoResult['data'];

            // Salva com associações
            if ($this->_Provider->saveAssociated($data, array('deep' => true))) {
                $dataSource->commit();
                return array(
                    'success' => true,
                    'message' => __('Prestador e serviços salvos com sucesso.'),
                    'id' => $this->_Provider->id
                );
            }

            throw new Exception(__('Erro de validação'));

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

            // Concatena nome completo
            $data = $this->_processFullName($data);

            // Processa upload da foto
            $photoResult = $this->_processPhoto($data);
            if (!$photoResult['success']) {
                return $photoResult;
            }
            $data = $photoResult['data'];

            // Remove vínculos antigos de serviços
            $ProviderService = ClassRegistry::init('ProviderService');
            $ProviderService->deleteAll(
                array('ProviderService.provider_id' => $id),
                false
            );

            // Salva com novas associações
            if ($this->_Provider->saveAssociated($data, array('deep' => true))) {
                $dataSource->commit();
                return array(
                    'success' => true,
                    'message' => __('Prestador atualizado com sucesso.')
                );
            }

            throw new Exception(__('Erro de validação'));

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

        // Busca foto atual para remover após exclusão
        $provider = $this->_Provider->find('first', array(
            'conditions' => array('Provider.id' => $id),
            'fields' => array('Provider.photo')
        ));

        if ($this->_Provider->delete($id)) {
            // Remove foto do servidor
            if (!empty($provider['Provider']['photo'])) {
                $this->_photoService->remove($provider['Provider']['photo']);
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
 * Concatena primeiro e último nome em nome completo
 *
 * @param array $data Dados do formulário
 * @return array Dados com nome completo processado
 */
    protected function _processFullName($data) {
        if (isset($data['Provider']['first_name']) && isset($data['Provider']['last_name'])) {
            $firstName = trim($data['Provider']['first_name']);
            $lastName = trim($data['Provider']['last_name']);
            $data['Provider']['name'] = $firstName . ' ' . $lastName;
        }
        return $data;
    }

/**
 * Processa upload de foto do prestador
 *
 * @param array $data Dados do formulário
 * @return array Resultado com 'success', 'data' ou 'message'
 */
    protected function _processPhoto($data) {
        if (empty($data['Provider']['photo']['name'])) {
            unset($data['Provider']['photo']);
            return array('success' => true, 'data' => $data);
        }

        $result = $this->_photoService->upload($data['Provider']['photo']);

        if ($result['success']) {
            if ($result['path']) {
                $data['Provider']['photo'] = $result['path'];
            } else {
                unset($data['Provider']['photo']);
            }
            return array('success' => true, 'data' => $data);
        }

        return array(
            'success' => false,
            'message' => $result['error']
        );
    }
}
