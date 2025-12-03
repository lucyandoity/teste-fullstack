<?php
App::uses('AppController', 'Controller');
/**
 * Services Controller
 *
 * @property Service $Service
 * @property PaginatorComponent $Paginator
 */
class ServicesController extends AppController {

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
		$this->Service->recursive = 0;
		$this->set('services', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Service->exists($id)) {
			throw new NotFoundException(__('Invalid service'));
		}
		$options = array('conditions' => array('Service.' . $this->Service->primaryKey => $id));
		$this->set('service', $this->Service->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Service->create();
			if ($this->Service->save($this->request->data)) {
				$this->Flash->success(__('The service has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The service could not be saved. Please, try again.'));
			}
		}
		$providers = $this->Service->Provider->find('list');
		$this->set(compact('providers'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Service->exists($id)) {
			throw new NotFoundException(__('Invalid service'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Service->save($this->request->data)) {
				$this->Flash->success(__('The service has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The service could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Service.' . $this->Service->primaryKey => $id));
			$this->request->data = $this->Service->find('first', $options);
		}
		$providers = $this->Service->Provider->find('list');
		$this->set(compact('providers'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Service->exists($id)) {
			throw new NotFoundException(__('Invalid service'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Service->delete($id)) {
			$this->Flash->success(__('The service has been deleted.'));
		} else {
			$this->Flash->error(__('The service could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
