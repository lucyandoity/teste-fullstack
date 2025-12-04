<?php
App::uses('AppController', 'Controller');

class PrestadoresController extends AppController
{

    public $components = array('Paginator', 'Flash');

    public function index()
    {
        $this->Paginator->settings = array(
            'contain' => array('Servico'),
            'limit' => 10
        );
        $prestadores = $this->Paginator->paginate();

        $coresAvatar = array('#8B5CF6', '#EC4899', '#10B981', '#F59E0B', '#3B82F6', '#EF4444');

        foreach ($prestadores as $key => $prestador) {
            $nome = explode(' ', $prestador['Prestador']['nome']);
            $iniciais = strtoupper(substr($nome[0], 0, 1) . (count($nome) > 1 ? substr(end($nome), 0, 1) : ''));
            $corIndex = $prestador['Prestador']['id'] % count($coresAvatar);
            $prestadores[$key]['Prestador']['iniciais'] = $iniciais;
            $prestadores[$key]['Prestador']['avatar_color'] = $coresAvatar[$corIndex];

            if (!empty($prestador['Prestador']['foto'])) {
                $prestadores[$key]['Prestador']['foto_url'] = Router::url('/img/uploads/prestadores/' . $prestador['Prestador']['foto'], true);
            } else {
                $prestadores[$key]['Prestador']['foto_url'] = null;
            }
        }
        $this->set('prestadores', $prestadores);
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->request->data = $this->_handleFileUpload($this->request->data);

            if (isset($this->request->data['Prestador']['valor_servico'])) {
                $this->request->data['Prestador']['valor_servico'] = str_replace(',', '.', $this->request->data['Prestador']['valor_servico']);
            }

            $this->Prestador->create();
            if ($this->Prestador->save($this->request->data)) {
                $this->Flash->success(__('O prestador foi salvo com sucesso.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('O prestador não pôde ser salvo. Por favor, tente novamente.'));
            }
        }
        $servicos = $this->Prestador->Servico->find('list');
        $this->set(compact('servicos'));
    }

    public function edit($id = null)
    {
        if (!$this->Prestador->exists($id)) {
            throw new NotFoundException(__('Prestador inválido'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data = $this->_handleFileUpload($this->request->data);

            if (isset($this->request->data['Prestador']['valor_servico'])) {
                $this->request->data['Prestador']['valor_servico'] = str_replace(',', '.', $this->request->data['Prestador']['valor_servico']);
            }

            if ($this->Prestador->save($this->request->data)) {
                $this->Flash->success(__('O prestador foi salvo com sucesso.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('O prestador não pôde ser salvo. Por favor, tente novamente.'));
            }
        } else {
            $this->request->data = $this->Prestador->findById($id);

            if (!empty($this->request->data['Prestador']['valor_servico'])) {
                $this->request->data['Prestador']['valor_servico'] = number_format($this->request->data['Prestador']['valor_servico'], 2, ',', '');
            }
            if (!empty($this->request->data['Prestador']['foto'])) {
                $this->request->data['Prestador']['foto_url'] = Router::url('/img/uploads/prestadores/' . $this->request->data['Prestador']['foto'], true);
            }
        }
        $servicos = $this->Prestador->Servico->find('list');
        $this->set(compact('servicos'));
    }

    public function delete($id = null)
    {
        if (!$this->Prestador->exists($id)) {
            throw new NotFoundException(__('Prestador inválido'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Prestador->delete($id)) {
            $this->Flash->success(__('O prestador foi excluído.'));
        } else {
            $this->Flash->error(__('O prestador não pôde ser excluído. Por favor, tente novamente.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    private function _handleFileUpload($data)
    {
        if (isset($data['Prestador']['foto']['error']) && $data['Prestador']['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = APP . 'webroot' . DS . 'img' . DS . 'uploads' . DS . 'prestadores' . DS;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }
            $filename = uniqid() . '-' . basename($data['Prestador']['foto']['name']);
            $targetPath = $uploadDir . $filename;
            if (move_uploaded_file($data['Prestador']['foto']['tmp_name'], $targetPath)) {
                $data['Prestador']['foto'] = $filename;
            } else {
                $data['Prestador']['foto'] = null;
            }
        } else {
            unset($data['Prestador']['foto']);
        }
        return $data;
    }
}
