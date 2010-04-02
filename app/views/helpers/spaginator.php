<?php
/*
    ページネーションの表示件数を帰るヘルパー
*/
require_once "../../cake/libs/view/helpers/paginator.php";
class SpaginatorHelper extends PaginatorHelper
{
    function limit($limit = null,$spname = "件"){
        $model = $this->params['controller'];
        
        if(!is_numeric($limit)){
            $limit = 20;
        }
        
        if(!isset($this->params['paging'][$model]['defaults']['limit']) && $limit == 20){
            $this->params['paging'][$model]['defaults']['limit']  = 20;
        }
        if(!isset($this->params['named']['limit'])){
            $this->params['named']['limit'] = $this->params['paging'][$model]['defaults']['limit'];
        }
        
        if($this->params['named']['limit'] == $limit){
            return $limit.$spname;
        }
        
        return $this->link("$limit$spname",array("limit" => $limit));
    }
}