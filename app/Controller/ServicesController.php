<?php
App::uses('AppController', 'Controller');
App::uses('ServiceService', 'Lib/Service/Service');

/**
 * Services Controller
 *
 * Controlador responsável pela gestão de serviços oferecidos pelos prestadores.
 * Delega a lógica de negócios para a camada de serviço (ServiceService).
 *
 * @property Service $Service
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 */
class ServicesController extends AppController {

/**
 * Componentes utilizados
 *
 * @var array
 */
    public $components = array('Paginator', 'Flash');

/**
 * Instância do serviço de serviços
 *
 * @var ServiceService
 */
    protected $_serviceService;

/**
 * Callback executado antes de cada action
 *
 * @return void
 */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->_serviceService = new ServiceService();
    }

/**
 * Lista todos os serviços com busca e paginação
 *
 * Permite filtrar por prestador e buscar por nome ou descrição.
 *
 * @return void
 */
    public function index() {
        $this->Service->recursive = 0;

        $paginatorSettings = $this->_serviceService->buildSearchConditions(
            $this->request->query
        );

        $this->Paginator->settings = $paginatorSettings;
        $this->set('services', $this->Paginator->paginate());
        $this->set('search', $this->request->query('search'));
    }

/**
 * Exibe detalhes de um serviço
 *
 * @param string $id ID do serviço
 * @return void
 * @throws NotFoundException Quando o serviço não é encontrado
 */
    public function view($id = null) {
        $service = $this->_serviceService->findById($id);
        $this->set('service', $service);
    }

/**
 * Adiciona um novo serviço
 *
 * @return CakeResponse|void Redireciona para index em caso de sucesso
 */
    public function add() {
        if ($this->request->is('post')) {
            $result = $this->_serviceService->create($this->request->data);

            if ($result['success']) {
                $this->Flash->success($result['message']);
                return $this->redirect(array('action' => 'index'));
            }

            $this->Flash->error($result['message']);

            // Define erros de validação para exibição no formulário
            if (!empty($result['validationErrors'])) {
                $this->Service->validationErrors = $result['validationErrors'];
            }
        }
    }

/**
 * Edita um serviço existente
 *
 * @param string $id ID do serviço
 * @return CakeResponse|void Redireciona para index em caso de sucesso
 * @throws NotFoundException Quando o serviço não é encontrado
 */
    public function edit($id = null) {
        // Valida existência antes de qualquer operação
        $service = $this->_serviceService->findById($id);

        if ($this->request->is(array('post', 'put'))) {
            $result = $this->_serviceService->update($id, $this->request->data);

            if ($result['success']) {
                $this->Flash->success($result['message']);
                return $this->redirect(array('action' => 'index'));
            }

            $this->Flash->error($result['message']);

            if (!empty($result['validationErrors'])) {
                $this->Service->validationErrors = $result['validationErrors'];
            }
        } else {
            // Preenche o formulário com os dados atuais
            $this->request->data = $service;
        }
    }

/**
 * Remove um serviço
 *
 * @param string $id ID do serviço
 * @return CakeResponse Redireciona para index
 * @throws NotFoundException Quando o serviço não é encontrado
 * @throws MethodNotAllowedException Quando o método HTTP não é POST ou DELETE
 */
    public function delete($id = null) {
        $this->request->allowMethod(array('post', 'delete'));

        $result = $this->_serviceService->delete($id);

        if ($result['success']) {
            $this->Flash->success($result['message']);
        } else {
            $this->Flash->error($result['message']);
        }

        return $this->redirect(array('action' => 'index'));
    }
}
