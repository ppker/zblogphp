<?php
/*
//构造要请求的参数数组，登陆
$parameter = array(
"service" => "alipay.auth.authorize",
"target_service"	=> "user.auth.quick.login",
"return_url"	=> $bloghost."zb_users/plugin/alipay/login_return_url.php",
);

//构造要请求的参数数组，无需改动
$parameter = array(

"seller_email" => trim($alipay_config['seller_email']),
"out_trade_no" => $out_trade_no, //商户订单号
"subject" => $subject, //订单名称
"total_fee" => $total_fee, //付款金额
"body" => $body, //订单描述
"show_url" => $show_url, //商品展示地址
);
 */
function AlipayAPI_Start($parameter) {
    global $zbp;
    require_once "alipay.config.php";
    require_once "lib/alipay_submit.class.php";

    //公共$parameter
    $parameter["partner"] = trim($alipay_config['partner']);
    //$parameter["anti_phishing_key"] = ""; //防钓鱼时间戳//若要使用请调用类文件submit中的query_timestamp函数
    //$parameter["exter_invoke_ip"] = GetGuestIP(); //客户端的IP地址
    $parameter["_input_charset"] = trim(strtolower($alipay_config['input_charset']));

    $parameter["notify_url"] = (isset($parameter["notify_url"])) ? $parameter["notify_url"] : ($zbp->host . "zb_users/plugin/alipay/pay_notify_url.php");
    $parameter["return_url"] = (isset($parameter["return_url"])) ? $parameter["return_url"] : ($zbp->host . "zb_users/plugin/alipay/pay_return_url.php");
    $parameter["service"] = (isset($parameter["service"])) ? $parameter["service"] : 'create_direct_pay_by_user';
    $parameter["payment_type"] = (isset($parameter["payment_type"])) ? $parameter["payment_type"] : 1;
    $parameter["seller_email"] = trim($alipay_config['seller_email']);
    //print_r($parameter);die();
    //建立请求
    $alipaySubmit = new AlipaySubmit($alipay_config);
    $html_text = $alipaySubmit->buildRequestForm($parameter, "get", "确认");
    echo $html_text;
}
