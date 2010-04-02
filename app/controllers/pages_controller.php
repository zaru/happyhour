<?php

class PagesController extends AppController {
    var $uses = array('ShopList');
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
        
        //店舗情報
        $this->paginate = array(
                "conditions" => array('ShopList.del_flg' => 0),
                "fields" => '*',
                "limit" => 20,
                "order" => 'ShopList.city ASC,ShopList.category1 ASC,ShopList.category2 ASC',
              );
        $shop = $this->paginate('ShopList');
        $this->set('shop',$shop);
        $this->set('condition','');
    }
    
}

