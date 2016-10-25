<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['partner']	= '2088************';
$config['seller_id']	= $config['partner'];
$config['key']		= '********************************';
$config['notify_url']	= "http://wwwdomaincom/Pay/notifylink";
$config['return_url']	= "http://wwwdomaincom/Pay/returnlink";
$config['sign_type']	= strtoupper('MD5');
$config['input_charset']= strtolower('utf-8');
$config['cacert']	= getcwd().'\\cacert.pem';
$config['transport']	= 'http';
$config['payment_type']	= "1";
$config['service']	= "create_direct_pay_by_user";
$config['anti_phishing_key']	= "";
$config['exter_invoke_ip']	= "";
