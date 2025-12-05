<?php
App::uses('AppController', 'Controller');
App::uses('ProviderBusinessService', 'Lib/Service');

/**
 * Providers Controller
 *
 * Controlador responsável pela gestão de prestadores de serviço.
 * Delega a lógica de negócios para a camada de serviço (ProviderBusinessService).
 *
 * @property Provider $Provider
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 */
class ProvidersController extends AppController {

/**
 * Componentes utilizados
 *
 * @var array
 */
    public $components = array('Paginator', 'Flash');

/**
 * Instância do serviço de prestadores
 *
 * @var ProviderBusinessService
 */
    protected $_providerService;

/**
 * Callback executado antes de cada action
 *
 * @return void
 */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->_providerService = new ProviderBusinessService();
    }

/**
 * Lista todos os prestadores com busca e paginação
 *
 * Permite busca por nome, email, telefone ou serviço através do parâmetro 'search'.
 *
 * @return void
 */
    public function index() {
        // Mescla query params com named params (page vem como named param do CakePHP)
        $params = array_merge(
            $this->request->query,
            $this->request->params['named']
        );

        $result = $this->_providerService->listWithFilters($params);

        $this->request->params['paging']['Provider'] = $result['paging'];
        $this->set('providers', $result['providers']);
        $this->set('providersCount', $result['totalCount']);
        $this->set('search', $this->request->query('search'));
    }

/**
 * Exibe detalhes de um prestador
 *
 * @param string $id ID do prestador
 * @return void
 * @throws NotFoundException Quando o prestador não é encontrado
 */
    public function view($id = null) {
        $provider = $this->_providerService->findById($id);
        $this->set('provider', $provider);
    }

/**
 * Adiciona um novo prestador
 *
 * @return CakeResponse|void Redireciona para index em caso de sucesso
 */
    public function add() {
        if ($this->request->is('post')) {
            $result = $this->_providerService->create($this->request->data);

            if ($result['success']) {
                $this->Flash->success($result['message']);
                return $this->redirect(array('action' => 'index'));
            }

            $this->Flash->error($result['message']);

            // Define erros de validação para exibição no formulário
            if (!empty($result['validationErrors'])) {
                $this->Provider->validationErrors = $result['validationErrors'];
            }
        }

        // Carrega a lista de serviços do Catálogo para o dropdown (id => name)
        $this->loadModel('Service');
        $services = $this->Service->find('list', array('order' => 'Service.name ASC'));
        $this->set(compact('services'));
    }

/**
 * Edita um prestador existente
 *
 * @param string $id ID do prestador
 * @return CakeResponse|void Redireciona para index em caso de sucesso
 * @throws NotFoundException Quando o prestador não é encontrado
 */
    public function edit($id = null) {
        // Valida existência antes de qualquer operação
        $provider = $this->_providerService->findById($id);

        if ($this->request->is(array('post', 'put'))) {
            $result = $this->_providerService->update($id, $this->request->data);

            if ($result['success']) {
                $this->Flash->success($result['message']);
                return $this->redirect(array('action' => 'index'));
            }

            $this->Flash->error($result['message']);

            if (!empty($result['validationErrors'])) {
                $this->Provider->validationErrors = $result['validationErrors'];
            }
        } else {
            $this->request->data = $provider;
        }

        $this->loadModel('Service');
        $services = $this->Service->find('list', array('order' => 'Service.name ASC'));
        $this->set(compact('services'));
    }

/**
 * Remove um prestador
 *
 * @param string $id ID do prestador
 * @return CakeResponse Redireciona para index
 * @throws NotFoundException Quando o prestador não é encontrado
 * @throws MethodNotAllowedException Quando o método HTTP não é POST ou DELETE
 */
    public function delete($id = null) {
        $this->request->allowMethod(array('post', 'delete'));

        $result = $this->_providerService->delete($id);

        if ($result['success']) {
            $this->Flash->success($result['message']);
        } else {
            $this->Flash->error($result['message']);
        }

        return $this->redirect(array('action' => 'index'));
    }
}
