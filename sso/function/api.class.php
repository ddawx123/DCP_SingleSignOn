<?php

class Response {

	public static $_instance;
	private $format = 'xml';

	private function __construct($format = 'xml') {
		$this->format = $format;
	}

	public static function getInstance($format = 'xml') {
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self($format);
			return self::$_instance;
		}
	}
	
	public function make($code = -1, $message = '', $data = array()) {
		if ($this->format == 'xml') {
			self::xmlEncode($code, $message, $data);
		}
		else if ($this->format == 'json') {
			self::jsonEncode($code, $message, $data);
		}
		else {
			self::errorHandler(405, 'Method not allow.');
		}
	}

	/**
	* 使用json方式输出通信数据
	* @param integer $code 状态码
	* @param string $message 提示信息
	* @param array $data 数据
	* return string
	*/
	public static function jsonEncode($code, $message = '', $data = array()) {
		if(!is_numeric($code)) {
			return '';
		}
		
		$result = array(
			'code' => $code,
			'message' => $message,
			'data' => $data,
			'requestID' => date("YmdHis",time())
		);
		
		header("Content-Type: application/json; charset=UTF-8");
		if (!isset($_REQUEST['callback'])) {
			echo json_encode($result);
		}
		else {
			echo htmlspecialchars($_REQUEST['callback']).'('.json_encode($result).')';
		}
		exit();
	}
	
	/**
	* 使用xml方式输出通信数据
	* @param integer $code 状态码
	* @param string $message 提示信息
	* @param array $data 数据
	* return string
	*/
	public static function xmlEncode($code, $message = '', $data = array()) {
		if(!is_numeric($code)) {
			return '';
		}
		
		$result = array(
			'code' => $code,
			'message' => $message,
			'data' => $data
		);
		$requestId = date("YmdHis",time());
		header("Content-Type: text/xml; charset=UTF-8");
		$xml = "<?xml version='1.0' encoding='UTF-8'?>";
		$xml .= "<root>";
		
		$xml .= self::xmlToEncode($result);
		$xml .= "<requestId>{$requestId}</requestId>\n";
		$xml .= "</root>";
		
		echo $xml;
		exit();
	}
	
	/**
	* xml数据内容封装
	* @param array $data 数据
	* return string
	*/
	public static function xmlToEncode($data) {
		$xml = $attr = "";
		foreach($data as $key => $value) {
			if(is_numeric($key)) {
				$attr = " id='{$key}'";
				$key = "item";
			}
			$xml .= "<{$key}{$attr}>";
			$xml .= is_array($value) ? self::xmlToEncode($value) : $value;
			$xml .= "</{$key}>\n";
		}
		return $xml;
		exit();
	}
	
	public static function errorHandler($code = -1, $message = '') {
		$requestId = date("YmdHis",time());
		header("Content-Type: text/xml; charset=UTF-8");
		$xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
		$xml .= "<root>\n";
		$xml .= "<code>".$code."</code>\n";
		$xml .= "<message>".$message."</message>\n";
		$xml .= "<requestId>{$requestId}</requestId>\n";
		$xml .= "</root>\n";
		echo $xml;
		exit();
	}

}
