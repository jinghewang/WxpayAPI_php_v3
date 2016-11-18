<?php

/**
 *
 * 微信支付API异常类
 * @author widyhu
 *
 */
class WxPayException extends Exception {

    private $_data;

    public function __construct($message = "", $code = 0, Exception $previous = null,$data=null) {
        parent::__construct($message, $code, $previous);
        if (!empty($data))
            $this->_data = $data;
    }

    public static function initWithData($message, $data) {
        $wpe = new WxPayException($message);
        $wpe->_data = $data;
        return $wpe;
    }


    /**
     * @return mixed
     */
    public function getData() {
        return $this->_data;
    }


    public function errorMessage() {
        return $this->getMessage();
    }
}
