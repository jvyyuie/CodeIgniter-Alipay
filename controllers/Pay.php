<?php
/** 
 * 控制器：支付宝支付相关
 * @package	controller
 * @author	jvyyuie
 * @version	Version 1.1.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('BaseController.php');

/**
 * 控制器：支付宝支付相关
 */
class Pay extends BaseController
{
	function __construct()
	{ 
		parent::__construct();
	} 
	function __destruct()
	{ 
		parent::__destruct();
	} 
	public function test()
	{
		$this->load->helper('alipaycore');
		$this->load->helper('alipaymd5');
		$this->load->library("AlipaySubmit");
		$out_trade_no	= '2342342304824'; //商户订单号，商户网站订单系统中唯一订单号，必填
		$subject	= 'test001'; //订单名称，必填
		$total_fee	= '0.01'; //付款金额，必填
		$body		= 'sdf'; //商品描述，可空

		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service"       => $this->config->config['service'],
				"partner"       => $this->config->config['partner'],
				"seller_id"	=> $this->config->config['seller_id'],
				"payment_type"	=> $this->config->config['payment_type'],
				"notify_url"	=> $this->config->config['notify_url'],
				"return_url"	=> $this->config->config['return_url'],
				"anti_phishing_key"=>$this->config->config['anti_phishing_key'],
				"exter_invoke_ip"=>$this->config->config['exter_invoke_ip'],
				"out_trade_no"	=> $out_trade_no,
				"subject"	=> $subject,
				"total_fee"	=> $total_fee,
				"body"		=> $body,
				"_input_charset"=> trim(strtolower($this->config->config['input_charset']))
				);

		//建立请求
		$alipaySubmit = new AlipaySubmit();
		$alipaySubmit->init($this->config->config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		print '<pre>';
		print_r($parameter);
		print '</pre>';
		echo $html_text;
		print "<textarea>";
		print "</textarea>";
	}
	function returnlink()
	{
		// 支付宝回调，根据订单找到订单，找到用户
		// 确认订单处于尚未完成状态，则将订单改成完成状态，并给用户进行充值
		// 同时进行日志记录
		$out_trade_no = $this->input->get('out_trade_no');
		$this->load->model('ChargeOrderModel');
		$ret = $this->ChargeOrderModel->loadOne('OrderNumber', $out_trade_no, true);
		//print_r($ret);
		if($ret->IsCompleted==false || true)
		{
			$this->ChargeOrderModel->setOrderToCompleted($out_trade_no);
			$ChargeMoney	= floatval($ret->ChargeMoney);
			$UserId		= intval($ret->UserId);
			$this->load->model('UserModel');
			$this->UserModel->charge($UserId, $ChargeMoney);

			// 充值过程记录
			$this->load->model('MoneyLogModel');
			$arr = array(
					'UserId'	=> $UserId,
					'Money'		=> $ChargeMoney,
					'Type'		=> MONEY_CHARGE,
					'Datetime'	=> $this->Datetime,
				    );
			$MoneyLog = $this->MoneyLogModel->init($arr)->writetotbl();

			$params = array(
					'MoneyLog'	=> $MoneyLog,
				       );
			$this->setupTemplate($params);
		}
		else
		{
			$this->perror("订单已经失效");
		}
	}
	function notifylink()
	{
		die("F");
	}
}
