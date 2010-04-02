<?php

class ShopController extends AppController {
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
    function happyhour($city = ''){
        
        $this->pageTitle = '';
        $this->set('pankuzu','');
        
        $cityLists = array('足立区','荒川区','板橋区','江戸川区','大田区','葛飾区','北区','江東区','品川区','渋谷区','新宿区','杉並区','墨田区','世田谷区','台東区','千代田区','中央区','豊島区','中野区','練馬区','文京区','港区','目黒区');
        $this->set('cityLists',$cityLists);
        
        //店舗情報
        $this->paginate = array(
                "conditions" => array('ShopList.del_flg' => 0,'ShopList.happy_flg' => 1),
                "fields" => '*',
                "limit" => 20,
                "order" => 'ShopList.city ASC,ShopList.category1 ASC,ShopList.category2 ASC',
              );
        if($city != ''){
            $this->paginate['conditions'][] = 'ShopList.address LIKE "%' . $city . '%"';
        }
        $shop = $this->paginate('ShopList');
        $this->set('shop',$shop);
        $this->set('condition','');
    }
    
}

