<?php

namespace MakvilleStorage\Controller;

use Cake\Core\Configure;
use App\Controller\AppController as BaseController;

class AppController extends BaseController {
    
    public function initialize() {
        parent::initialize();
        $this->loadComponent('MakvilleStorage.Storage');
        $this->viewBuilder()->setLayout(Configure::read('makville-mailer-layout', 'admin'));
    }
}
