<?php

class AppError extends ErrorHandler{
    function system_error($params){
        $this->controller->set('message', $params['message']); 
        $this->_outputMessage('system_error');
    }
}