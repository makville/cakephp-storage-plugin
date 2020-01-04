<?php

namespace MakvilleStorage\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController {
    
    public function initialize() {
        parent::initialize();
        $this->loadComponent('MakvilleStorage.Storage');
    }
}
