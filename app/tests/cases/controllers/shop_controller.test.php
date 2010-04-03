<?php 
class ArticlesControllerTest extends CakeTestCase { 
    function startCase() { 
        echo '<h1>テストケースを開始します</h1>'; 
    } 
    function endCase() { 
        echo '<h1>テストケースを終了します</h1>'; 
    } 
    function startTest($method) { 
        echo '<h3>メソッド「' . $method . '」を開始します</h3>'; 
    } 
    function endTest($method) { 
        echo '<hr />'; 
    } 
    function testIndex() { 
        $result = $this->testAction(
            '/shop/happyhour',
            array('return' => 'contents')
        );
        debug($result);
    }
} 