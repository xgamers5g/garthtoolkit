<?php
namespace GarthFramework\Tools\Request;

/**
 * ToolCurl
 * 
 * 這是CURL的包覆類別
 *
 * 主要是為了建立帶有SessionCookie的Curl測試環境
 *
 * It's work for SessionCookie
 *
 * first, ToolCurl::load($uri),you'll get an object
 *
 * if you want to touch the url just object->get()
 *
 * if you want to get the call back data just object->get(true);
 *
 * 要讀取header object->get(true, true)
 *
 * 要加入Authorization到HEADER裡的話 object->get(true, true, $value)
 *
 * then if you want to post JSON data just object->postJSON($postdata)
 *
 * with call back data : object->postJSON($postdata, true)
 *
 * 要讀取header object->postJSON($postdata, true, true)
 *
 * 要加入Authorization到HEADER裡的話 object->postJSON($postdata, true, true, $value)
 *
 * 2014.10.6 Garth_Wang
 */

class ToolCurl
{
	/** 
	 * @param static $ci 裝載curl_init()物件
	 */
	private static $ci = null;
	/** 
	 * @param static $uri 裝載目標uri的網址
	 */
	private static $uri = null;

	private static $instance = null;
	
	private function __construct()
	{
		
	}
	/**
	 * 回傳ToolCurl物件
	 * @param string $uri 初始化物件必需要有目標uri
	 */
	public static function getInstance($uri)
	{
		self::$uri = $uri;
		//指定cookie位置
		
		self::$ci = null;
		self::$ci = curl_init(self::$uri);

		//CLI模式要創建Cookie
		if (php_sapi_name() == "cli") {
			$cookie_file= BASE_DIR.'/mycookie.tmp';
			if (!file_exists($cookie_file)) {
				$fp = fopen($cookie_file, 'w+');
				fclose($fp);
				chmod($cookie_file, 0777);
			}
			curl_setopt(self::$ci, CURLOPT_COOKIEFILE, $cookie_file);
			curl_setopt(self::$ci, CURLOPT_COOKIEJAR, $cookie_file);
		}
		curl_setopt(self::$ci, CURLOPT_USERAGENT, 'ToolCurl');
		curl_setopt(self::$ci, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt(self::$ci, CURLOPT_SSL_VERIFYPEER, 0);

		
		if(self::$instance instanceof \GarthFramework\Tools\Request\ToolCurl){
		
			return self::$instance;
		}
		self::$instance = new self;

		return self::$instance;
	}

	public function post($postdata, $return = null, $head = null)
	{
		curl_setopt(self::$ci, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt(self::$ci, CURLOPT_POSTFIELDS, $postdata);
		if ($return === true) {
			curl_setopt(self::$ci, CURLOPT_RETURNTRANSFER, 1);
			if ($head === true) {
				curl_setopt(self::$ci, CURLOPT_HEADER, 1);
			}

			return curl_exec(self::$ci);
		}

		curl_exec(self::$ci);
	}

	/**
	 * 以postJSON物件的方式打uri
	 * @param JSON $postdata 要送的post的參數
	 * @param bool $return 填入true則回傳curl回來的結果
	 */
	public function postJSON($postdata, $return = null, $head = null)
	{
		curl_setopt(self::$ci, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt(self::$ci, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt(
			self::$ci,
			CURLOPT_HTTPHEADER,
			array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($postdata)
			)
		);
		if ($return === true) {
			curl_setopt(self::$ci, CURLOPT_RETURNTRANSFER, 1);
			if ($head === true) {
				curl_setopt(self::$ci, CURLOPT_HEADER, 1);

			}

			return curl_exec(self::$ci);
		}

		curl_exec(self::$ci);
	}

	/**
	 *  get方式打api
	 *  @param bool $return 填入true則回傳curl回來的結果
	 */
	public function get($return = null, $head = null, $token = null){
		if ($token) {
			curl_setopt(self::$ci, CURLOPT_HTTPHEADER, array('Authorization: ' . $token));
		}
		if ($return === true) {
			curl_setopt(self::$ci, CURLOPT_RETURNTRANSFER, 1);
			if ($head === true) {
				curl_setopt(self::$ci, CURLOPT_HEADER, 1);
			}

			return curl_exec(self::$ci);
		}

		curl_exec(self::$ci);
	}

	public static function getHeadersFromCurlResponse($response)
	{
		$headers = array();
		$header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
		foreach (explode("\r\n", $header_text) as $i => $line) {
			if ($i === 0) {
				$headers['http_code'] = $line;
			} else {
				list ($key, $value) = explode(': ', $line);
				$headers[$key] = $value;
			}
		}

		return $headers;
	}

	public static function getCookies($response)
	{
		$cookies = array();
		$header_array = explode("\r\n", $response);
		foreach($header_array as $k => $line){
			if(strpos($line, 'Set-Cookie') > -1){
				array_push($cookies, $line);
			}
		}

		$returnValue = [];
		foreach($cookies as $cookie){
			$tmp = explode("Set-Cookie: ", $cookie)[1];
			$tmp = explode(";", $tmp)[0];

			list ($key, $value) = explode('=', $tmp, 2);
			$returnValue[$key] = $value;
		}

		return $returnValue;
	}

	public function __destruct(){

		if (php_sapi_name() == "cli") {
			$cookie_file = BASE_DIR.'/mycookie.tmp';
			if(file_exists($cookie_file)){
				unlink($cookie_file);
			}
		}

		self::$ci = null;
		self::$uri = null;
		self::$instance = null;
	}
}
