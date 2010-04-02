<?php
class StdComponent extends Object
{
    
    var $modelClass = NULL;
    
    function startup(&$controller){
        $this->controller =& $controller;    	
    }

    function _reserveData($id,$year='',$month=''){
        
        if($year == '' && $month == ''){
            list($year,$month) = explode('-',date('Y-m'));
            $next = date('Ym', strtotime("+1 month",mktime(0, 0, 0, $month, 1, $year)));
            $condition = array(
                'conditions' => array(
                    'OR' => array(
                        'date_format(reserve_start,"%Y%m") between ' . $year . $month . ' and ' . $next,
                        'date_format(reserve_end,"%Y%m") between ' . $year . $month . ' and ' . $next,
                    ),
                    'user_list_id' => $id,
                    'status' => 0,
                    'del_flg' => 0,
                ),
                'fields' => array(
                    'id',
                    'date_format(reserve_start,"%Y-%m-%d %H:%i")',
                    'date_format(reserve_end,"%Y-%m-%d %H:%i")',
                    'run_start',
                    'run_end',
                ),
                'order' => 'reserve_start ASC',
            );
            
        }else{
        
            $condition = array(
                'conditions' => array(
                    'OR' => array(
                        'date_format(reserve_start,"%Y%m")' => $year . $month,
                        'date_format(reserve_end,"%Y%m")' => $year . $month,
                    ),
                    'user_list_id' => $id,
                    'status' => 0,
                    'del_flg' => 0,
                ),
                'fields' => array(
                    'id',
                    'date_format(reserve_start,"%Y-%m-%d %H:%i")',
                    'date_format(reserve_end,"%Y-%m-%d %H:%i")',
                    'run_start',
                    'run_end',
                ),
                'order' => 'reserve_start ASC',
            );
        
        }
        return $this->controller->ReserveList->find('all',$condition);
    }
}