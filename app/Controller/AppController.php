<?php
/**
 * Application level Controller
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * @package       app.Controller
 * @link          https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

/**
 * Componentes disponíveis em todos os controllers
 *
 * @var array
 */
    public $components = array(
        'Flash',
        'Session',
        'RequestHandler'
    );

/**
 * Helpers disponíveis em todas as views
 *
 * @var array
 */
    public $helpers = array(
        'Html',
        'Form',
        'Flash',
        'Session',
        'Paginator'
    );

/**
 * Callback executado antes de cada action
 *
 * @return void
 */
    public function beforeFilter() {
        parent::beforeFilter();

        // Headers de segurança
        $this->response->header(array(
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-XSS-Protection' => '1; mode=block'
        ));
    }
}
