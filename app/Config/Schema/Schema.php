<?php

class AppSchema extends CakeSchema {

    public $services = array(
        'id' => array('type' => 'integer', 'null' => false, 'key' => 'primary'),
        'name' => array('type' => 'string', 'length' => 100, 'null' => false),
        'description' => array('type' => 'text', 'null' => true),
        'created' => array('type' => 'datetime', 'null' => true),
        'modified' => array('type' => 'datetime', 'null' => true),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
    );

    public $service_providers = array(
        'id' => array('type' => 'integer', 'null' => false, 'key' => 'primary'),
        'first_name' => array('type' => 'string', 'length' => 100, 'null' => false),
        'last_name' => array('type' => 'string', 'length' => 100, 'null' => false),
        'email' => array('type' => 'string', 'length' => 100, 'null' => false),
        'phone' => array('type' => 'string', 'length' => 15, 'null' => false),
        'photo' => array('type' => 'string', 'length' => 255, 'null' => true),
        'service' => array('type' => 'string', 'length' => 50, 'null' => false),
        'description' => array('type' => 'text', 'null' => true),
        'price' => array('type' => 'decimal', 'length' => '10,2', 'null' => false),
        'created' => array('type' => 'datetime', 'null' => true),
        'modified' => array('type' => 'datetime', 'null' => true),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
    );

    public function after($event = array()) {
        if (isset($event['create'])) {
            if ($event['create'] == 'services') {
                $this->_seedServices();
            }
        }
    }

    protected function _seedServices() {
        $Service = ClassRegistry::init('Service');
        
        $services = array(
            array('name' => 'Diagnóstico e Consultoria Inicial'),
            array('name' => 'Definição de Arquitetura e Stack Tecnológica'),
            array('name' => 'Prototipação de Telas (Wireframes / UI Básica)'),
            array('name' => 'Design e Implementação do Frontend'),
            array('name' => 'Desenvolvimento do Backend e APIs'),
            array('name' => 'Modelagem e Configuração do Banco de Dados'),
            array('name' => 'Sistema de Autenticação e Autorização'),

        );

        foreach ($services as $service) {
            $Service->create();
            $Service->save($service);
        }
    }

}