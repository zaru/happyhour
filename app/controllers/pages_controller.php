<?php

class PagesController extends AppController {
    var $uses = array();
    var $components = array();
    var $helpers = array('Ajax','Javascript','spaginator','Cache');
    
    function beforeFilter() {
    }
    
    function beforeRender(){
    }
    
    /*
     * インデックス
     */
    function display(){
        
        $this->pageTitle = '';
        $this->set('pankuzu','');
        
    }
    
}

