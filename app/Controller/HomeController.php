<?php
App::uses('AppController', 'Controller');
App::uses('DashboardService', 'Lib/Service');

class HomeController extends AppController {

    protected $_dashboardService;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->_dashboardService = new DashboardService();
    }

    public function index() {
        $metrics = $this->_dashboardService->getMetrics();
        $this->set('metrics', $metrics);
    }
}
