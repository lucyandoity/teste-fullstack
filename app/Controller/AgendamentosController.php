<?php
App::uses('AppController', 'Controller');
/**
 * Agendamentos Controller
 *
 * @property Agendamento $Agendamento
 * @property PaginatorComponent $Paginator
 */
class AgendamentosController extends AppController {

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
		$this->Agendamento->recursive = 0;
		$this->set('agendamentos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Agendamento->exists($id)) {
			throw new NotFoundException(__('Invalid agendamento'));
		}
		$options = array('conditions' => array('Agendamento.' . $this->Agendamento->primaryKey => $id));
		$this->set('agendamento', $this->Agendamento->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Agendamento->create();
			if ($this->Agendamento->save($this->request->data)) {
				$this->Flash->success(__('The agendamento has been saved.'));
				return $this->redirect(array('action' => 'index'));
			}
		}
		$prestadores = $this->Agendamento->Prestador->find('list');
		$servicos = $this->Agendamento->Servico->find('list');

		$optionsStatus = array(
			'Agendado' => 'Agendado',
			'Confirmado' => 'Confirmado',
			'Concluído' => 'Concluído',
			'Cancelado' => 'Cancelado'
		);
		$this->set(compact('prestadores', 'servicos', 'optionsStatus'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Agendamento->exists($id)) {
			throw new NotFoundException(__('Invalid agendamento'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Agendamento->save($this->request->data)) {
				$this->Flash->success(__('The agendamento has been saved.'));
				return $this->redirect(array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('Agendamento.' . $this->Agendamento->primaryKey => $id));
			$this->request->data = $this->Agendamento->find('first', $options);
			$dataHoraDoBanco = $this->request->data['Agendamento']['data_hora_inicio'];
			if (!empty($dataHoraDoBanco)) {
				$this->request->data['Agendamento']['data_hora_inicio'] = date('d/m/Y H:i', strtotime($dataHoraDoBanco));
			}
		}
		$prestadores = $this->Agendamento->Prestador->find('list');
		$servicos = $this->Agendamento->Servico->find('list');
		
		$optionsStatus = array(
			'Agendado' => 'Agendado',
			'Confirmado' => 'Confirmado',
			'Concluído' => 'Concluído',
			'Cancelado' => 'Cancelado'
		);

		$this->set(compact('prestadores', 'servicos', 'optionsStatus'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Agendamento->exists($id)) {
			throw new NotFoundException(__('Invalid agendamento'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Agendamento->delete($id)) {
			$this->Flash->success(__(''));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
