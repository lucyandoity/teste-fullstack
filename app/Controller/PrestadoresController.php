<?php
App::uses('AppController', 'Controller');
/**
 * Prestadores Controller
 *
 * @property Prestador $Prestador
 * @property PaginatorComponent $Paginator
 */
class PrestadoresController extends AppController {

    public $components = array('Paginator', 'Flash');

    /**
     * Prepara e exibe a lista de todos os prestadores.
     */
    public function index() {
        $this->Prestador->recursive = 1; // Busca apenas dados diretos do Prestador
        $prestadores = $this->Paginator->paginate();

        $coresAvatar = array('#8B5CF6', '#EC4899', '#10B981', '#F59E0B', '#3B82F6', '#EF4444');

        // Percorre a lista para adicionar dados extras para a view
        foreach ($prestadores as $key => $prestador) {
            // Gera as iniciais como um fallback caso não haja foto
            $nome = explode(' ', $prestador['Prestador']['nome']);
            $iniciais = strtoupper(substr($nome[0], 0, 1) . (count($nome) > 1 ? substr(end($nome), 0, 1) : ''));
            
            // Escolhe uma cor consistente para o avatar de iniciais
            $corIndex = $prestador['Prestador']['id'] % count($coresAvatar);

            $prestadores[$key]['Prestador']['iniciais'] = $iniciais;
            $prestadores[$key]['Prestador']['avatar_color'] = $coresAvatar[$corIndex];

            // Gera a URL completa e correta para a foto, se ela existir
            if (!empty($prestador['Prestador']['foto'])) {
                $prestadores[$key]['Prestador']['foto_url'] = Router::url('/img/uploads/prestadores/' . $prestador['Prestador']['foto'], true);
            } else {
                $prestadores[$key]['Prestador']['foto_url'] = null;
            }
        }
        $this->set('prestadores', $prestadores);
    }

    /**
     * Exibe os detalhes de um único prestador (não estamos usando, mas é bom ter).
     */
    public function view($id = null) {
        if (!$this->Prestador->exists($id)) {
            throw new NotFoundException(__('Prestador inválido'));
        }
        $options = array('conditions' => array('Prestador.' . $this->Prestador->primaryKey => $id));
        $this->set('prestador', $this->Prestador->find('first', $options));
    }

    /**
     * Adiciona um novo prestador.
     */
    public function add() {
        if ($this->request->is('post')) {
            // 1. Processa o upload da foto ANTES de qualquer outra coisa
            $this->request->data = $this->_handleFileUpload($this->request->data);

            // 2. Tenta salvar os dados no banco
            $this->Prestador->create();
            if ($this->Prestador->saveAll($this->request->data)) {
                $this->Flash->success(__('O prestador foi salvo com sucesso.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('O prestador não pôde ser salvo. Por favor, tente novamente.'));
            }
        }
        // Envia a lista de serviços para os checkboxes do formulário
        $servicos = $this->Prestador->Servico->find('list');
        $this->set(compact('servicos'));
    }

    /**
     * Edita um prestador existente.
     */
    public function edit($id = null) {
        if (!$this->Prestador->exists($id)) {
            throw new NotFoundException(__('Prestador inválido'));
        }
        if ($this->request->is(array('post', 'put'))) {
            // 1. Processa o upload de uma nova foto, se houver
            $this->request->data = $this->_handleFileUpload($this->request->data);
            
            // 2. Tenta salvar as alterações
            if ($this->Prestador->saveAll($this->request->data)) {
                $this->Flash->success(__('O prestador foi salvo com sucesso.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('O prestador não pôde ser salvo. Por favor, tente novamente.'));
            }
        } else {
            // Se não for POST/PUT, busca os dados do prestador para preencher o formulário
            $options = array('conditions' => array('Prestador.' . $this->Prestador->primaryKey => $id));
            $this->request->data = $this->Prestador->find('first', $options);

            // Prepara a URL da foto existente para o preview no formulário
            if (!empty($this->request->data['Prestador']['foto'])) {
                $this->request->data['Prestador']['foto_url'] = Router::url('/img/uploads/prestadores/' . $this->request->data['Prestador']['foto'], true);
            }
        }
        // Envia a lista de serviços para os checkboxes do formulário
        $servicos = $this->Prestador->Servico->find('list');
        $this->set(compact('servicos'));
    }

    /**
     * Deleta um prestador.
     */
    public function delete($id = null) {
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

    /**
     * Método privado e robusto para lidar com o upload da foto.
     * Esta é a função CORRETA e COMPLETA.
     */
    private function _handleFileUpload($data) {
        // Verifica se um arquivo foi enviado e se não há erros.
        if (isset($data['Prestador']['foto']['error']) && $data['Prestador']['foto']['error'] === UPLOAD_ERR_OK) {

            // Define o diretório de destino de forma segura e absoluta.
            $uploadDir = APP . 'webroot' . DS . 'img' . DS . 'uploads' . DS . 'prestadores' . DS;

            // Cria o diretório se ele não existir (fundamental).
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            // Gera um nome de arquivo único para evitar sobreposições e problemas de cache.
            $filename = uniqid() . '-' . basename($data['Prestador']['foto']['name']);
            $targetPath = $uploadDir . $filename;

            // Move o arquivo temporário para o destino final.
            if (move_uploaded_file($data['Prestador']['foto']['tmp_name'], $targetPath)) {
                // Sucesso! Atualiza o campo 'foto' com o novo nome do arquivo.
                $data['Prestador']['foto'] = $filename;
            } else {
                // Falha ao mover o arquivo, define o campo como nulo.
                $data['Prestador']['foto'] = null;
            }
        } else {
            // Se nenhum novo arquivo foi enviado, remove o campo 'foto' dos dados
            // para que a foto antiga no banco não seja apagada.
            unset($data['Prestador']['foto']);
        }
        return $data;
    }
}