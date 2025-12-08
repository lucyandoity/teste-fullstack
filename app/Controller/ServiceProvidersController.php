<?php

App::uses('AppController', 'Controller');
App::uses('ConnectionManager', 'Model');

class ServiceProvidersController extends AppController {
    // Aqui ficam as configurações do controller :)
    public $layout = 'custom';
    public $uses = array('ServiceProvider', 'Service');
    public $components = array('Flash', 'Paginator');
    public $paginate = array(
        'limit' => 7,
        'order' => array(
            'ServiceProvider.created' => 'desc'
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
                'ServiceProvider.email LIKE' => '%' . $search . '%',
                'ServiceProvider.service LIKE' => '%' . $search . '%',
                'ServiceProvider.phone LIKE' => '%' . $search . '%',
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
        if ($this->request->is('post')) {
            $this->ServiceProvider->create();
            
            // Upload de Foto
            if (!empty($this->request->data['ServiceProvider']['photo']['name'])) {
                $photo = $this->request->data['ServiceProvider']['photo'];

                // Verificação de tipo de arquivo
                $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
                if (!in_array(strtolower($extension), array('jpg', 'jpeg', 'png'))) {
                    $this->Flash->notification('Por favor, envie um arquivo de foto(JPG, JPEG, PNG) válido.', array('params' => array('class' => 'error')));
                    return $this->redirect(array('action' => 'create'));
                }

                // Verificação de tamanho máximo 5MB
                if ($photo['error'] === UPLOAD_ERR_OK) {
                    $photosize = filesize($photo['tmp_name']);
                    if ($photosize > 5 * 1024 * 1024) {
                        $this->Flash->notification('A foto é muito grande. O tamanho máximo permitido é 5MB.', array('params' => array('class' => 'error')));
                        return $this->redirect(array('action' => 'create'));
                    }

                    $filename = uniqid('photo_') . '.' . $extension;
                    $uploadDir = WWW_ROOT . 'img' . DS . 'uploads' . DS;
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $targetPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($photo['tmp_name'], $targetPath)) {
                        $this->request->data['ServiceProvider']['photo'] = 'uploads/' . $filename;
                    } else {
                        $this->request->data['ServiceProvider']['photo'] = null;
                    }
                } else {
                    $this->request->data['ServiceProvider']['photo'] = null;
                }
            } else {
                $this->request->data['ServiceProvider']['photo'] = null;
            }
            
            $this->ServiceProvider->set($this->request->data);
            
            if ($this->ServiceProvider->validates()) {
                if ($this->ServiceProvider->save($this->request->data)) {
                    $this->Flash->notification('Prestador cadastrado com sucesso!');
                    return $this->redirect(array('action' => 'index'));
                }
            }
        }

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
        
        if ($this->request->is(array('post', 'put'))) {
            // Upload de Foto
            if (!empty($this->request->data['ServiceProvider']['photo']['name'])) {
                $photo = $this->request->data['ServiceProvider']['photo'];

                $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
                if (!in_array(strtolower($extension), array('jpg', 'jpeg', 'png'))) {
                    $this->Flash->notification('Por favor, envie um arquivo de foto(JPG, JPEG, PNG) válido.', array('params' => array('class' => 'error')));
                    return $this->redirect(array('action' => 'edit', $id));
                }

                if ($photo['error'] === UPLOAD_ERR_OK) {
                    $photosize = filesize($photo['tmp_name']);
                    if ($photosize > 5 * 1024 * 1024) {
                        $this->Flash->notification('A foto é muito grande. O tamanho máximo permitido é 5MB.', array('params' => array('class' => 'error')));
                        return $this->redirect(array('action' => 'edit', $id));
                    }

                    $filename = uniqid('photo_') . '.' . $extension;
                    $uploadDir = WWW_ROOT . 'img' . DS . 'uploads' . DS;
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $targetPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($photo['tmp_name'], $targetPath)) {
                        $this->request->data['ServiceProvider']['photo'] = 'uploads/' . $filename;
                    } else {
                        unset($this->request->data['ServiceProvider']['photo']); 
                    }
                } else {
                    unset($this->request->data['ServiceProvider']['photo']); 
                }
            } else {
                unset($this->request->data['ServiceProvider']['photo']); 
            }

            if ($this->ServiceProvider->save($this->request->data)) {
                $this->Flash->notification('Prestador atualizado com sucesso!');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->notification('Erro ao atualizar. Verifique os dados.', array('params' => array('class' => 'error')));
        } else {
            $this->request->data = $this->ServiceProvider->findById($id);
        }
        
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

    // Na função de import decidi por fazer um LOAD DATA INFILE(BULK INSERT), que é a forma mais performática para importar grandes volumes de dados
    // (testei com 10k de linhas e foi instantâneo).Sendo bastante simples de implementar também, ele evita loops desnecessários no PHP(otimizando) e vários inserts individuais
    // não precisando também de libs extras somente para isso
    public function import() {
        if ($this->request->is('post')) {
            
            if (!empty($this->request->data['ServiceProvider']['csv_file']['tmp_name'])) {
                $file = $this->request->data['ServiceProvider']['csv_file'];
                
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                if (strtolower($extension) !== 'csv') {
                    $this->Flash->modalnotification('Por favor, envie um arquivo CSV válido.', array('params' => array('class' => 'error')));
                    return $this->redirect(array('action' => 'index'));
                }

                if ($file['error'] === UPLOAD_ERR_OK) {
                    $fileSize = filesize($file['tmp_name']);
                    if ($fileSize > 25 * 1024 * 1024) {
                        $this->Flash->modalnotification('O arquivo é muito grande. O tamanho máximo permitido é 25MB.', array('params' => array('class' => 'error')));
                        return $this->redirect(array('action' => 'index'));
                    }

                    try {
                        $db = ConnectionManager::getDataSource('default');
                        
                        // Usar caminho absoluto do arquivo temporário
                        $tmpPath = str_replace('\\', '/', $file['tmp_name']);
                        
                        $bulkInsertQuery = "LOAD DATA LOCAL INFILE '" . $tmpPath . "' 
                            INTO TABLE service_providers
                            FIELDS TERMINATED BY ','
                            ENCLOSED BY '\"'
                            LINES TERMINATED BY '\\n'
                            IGNORE 1 ROWS
                            (first_name, last_name, email, phone, service, description, price)";

                        $db->rawQuery($bulkInsertQuery);
                        
                        $this->Flash->modalnotification('Lista enviada com sucesso!');
                    } catch (Exception $e) {
                        $this->Flash->modalnotification('Erro ao importar: ' . $e->getMessage(), array('params' => array('class' => 'error')));
                    }
                } else {
                    $this->Flash->modalnotification('Erro no upload do arquivo.', array('params' => array('class' => 'error')));
                }
            } else {
                $this->Flash->modalnotification('Nenhum arquivo selecionado.', array('params' => array('class' => 'error')));
            }
        }
        
        return $this->redirect(array('action' => 'index'));
    }
}