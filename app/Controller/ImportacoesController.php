<?php
App::uses('AppController', 'Controller');
require_once(ROOT . DS . 'vendors' . DS . 'autoload.php');
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportacoesController extends AppController
{
  public $uses = array('Prestador', 'Servico');
  public $helpers = array('Html', 'Form', 'Session');
  public $components = array('Session', 'Flash');

  public function index()
  {
    if ($this->request->is('post')) {
      if (!empty($this->request->data['Importacao']['arquivo']['name'])) {
        $filename = $this->request->data['Importacao']['arquivo']['tmp_name'];

        if ($this->request->data['Importacao']['arquivo']['size'] > 25 * 1024 * 1024) {
          $this->Flash->error('O arquivo é muito grande. O limite máximo é 25MB.');
          return;
        }

        $ext = substr(strtolower(strrchr($this->request->data['Importacao']['arquivo']['name'], '.')), 1);
        if (!in_array($ext, array('csv', 'xls', 'xlsx'))) {
          $this->Flash->error('Tipo de arquivo não permitido. Apenas arquivos CSV, XLS e XLSX são aceitos.');
          return;
        }

        $count = 0;

        if ($ext == 'csv') {
          $handle = fopen($filename, "r");
          $header = fgetcsv($handle);
          while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $this->_processRow($row, $count);
          }
          fclose($handle);
        } else {
          try {
            $spreadsheet = IOFactory::load($filename);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            array_shift($rows);

            foreach ($rows as $row) {
              $this->_processRow($row, $count);
            }
          } catch (Exception $e) {
            $this->Flash->error('Erro ao processar arquivo Excel: ' . $e->getMessage());
            return;
          }
        }


        // Return JSON response for AJAX
        $this->autoRender = false;
        $this->response->type('json');
        echo json_encode(['success' => true, 'count' => $count]);
        return;
      } else {
        $this->Flash->error('Selecione um arquivo.');
      }
    }
  }

  private function _processRow($row, &$count)
  {
    if (count($row) < 6)
      return;

    $data = array(
      'Prestador' => array(
        'nome' => $row[0],
        'email' => $row[1],
        'telefone' => $row[2]
      ),
      'Servico' => array(
        array(
          'nome' => $row[3],
          'descricao' => $row[4],
          'valor' => $row[5]
        )
      )
    );

    $this->Prestador->create();
    if ($this->Prestador->saveAssociated($data)) {
      $count++;
    }
  }
}
