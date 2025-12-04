<?php
App::uses('AppController', 'Controller');
/**
 * Prestadores Controller
 *
 * @property Prestador $Prestador
 * @property PaginatorComponent $Paginator
 */
class PrestadoresController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator');

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		// Configura o Paginator para buscar os dados
		$this->Paginator->settings = array(
			'limit' => 10, // 10 prestadores por página
			'order' => array('Prestador.nome' => 'asc')
		);
		$this->Prestador->recursive = 1; // Traz os dados associados (Serviços)
		
		// Executa a busca paginada
		$prestadores = $this->Paginator->paginate('Prestador');
		
		// Envia os dados para a View
		$this->set('prestadores', $prestadores);
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null)
	{
		if (!$this->Prestador->exists($id)) {
			throw new NotFoundException(__('Invalid prestador'));
		}
		$options = array('conditions' => array('Prestador.' . $this->Prestador->primaryKey => $id));
		$this->set('prestador', $this->Prestador->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		// Bloco 1: Executa APENAS quando o formulário é enviado (POST)
		if ($this->request->is('post')) {
			$this->Prestador->create();
			if ($this->Prestador->save($this->request->data)) {
				$this->Flash->success(__('The prestador has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The prestador could not be saved. Please, try again.'));
			}
		}

		$servicos = $this->Prestador->Servico->find('list');

		$this->set(compact('servicos'));
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null)
	{
		if (!$this->Prestador->exists($id)) {
			throw new NotFoundException(__('Invalid prestador'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Prestador->save($this->request->data)) {
				$this->Flash->success(__('The prestador has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The prestador could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Prestador.' . $this->Prestador->primaryKey => $id));
			$this->request->data = $this->Prestador->find('first', $options);
		}
		$servicos = $this->Prestador->Servico->find('list');
		$this->set(compact('servicos'));
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null)
	{
		if (!$this->Prestador->exists($id)) {
			throw new NotFoundException(__('Invalid prestador'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Prestador->delete($id)) {
			$this->Flash->success(__('The prestador has been deleted.'));
		} else {
			$this->Flash->error(__('The prestador could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
