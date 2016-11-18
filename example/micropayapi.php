<?php
require_once "../lib/WxPay.Api.php";
require_once "WxPay.MicroPay.php";
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//打印输出数组信息
function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}


if(isset($_REQUEST["auth_code"]) && $_REQUEST["auth_code"] != ""){

    $result_data = array();
    try{
        $auth_code = $_REQUEST["auth_code"];
        $input = new WxPayMicroPay();
        $input->SetAuth_code($auth_code);
        $input->SetBody("刷卡测试样例-支付");
        $input->SetTotal_fee("1");
        $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));

        $microPay = new MicroPay();
        $result = $microPay->pay($input);

        $result_data['data'] = $result;
        $result_data['code'] = 200;
        $result_data['message'] = 200;

        echo json_encode($result_data);
    }
    catch (WxPayException $wpex){
        $edata = $wpex->getData();
        $result_data['data'] = $edata;
        $result_data['code'] = 500;
        $result_data['message'] = $wpex->getMessage() . ' ' . (empty($edata['err_code_des']) ? '' : $edata['err_code_des']);

        echo json_encode($result_data);
    }
    catch (Exception $ex){
        $result_data['data'] = null;
        $result_data['code'] = 501;
        $result_data['message'] = $ex->getMessage();

        echo json_encode($result_data);
    }

}

/**
 * 注意：
 * 1、提交被扫之后，返回系统繁忙、用户输入密码等错误信息时需要循环查单以确定是否支付成功
 * 2、多次（一半10次）确认都未明确成功时需要调用撤单接口撤单，防止用户重复支付
 */

?>