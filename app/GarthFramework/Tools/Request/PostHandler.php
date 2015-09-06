<?php
namespace GarthFramework\Tools\Request;

/**
 * ToolPostHandler
 *
 * 專門拿來解析HttpRequest的POST資料
 *
 * The Tool for recieve the data from HttpMethod 'POST'
 *
 * We can start ToolPostHandler with ToolPostHandler::init()
 *
 * it will return a ToolPostHandler Object
 *
 * if you want to get data when Content-Type isn't multipart/form-data
 *
 * you can try ToolPostHandler::init()->getData()
 * 
 * if you want to get multipart/form-data or origin form post Variable
 *
 * you can try ToolPostHandler::init()->getDataByKey($key)
 *
 * if you want to get JSON data , use this ToolPostHandler::init()->getJSONData()
 *
 * 2014.09.25 Garth_Wang
 */

class PostHandler{

	private static $instance;
	///file_get_contents的路徑
	private static $target = null;
	///HTTP METHOD
	private static $httpMethod = null;

	private function __construct(){

	}
	/**
	* @return Object 回傳PostHandler物件
	*/
	public static function getInstance($target = null, $httpMethod = null){

		//測試時會注入target, 如無注入則使用php://input
		if(!$target){
			self::$target = 'php://input';
		}else{
			self::$target = $target;
		}

		//測試時會注入httpmethod, 如無注入則會偵測server狀態
		if(!$httpMethod){
			self::$httpMethod = strtoupper(getenv('REQUEST_METHOD'));
		}else{
			self::$httpMethod = $httpMethod;
		}

		/* 單例模式運用 */
		if(self::$instance instanceof \GarthFramework\Tools\Request\PostHandler){

			return self::$instance;
		}
		self::$instance = new self;

		return self::$instance;

	}
	/**
	 * 取得POST過來的JSON物件
	 * @param String $location 手動指定file_get_contents的路徑，用於測試時的DI。
	 * @param String $method 手動指定http method，用於測試時的DI。
	 * @return array 返回json_decode後的陣列或空陣列
	 */
	public function getJSONData(){

		if (self::$httpMethod == 'POST') {
			$res = file_get_contents(self::$target, true);
			if ($res) {
			
				return json_decode($res, true);
			} 

			return array();
		}

	}

	public function getPostData($postdata = null){

		if($postdata){
			$_POST = $postdata;
		}

		return (isset($_POST) AND !empty($_POST)) ? $_POST : null;
	}

}
