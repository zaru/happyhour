
    <h2>東京23区内のお店リスト</h2>
    <?php $spaginator->options(array('url' => $condition)); ?>
    <ul class="pages">
        <?php
                echo '<li class="prev">' . $spaginator->prev('<<') . '</li>';
                echo $spaginator->numbers(array(
                'tag'       => 'li', 
                'separator' => '',
                'before'    => '',
                'after'     => '',
                'modulus'   => 8,   
                'first'     => '', 
                'last'      => ''  ));
                echo '<li class="next">' . $spaginator->next('>>') . '</li>';
            ?>
    </ul>
    <ul>
    <?php
        foreach($shop as $val){
    ?>
    <li><?php echo h($val['ShopList']['name']); ?></li>
    <?php
        }
    ?>
    </ul>