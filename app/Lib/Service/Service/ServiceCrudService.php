<?php
/**
 * Service CRUD Service
 *
 * Serviço responsável por operações CRUD de serviços.
 * Gerencia criação, atualização e exclusão.
 *
 * @package app.Lib.Service.Service
 */

App::uses('AppModel', 'Model');

class ServiceCrudService {

/**
 * Instância do Model Service
 *
 * @var Service
 */
    protected $_Service;

/**
 * Construtor
 */
    public function __construct() {
        $this->_Service = ClassRegistry::init('Service');
    }

/**
 * Cria um novo serviço
 *
 * @param array $data Dados do serviço
 * @return array Resultado da operação
 */
    public function create($data) {
        $this->_Service->create();

        if ($this->_Service->save($data)) {
            return array(
                'success' => true,
                'message' => __('Serviço salvo com sucesso.'),
                'id' => $this->_Service->id
            );
        }

        return array(
            'success' => false,
            'message' => __('Não foi possível salvar o serviço. Verifique os dados e tente novamente.'),
            'validationErrors' => $this->_Service->validationErrors
        );
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
        if (!$this->_Service->exists($id)) {
            throw new NotFoundException(__('Serviço não encontrado'));
        }

        $data['Service']['id'] = $id;

        if ($this->_Service->save($data)) {
            return array(
                'success' => true,
                'message' => __('Serviço atualizado com sucesso.')
            );
        }

        return array(
            'success' => false,
            'message' => __('Erro ao atualizar o serviço. Verifique os dados e tente novamente.'),
            'validationErrors' => $this->_Service->validationErrors
        );
    }

/**
 * Remove um serviço
 *
 * @param int $id ID do serviço
 * @return array Resultado da operação
 * @throws NotFoundException
 */
    public function delete($id) {
        if (!$this->_Service->exists($id)) {
            throw new NotFoundException(__('Serviço não encontrado'));
        }

        if ($this->_Service->delete($id)) {
            return array(
                'success' => true,
                'message' => __('Serviço excluído com sucesso.')
            );
        }

        return array(
            'success' => false,
            'message' => __('Não foi possível excluir o serviço. Tente novamente.')
        );
    }
}

