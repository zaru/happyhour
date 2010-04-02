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
            if($i == $day){
                $list .= $this->Html->tag('li', $i, array('class' => 'focus'));
            }else{
                if($year . $month . str_pad($i,2,'0',STR_PAD_LEFT) < $nowDate){
                    $link = $i;
                }else{
                    $link = $this->Html->link($i, '/reserve/index/' . $year . '/' . $month . '/' . $i, array());
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
     * 予約済みの出力
     */
    function reserved($data,$year,$month,$day){
    
        $output = '';
        $reserveDate = '';
        
        //2台あるので30分ごとに重複の予約データをチェックする
        $minute = array('00','30');
        for($i=7;$i<23;$i++){
            foreach($minute as $mVal){
                $reserveDate[$i][$mVal] = 0;
                $count = 0;
                foreach($data as $val){
                    list($sDate,$sTime) = explode(' ',$val['ReserveList']['reserve_start']);
                    list($sHour,$sMinute) = explode(':',$sTime);
                    list($eDate,$eTime) = explode(' ',$val['ReserveList']['reserve_end']);
                    list($eHour,$eMinute) = explode(':',$eTime);
                    if($sMinute == '00'){
                        $sMinuteP = '30';
                        $sHourP = $sHour - 1;
                        $eHourP = $eHour;
                    }else{
                        $sMinuteP = '00';
                        $sHourP = $sHour;
                        $eHourP = $eHour + 1;
                    }
                    if($sDate == $year . '-' . $month . '-' . $day && $eDate == $year . '-' . $month . '-' . $day
                    && $i . $mVal >= $sHourP . $sMinuteP && $i . $mVal < $eHourP . $sMinuteP){
                        //echo $i .'>='. $sHourP .' && '. $i .'<='. $eHourP .' && '. $mVal .'!='. $sMinuteP . '<br />';
                        $count++;
                    }elseif($sDate != $year . '-' . $month . '-' . $day && $eDate == $year . '-' . $month . '-' . $day
                    && $i . $mVal < $eHourP . $sMinuteP){
                        //echo $i . $mVal . ' < ' . $eHourP . $sMinuteP . '<br />';
                        $count++;
                    }elseif($sDate == $year . '-' . $month . '-' . $day && $eDate != $year . '-' . $month . '-' . $day
                    && $i . $mVal >= $sHourP . $sMinuteP){
                        //echo $i . $mVal . ' < ' . $eHourP . $sMinuteP . '<br />';
                        $count++;
                    }
                }
                
                //全台借り切られている時間
                if($count == 2){
                    $reserveDate[$i][$mVal] = 1;
                }
                //echo $count . ' = ' . $i . ':' . $mVal . ' -> ' . $reserveDate[$i][$mVal] . '<br />';
            }
        }
        //pr($reserveDate);
        $startFlag = 0;
        foreach($reserveDate as $h => $val){
            foreach($val as $m => $flag){
                if($flag == '1' && $startFlag == '0'){
                    $startH = $h;
                    $startM = $m;
                    $startFlag = 1;
                }elseif($flag == '0' && $startFlag == '1'){
                    $startFlag = 0;
                    
                    $tempH = $h;
                    $tempM = $m;
                    
                    $output .= $this->_makeReserveBox($startH,$startM,$tempH,$tempM);
                    
                }elseif($flag == '1' && $startFlag == '1'){
                    $tempH = $h;
                    $tempM = $m;
                }
            }
        }
        if($startFlag == 1){
            $output .= $this->_makeReserveBox($startH,$startM,$tempH,$tempM);
        }
        
        return $output;
    }
    
    /*
     * 予約可能な時間
     */
    function makeOkTimeS($data,$year,$month,$day){
    
        $output = array();
        $reserveDate = '';
        
        //今日の日付の場合、現時刻からスタート
        if(date('Ymd') == $year.$month.$day){
            $hour = date('H') + 1;
        }else{
            $hour = 7;
        }
        //2台あるので30分ごとに重複の予約データをチェックする
        $minute = array('00','30');
        for($i=$hour;$i<23;$i++){
            foreach($minute as $mVal){
                $reserveDate[$i][$mVal] = 0;
                $count = 0;
                foreach($data as $val){
                    list($sDate,$sTime) = explode(' ',$val['ReserveList']['reserve_start']);
                    list($sHour,$sMinute) = explode(':',$sTime);
                    list($eDate,$eTime) = explode(' ',$val['ReserveList']['reserve_end']);
                    list($eHour,$eMinute) = explode(':',$eTime);
                    if($sMinute == '00'){
                        $sMinuteP = '30';
                        $sHourP = $sHour - 1;
                        $eHourP = $eHour;
                    }else{
                        $sMinuteP = '00';
                        $sHourP = $sHour;
                        $eHourP = $eHour + 1;
                    }
                    if($sDate == $year . '-' . $month . '-' . $day && $eDate == $year . '-' . $month . '-' . $day
                    && $i . $mVal >= $sHourP . $sMinuteP && $i . $mVal < $eHourP . $sMinuteP){
                        //echo $i .'>='. $sHourP .' && '. $i .'<='. $eHourP .' && '. $mVal .'!='. $sMinuteP . '<br />';
                        $count++;
                    }elseif($sDate != $year . '-' . $month . '-' . $day && $eDate == $year . '-' . $month . '-' . $day
                    && $i . $mVal < $eHourP . $sMinuteP){
                        //echo $i . $mVal . ' < ' . $eHourP . $sMinuteP . '<br />';
                        $count++;
                    }elseif($sDate == $year . '-' . $month . '-' . $day && $eDate != $year . '-' . $month . '-' . $day
                    && $i . $mVal >= $sHourP . $sMinuteP){
                        //echo $i . $mVal . ' < ' . $eHourP . $sMinuteP . '<br />';
                        $count++;
                    }
                }
                
                //全台借り切られている時間
                if($count != 2){
                    $reserveDate[$i][$mVal] = 1;
                }
                //echo $count . ' = ' . $i . ':' . $mVal . ' -> ' . $reserveDate[$i][$mVal] . '<br />';
            }
        }
        //pr($reserveDate);
        $startFlag = 0;
        foreach($reserveDate as $h => $val){
            foreach($val as $m => $flag){
                if($flag == '1' && $startFlag == '0'){
                    if($m == '30'){
                        $startH = $h + 1;
                        $startM = '00';
                    }elseif($m == '00'){
                        $startH = $h;
                        $startM = '30';
                    }
                    //$startH = $h;
                    //$startM = $m;
                    $startFlag = 1;
                }elseif($flag == '0' && $startFlag == '1'){
                    $startFlag = 0;
                    
                    $tempH = $h;
                    $tempM = $m;
                    if(($tempH*60+$tempM) - ($startH*60+$startM) > 30){
                        for($i=$startH;$i<$tempH;$i++){
                            $output[] = $i;
                        }
                    }
                    
                }elseif($flag == '1' && $startFlag == '1'){
                    $tempH = $h;
                    $tempM = $m;
                }
            }
        }
        if($startFlag == 1){
            if(($tempH*60+$tempM) - ($startH*60+$startM) > 30){
                for($i=$startH;$i<$tempH;$i++){
                    $output[] = $i;
                }
            }
        }
        
        return $output;
    }
    
    /*
     * 予約可能な日
     */
    function makeOkDayE($data,$year,$month,$day){
    
        $output = array();
        $reserveDate = '';
        $result = true;
        
        list($year,$month,$day) = explode('-',date('Y-m-d', strtotime("+1 day",mktime(0, 0, 0, $month, $day, $year))));
        
        $minute = array('00');
        for($i=7;$i<=7;$i++){
            foreach($minute as $mVal){
                $reserveDate[$i][$mVal] = 0;
                $count = 0;
                foreach($data as $val){
                    list($sDate,$sTime) = explode(' ',$val['ReserveList']['reserve_start']);
                    list($sHour,$sMinute) = explode(':',$sTime);
                    list($eDate,$eTime) = explode(' ',$val['ReserveList']['reserve_end']);
                    list($eHour,$eMinute) = explode(':',$eTime);
                    if($sMinute == '00'){
                        $sMinuteP = '30';
                        $sHourP = $sHour - 1;
                        $eHourP = $eHour;
                    }else{
                        $sMinuteP = '00';
                        $sHourP = $sHour;
                        $eHourP = $eHour + 1;
                    }
                    if($sDate == $year . '-' . $month . '-' . $day && $eDate == $year . '-' . $month . '-' . $day
                    && $i . $mVal >= $sHourP . $sMinuteP && $i . $mVal < $eHourP . $sMinuteP){
                        $count++;
                    }elseif($sDate != $year . '-' . $month . '-' . $day && $eDate == $year . '-' . $month . '-' . $day
                    && $i . $mVal < $eHourP . $sMinuteP){
                        $count++;
                    }elseif($sDate == $year . '-' . $month . '-' . $day && $eDate != $year . '-' . $month . '-' . $day
                    && $i . $mVal >= $sHourP . $sMinuteP){
                        $count++;
                    }
                }
                
                //全台借り切られている時間
                if($count == 2){
                    $result = false;
                }
            }
        }
        return $result;
    }
    
    function reservedBox($data){
        
        $time = array('00','30');
        
        $output = '';
        $startH = '';
        
        for($i=7;$i<22;$i++){
            $h = str_pad($i,2,'0',STR_PAD_LEFT);
            foreach($time as $val){
                if($data[$h][$val] == '2' && $startH == ''){
                    $startH = $i;
                    $startM = $val;
                }elseif($data[$h][$val] != '2' && $startH != ''){
                    $output .= $this->_makeReserveBox($startH,$startM,$endH,$endM);
                    $startH = '';
                    $startM = '';
                }else{
                    $endH = $i;
                    $endM = $val;
                }
            }
        }
        
        return $output;
        
    }
    
    /*
     * 予約済みのボックスを生成
     */
    function _makeReserveBox($startH,$startM,$tempH,$tempM){

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
        
        if($top + $height != 607){
            $top = $top + $height;
            $style = 'height:20px;top:' . $top . 'px;background-color:#fee;';
            $reserveDate = $this->Html->tag('div','返却後30分は予約できません',array('class' => 'compTxt','style' => 'text-align:center'));
            $output .= $this->Html->tag('div',$reserveDate,array('class' => 'completed','style' => $style));
        }
        return $output;
        
    }

}