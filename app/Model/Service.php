<?php

App::uses('AppModel', 'Model');

class Service extends AppModel {
    
    public $hasMany = array(
        'ServiceProvider' => array(
            'className' => 'ServiceProvider',
            'foreignKey' => 'service_id',
        )
    );

    public $validate = array(
        'name' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'message' => 'Nome do serviço é obrigatório'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 100),
                'message' => 'Nome do serviço não deve ultrapassar 100 caracteres'
            )
        )
    );
}