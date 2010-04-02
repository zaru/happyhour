
    <h2>東京23区内のお店リスト - <a href="<?php echo $html->url('/shop/happyhour'); ?>">ハッピーアワーを実施しているお店一覧</a></h2>
    <?php $spaginator->options(array('url' => $condition)); ?>
    <ul class="pages clearfix">
        <li class="read">全<?php echo $spaginator->counter(array('format' => '%count%')); ?>件</li>
        <?php
            if($spaginator->prev('<<')){
                echo '<li class="prev">' . $spaginator->prev('<<') . '</li>';
            }
            echo $spaginator->numbers(array(
                'tag'       => 'li', 
                'separator' => '',
                'before'    => '',
                'after'     => '',
                'modulus'   => 8,   
                'first'     => '', 
                'last'      => '')
            );
            if($spaginator->next('>>')){
                echo '<li class="next">' . $spaginator->next('>>') . '</li>';
            }
        ?>
    </ul>
    
    <?php
        foreach($shop as $val){
    ?>
    <div class="shop_list">
        <h3><?php echo h($val['ShopList']['name']); ?><?php if($val['ShopList']['happy_flg'] == '1'){ echo '<strong>ハッピーアワー実施中</strong>'; } ?></h3>
        <p class="category"><?php echo h($val['ShopList']['category1']); ?> - <?php echo h($val['ShopList']['category2']); ?></p>
        <dl>
            <dt>住所</dt>
            <dd><?php echo h($val['ShopList']['address']); ?></dd>
            <dt>電話番号</dt>
            <dd><?php echo h($val['ShopList']['tel']); ?></dd>
            <?php
                if($val['ShopList']['fax'] != ''){
            ?>
            <dt>FAX番号</dt>
            <dd><?php echo h($val['ShopList']['fax']); ?></dd>
            <?php
                }
            ?>
            <dt>開店時間</dt>
            <dd><?php echo h($val['ShopList']['opentime']); ?></dd>
            <dt>定休日</dt>
            <dd><?php echo h($val['ShopList']['holiday']); ?></dd>
        </dl>
    </div>
    <?php
        }
    ?>