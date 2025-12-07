<?php
App::uses('AppController', 'Controller');
/**
 * Servicos Controller
 *
 * @property Servico $Servico
 * @property PaginatorComponent $Paginator
 */
class ServicosController extends AppController
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
	public function index()
	{
		$this->Servico->recursive = 0;
		$this->set('servicos', $this->Paginator->paginate());
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
		if (!$this->Servico->exists($id)) {
			throw new NotFoundException(__('Invalid servico'));
		}
		$options = array('conditions' => array('Servico.' . $this->Servico->primaryKey => $id));
		$this->set('servico', $this->Servico->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		if ($this->request->is('post')) {
			$this->Servico->create();
			if ($this->Servico->save($this->request->data)) {
				return $this->redirect(array('action' => 'index'));
			}
		}
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
		if (!$this->Servico->exists($id)) {
			throw new NotFoundException(__('Invalid servico'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Servico->save($this->request->data)) {
	
				return $this->redirect(array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('Servico.' . $this->Servico->primaryKey => $id));
			$this->request->data = $this->Servico->find('first', $options);
		}
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
		if (!$this->Servico->exists($id)) {
			throw new NotFoundException(__('Invalid servico'));
		}
		$this->request->allowMethod('post', 'delete');
	
		return $this->redirect(array('action' => 'index'));
	}

	public function add_ajax()
	{
		$this->autoRender = false; // Garante que nenhuma view seja renderizada
		$this->response->type('json'); // Define o tipo de conteúdo da resposta para JSON

		$response = array('status' => 'error', 'message' => 'Requisição inválida.');

		if ($this->request->is('post')) {
			$this->Servico->create();

			$dataToSave = array('Servico' => array('nome' => $this->request->data['nome']));

			if ($this->Servico->save($dataToSave)) {
				// Sucesso!
				$response = array(
					'status' => 'success',
					'data' => array(
						'id' => $this->Servico->id,
						'nome' => $this->request->data['nome']
					)
				);
			} else {
				// Falha na validação
				$errors = $this->Servico->validationErrors;
				$errorMessage = 'Não foi possível salvar o serviço.';
				if (!empty($errors['nome'])) {
					$errorMessage = $errors['nome'][0];
				}

				// Define um status HTTP de erro para a resposta
				$this->response->statusCode(400);
				$response = array(
					'status' => 'error',
					'message' => $errorMessage
				);
			}
		}
		$this->response->body(json_encode($response));
		return $this->response;
	}
}
