<?php

class CalendarHelper extends AppHelper{

	var $helpers = array('Html', 'Ajax');
    
    
    /*
     * カレンダー出力
     */
    function make($year,$month,$day = null){
    
        if($day == ''){
            if($year == date('Y') && $month == date('m')){
                $day = date('d');
            }else{
                $day = 1;
            }
        }
        
        $nowDate = date('Ymd');
    
    
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
        $week = array('日','月','火','水','木','金','土');
        $label = '';
        foreach($week as $val){
            $label .= $this->Html->tag('li', $val, array('class' => 'day'));
        }
        
        $thisWeek = date('w', mktime(0,0,0,$month,1,$year));
        $lastWeek = date('w', mktime(0,0,0,$month,$days,$year));
        $list = '';
        for($i=0;$i<$thisWeek;$i++){
            $list .= $this->Html->tag('li', '', array());
        }
        for($i=1;$i<=$days;$i++){
            $k = str_pad($i,2,'0',STR_PAD_LEFT);
            if($i == $day){
                $list .= $this->Html->tag('li', $i, array('class' => 'focus'));
            }else{
                if($year . $month . $k < $nowDate){
                    $link = $i;
                }else{
                    $link = $this->Html->link($i, '/reserve/index/' . $year . '-' . $month . '-' . $k . '_07-00', array());
                }
                $list .= $this->Html->tag('li', $link, array());
            }
        }
        for($i=1;$i<7-$lastWeek;$i++){
            $list .= $this->Html->tag('li', '', array());
        }
        
        
        return $this->Html->tag('ul', $label . $list, array('class' => 'clearfix'));
    
    }
    
    /*
     * 予約済みの表示
     */    
    function reservedBox($data){
        
        $time = array('00','30');
        
        $output = '';
        $startH = '';
        $rFlag = 0;
        for($i=7;$i<22;$i++){
            $h = str_pad($i,2,'0',STR_PAD_LEFT);
            foreach($time as $val){
                if($data[$h][$val] == '2' && $startH == ''){
                    $startH = $i;
                    $startM = $val;
                    $rFlag = 0;
                }elseif($data[$h][$val] != '2' && $startH != ''){
                    if($rFlag == 0){
                        $endH = $i;
                        $endM = $val;
                        //echo $startH . '<br>-' . $startM . '<br>' . $endH . '<br>-' . $endM . '<br>';
                    }
                    $output .= $this->_makeReserveBox($startH,$startM,$endH,$endM,$rFlag);
                    $startH = '';
                    $startM = '';
                }else{
                    $endH = $i;
                    $endM = $val;
                    $rFlag = 1;
                }
            }
        }
        if($startH != '' && $startM != ''){
            $output .= $this->_makeReserveBox($startH,$startM,$endH,$endM,1);
        }
        
        return $output;
        
    }
    
    /*
     * ボックスを生成
     */
    function _makeReserveBox($startH,$startM,$tempH,$tempM,$flag){

        $wide = 40;
        $height = ($tempH - $startH) * $wide;
        $top = (($startH - 7) * 40) + 7;
        if($startM == '30'){
            $top += 20;
        }
        if($tempM == '30' && $startM == '00'){
            $height += 20;
        }elseif($tempM == '00' && $startM == '30'){
            $height -= 20;
        }
        $style = 'height:' . $height . 'px;top:' . $top . 'px';
        $reserveDate = $this->Html->tag('div',$startH . ':' . $startM . 'から' . $tempH . ':' . $tempM . 'まで予約済み',array('class' => 'compTxt'));
        $output = $this->Html->tag('div',$reserveDate,array('class' => 'completed','style' => $style));
        
        if($top + $height != 607 && $flag == 1){
            $top = $top + $height;
            $style = 'height:20px;top:' . $top . 'px;background-color:#fee;';
            $reserveDate = $this->Html->tag('div','返却後30分は予約できません',array('class' => 'compTxt','style' => 'text-align:center'));
            $output .= $this->Html->tag('div',$reserveDate,array('class' => 'completed','style' => $style));
        }
        
        return $output;
        
    }

}