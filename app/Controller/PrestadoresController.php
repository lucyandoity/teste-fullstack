<?php
App::uses('AppController', 'Controller');

class PrestadoresController extends AppController
{
  public $components = array('Paginator', 'Session', 'Flash');
  public $helpers = array('Html', 'Form', 'Session');
  public $uses = array('Prestador');

  public $paginate = array(
    'limit' => 10,
    'order' => array('Prestador.id' => 'desc')
  );

  public function index()
  {
    $this->Prestador->recursive = 1;
    $conditions = array();
    if (!empty($this->request->query['q'])) {
      $q = $this->request->query['q'];
      $conditions['OR'] = array(
        'Prestador.nome LIKE' => '%' . $q . '%',
        'Prestador.email LIKE' => '%' . $q . '%'
      );
    }

    $this->Paginator->settings = array(
      'limit' => 10,
      'order' => array('Prestador.id' => 'desc'),
      'conditions' => $conditions
    );
    $this->set('prestadores', $this->Paginator->paginate('Prestador'));

    // Bonus Stats
    $this->loadModel('Servico');
    $totalPrestadores = $this->Prestador->find('count');
    $totalServicos = $this->Servico->find('count');
    $avgData = $this->Servico->find('all', array('fields' => array('AVG(Servico.valor) as media')));
    $mediaValor = isset($avgData[0][0]['media']) ? $avgData[0][0]['media'] : 0;

    $this->set(compact('totalPrestadores', 'totalServicos', 'mediaValor'));
  }

  public function add($id = null)
  {
    if ($this->request->is('post') || $this->request->is('put')) {
      if ($id) {
        $this->Prestador->id = $id;
      } else {
        $this->Prestador->create();
      }

      if (!empty($this->request->data['Prestador']['foto']['name']) && $this->request->data['Prestador']['foto']['error'] === UPLOAD_ERR_OK) {
        $file = $this->request->data['Prestador']['foto'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array(strtolower($ext), $allowed)) {
          $newName = uniqid() . '.' . $ext;
          $uploadPath = WWW_ROOT . 'img' . DS . 'uploads';
          if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
          }
          if (move_uploaded_file($file['tmp_name'], $uploadPath . DS . $newName)) {
            $this->request->data['Prestador']['foto'] = 'uploads/' . $newName;
          } else {
            $this->Flash->error(__('Erro ao fazer upload da foto.'));
            unset($this->request->data['Prestador']['foto']);
          }
        } else {
          $this->Flash->error(__('Formato de imagem inválido.'));
          unset($this->request->data['Prestador']['foto']);
        }
      } else {
        if (empty($this->request->data['Prestador']['foto']['name'])) {
          unset($this->request->data['Prestador']['foto']);
        }
      }

      if ($this->Prestador->saveAssociated($this->request->data)) {
        $this->Flash->success(__('O prestador foi salvo.'));
        return $this->redirect(array('action' => 'index'));
      } else {
        $this->Flash->error(__('O prestador não pôde ser salvo. Tente novamente.'));
      }
    } else {
      if ($id) {
        $this->request->data = $this->Prestador->findById($id);
        if (!$this->request->data) {
          throw new NotFoundException(__('Prestador inválido'));
        }
      }
    }

    $this->loadModel('Servico');
    $servicosDisponiveis = $this->Servico->find('all', array(
      'fields' => array('Servico.nome', 'Servico.descricao'),
      'group' => array('Servico.nome'),
      'order' => array('Servico.nome' => 'ASC')
    ));
    $this->set('servicosDisponiveis', $servicosDisponiveis);
  }

  public function delete($id = null)
  {
    $this->Prestador->id = $id;
    if (!$this->Prestador->exists()) {
      throw new NotFoundException(__('Prestador inválido'));
    }
    if ($this->Prestador->delete()) {
      $this->Flash->success(__('Prestador excluído.'));
    } else {
      $this->Flash->error(__('Prestador não pôde ser excluído.'));
    }
    return $this->redirect(array('action' => 'index'));
  }

  public function export_csv()
  {
    $this->autoRender = false;
    $this->response->type('csv');
    $this->response->download('prestadores_export_' . date('Y-m-d') . '.csv');

    $prestadores = $this->Prestador->find('all', array('recursive' => 1));

    $handle = fopen('php://output', 'w');


    $header = array('ID', 'Nome', 'Email', 'Telefone', 'Servicos');
    fputcsv($handle, $header);

    foreach ($prestadores as $p) {
      $servicos = array();
      if (!empty($p['Servico'])) {
        foreach ($p['Servico'] as $s) {
          $servicos[] = $s['nome'] . ' (' . number_format($s['valor'], 2) . ')';
        }
      }
      $row = array(
        $p['Prestador']['id'],
        $p['Prestador']['nome'],
        $p['Prestador']['email'],
        $p['Prestador']['telefone'],
        implode('; ', $servicos)
      );
      fputcsv($handle, $row);
    }
    fclose($handle);
  }
}
