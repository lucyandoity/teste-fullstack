<?php

App::uses('AppController', 'Controller');

class ServiceProvidersController extends AppController {
    // Aqui ficam as configurações do controller :)
    public $layout = 'custom';
    public $uses = array('ServiceProvider', 'Service');
    public $components = array('Flash', 'Paginator');
    public $paginate = array(
        'limit' => 7,
        'order' => array(
            'ServiceProvider.first_name' => 'asc'
        )
    );

    public function index() {
        // Condições para busca
        $conditions = array();

        if (!empty($this->request->query['search'])) {
            $search = $this->request->query['search'];
            $conditions['OR'] = array(
                // Aqui poderiamos adicionar filtros adicionais, por exemplo, por serviço oferecido ou email
                'ServiceProvider.first_name LIKE' => '%' . $search . '%',
                'ServiceProvider.last_name LIKE' => '%' . $search . '%',
                'CONCAT(ServiceProvider.first_name, " ", ServiceProvider.last_name) LIKE' => '%' . $search . '%'
            );
        }

        $this->Paginator->settings = array_merge($this->paginate, array(
            'conditions' => $conditions
        ));
        
        $data = $this->Paginator->paginate('ServiceProvider');
        $this->set('serviceProviders', $data);
        $this->set('search', isset($this->request->query['search']) ? $this->request->query['search'] : '');
    }

    public function create() {
        // o If aqui está servindo para não processar o form quando a request é um GET (Rota do formulário)
        if ($this->request->is('post')) {
            $this->ServiceProvider->create();
            
            // Upload de Foto
            if (!empty($this->request->data['ServiceProvider']['photo']['name'])) {
                $photo = $this->request->data['ServiceProvider']['photo'];
                $filename = $this->request->data['ServiceProvider']['photo']['name'];
                $uploadDir = WWW_ROOT . 'img' . DS . 'uploads' . DS;
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                if (move_uploaded_file($photo['tmp_name'], $uploadDir . $filename)) {
                    $this->request->data['ServiceProvider']['photo'] = 'uploads/' . $filename;
                // Esse if é para caso o upload falhe, a foto também ficará como null, mas o usuário poderá editar depois
                } else {
                    $this->request->data['ServiceProvider']['photo'] = null;
                }
            // Se nenhum arquivo foi enviado a foto fica como null (Avatarzinho bonitinho com as letras iniciais do nome/sobrenome)
            } else {
                $this->request->data['ServiceProvider']['photo'] = null;
            }
            // Aqui é para preparação dos dados para validação
            $this->ServiceProvider->set($this->request->data);
            
            if ($this->ServiceProvider->validates()) {
                if ($this->ServiceProvider->save($this->request->data)) {
                    $this->Flash->notification('Prestador cadastrado com sucesso!');
                    return $this->redirect(array('action' => 'index'));
                }
            }
        }

        // Popular as options do dropdown de serviços 
        $serviceSuggestions = $this->Service->find('list', array('fields' => array('name', 'name')));
        $this->set(compact('serviceSuggestions'));
    }

    // A lógica aqui é diferente pois alterei o view para um AJAX via jquery, então essa função só retorna o JSON para popular o modal!
    public function view($id = null) {
        // Acha o prestador pelo ID
        $serviceProvider = $this->ServiceProvider->findById($id);

        // Não precisamos de casos de erro por id inválido, pois o botão de ver detalhes só aparece se o prestador existir, então abaixo está a lógica para retornar o JSON via AJAX
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->response->type('application/json');
            return json_encode($serviceProvider);
        }
    }

    public function edit($id = null) {
        $this->ServiceProvider->id = $id;
        // Verifica se o prestador existe, se não, redireciona para a index(lista de prestadores) com notificação de erro 
        if (!$this->ServiceProvider->exists()) {
            $this->Flash->notification('Prestador não encontrado!', array('params' => array('class' => 'error')));
            return $this->redirect(array('action' => 'index'));
        }
        // o If aqui está servindo para não processar o form quando a request é um GET (Rota do formulário) ;^)
        if ($this->request->is(array('post', 'put'))) {
            // Upload de Foto
            if (!empty($this->request->data['ServiceProvider']['photo']['name'])) {
                $photo = $this->request->data['ServiceProvider']['photo'];
                $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
                $filename = uniqid('photo_') . '.' . $extension;
                $uploadDir = WWW_ROOT . 'img' . DS . 'uploads' . DS;
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                if (move_uploaded_file($photo['tmp_name'], $uploadDir . $filename)) {
                    $this->request->data['ServiceProvider']['photo'] = 'uploads/' . $filename;
                // Se o upload falhar, removemos a chave 'photo' para manter a foto atual
                } else {
                    unset($this->request->data['ServiceProvider']['photo']); 
                }
            // Se nenhum arquivo foi enviado, removemos a chave 'photo' para manter a foto atual
            } else {
                unset($this->request->data['ServiceProvider']['photo']); 
            }

            if ($this->ServiceProvider->save($this->request->data)) {
                $this->Flash->notification('Prestador atualizado com sucesso!');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->notification('Erro ao atualizar. Verifique os dados.', array('params' => array('class' => 'error')));
        // Se não for post ou put, preenche os inputs com os dados atuais do prestador
        } else {
            $this->request->data = $this->ServiceProvider->findById($id);
        }
        
        // Popular as options do dropdown de serviços 
        $serviceSuggestions = $this->Service->find('list', array('fields' => array('name', 'name')));
        $this->set(compact('serviceSuggestions'));
    }

    public function delete($id = null) {
        $this->ServiceProvider->id = $id;
        if (!$this->ServiceProvider->exists()) {
            throw new NotFoundException('Prestador não encontrado');
        }
        if ($this->ServiceProvider->delete()) {
            $this->Flash->notification('Prestador removido com sucesso!');
        } else {
            $this->Flash->notification('Erro ao remover prestador.', array('params' => array('class' => 'error')));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function import() {
        if ($this->request->is('post')) {
            $file = $this->request->data['ServiceProvider']['csv_file'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $filePath = $file['tmp_name'];
                $handle = fopen($filePath, 'r');
                if ($handle !== false) {
                    $header = fgetcsv($handle, 1000, ',');
                    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                        $record = array_combine($header, $data);
                        $this->ServiceProvider->create();
                        if (!$this->ServiceProvider->save($record)) {
                            $this->Flash->notification('Erro ao importar alguns registros.', array('params' => array('class' => 'error')));
                        }
                    }
                    fclose($handle);
                    $this->Flash->notification('Importação concluída com sucesso!');
                    return $this->redirect(array('action' => 'index'));
                } else {
                    $this->Flash->notification('Erro ao abrir o arquivo.', array('params' => array('class' => 'error')));
                }
            } else {
                $this->Flash->notification('Erro no upload do arquivo.', array('params' => array('class' => 'error')));
            }
        }
    }
}