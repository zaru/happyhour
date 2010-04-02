<?php
/* SVN FILE: $Id$ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $html->charset() . "\n"; ?>
<title><?php echo $title_for_layout; ?>やまいちパーク</title>
<meta name="keywords" content="やまいちパーク,カーシェアリング,仙台,プリウス," />
<meta name="description" content="" />
<?php
    echo $html->css('import') . "\n";
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<?php
    echo $javascript->link('pagescroll') . "\n";
?>
</head>
<body>
	<!--▼header▼-->
<div id="headerArea">
	<div id="header">
		<div class="hLeft"><h1 class="siteName"><img src="<?php echo $html->url('/img/logo.gif'); ?>" alt="株式会社やまいちパーク" /></h1></div>
        <?php
            if(isset($userData)){
        ?>
        <div class="hRight">
            ID <?php echo h($userData['login_id'] . ':' . $userData['user_name'] . '様'); ?> | 
            <?php
                //利用時間など
                $totalUse = 0;
                $totalKm = 0;
                foreach($reserveUser as $val){
                    $totalUse += (strtotime($val['0']['date_format(reserve_end,"%Y-%m-%d %H:%i")']) - strtotime($val['0']['date_format(reserve_start,"%Y-%m-%d %H:%i")'])) / 3600;
                    $totalKm += $val['ReserveList']['run_end'] - $val['ReserveList']['run_start'];
                }
            ?>
            当月ご利用時間 <span class="num"><?php echo $totalUse; ?>時間</span> | 
            ご利用残時間 <span class="num"><?php echo 48 - $totalUse; ?>時間</span> | 
            ご利用料金 <span class="num">約<?php echo number_format($totalUse * 500); ?>円</span> | 
            走行料金 <span class="num"><?php echo number_format(round($totalKm) * 20); ?>円</span>
            <br />
            
            [ <a href="<?php echo $html->url('/reserve'); ?>">予約画面に戻る</a> ]
            [ <a href="<?php echo $html->url('/auth/user_logout'); ?>">ログアウトする</a> ]
        </div>
        <?php
            }
        ?>
		
		<!-- /header -->
	</div>
</div>
<div id="wrapper">



    <!--▼contents▼-->
    <div id="contents" class="clearfix">
        <!--▼mainColumn▼-->
        <div id="mainColumn">
        
            <?php $session->flash(); ?>
            <?php echo $content_for_layout; ?>
        
        <!-- /mainColumn -->
        </div>
    <!-- /contents -->
    </div>
    <div class="pageTop"><a href="#contents" onclick="return pageScroll('wrapper')"><img src="<?php echo $html->url('/img/go_top.gif'); ?>" alt="このページの先頭へ" /></a></div>
    <!--▼footer▼-->
    <div id="footer">
        <div class="copyrignt"><img src="<?php echo $html->url('/img/copy.gif'); ?>" alt="Copyright &copy;2009 YAMAICHI PARK All Rights Reserved." /></div>
    <!-- /footer -->
    </div>
<!--/wrapper-->
</div>
<?php echo $cakeDebug; ?>
</body>
<!-- Last Updated:2009/02/10 [ID] -->
</html>
