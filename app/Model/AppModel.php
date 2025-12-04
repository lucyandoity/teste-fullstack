<?php
/**
 * Application model for CakePHP.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * @package       app.Model
 */
class AppModel extends Model {

/**
 * Comportamentos padrão para todos os models
 *
 * @var array
 */
    public $actsAs = array('Containable');

/**
 * Recursive padrão desabilitado para melhor performance
 *
 * @var int
 */
    public $recursive = -1;
}
