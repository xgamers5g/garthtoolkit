<?php
namespace GarthFramework\Request\Tools;

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
	public $file = null;
	///HTTP METHOD
	public $method = null;

	private function __construct(){

		$this->file = 'php://input';
		$this->method = strtoupper(getenv('REQUEST_METHOD'));

	}
	/**
	* @return Object 回傳PostHandler物件
	*/
	public static function getInstance(){

		if(self::$instance){
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
	public function getJSONData($location = null, $method = null){

		if ($location) {
			$this->file = $location;
		}
		if ($method) {
			$this->method = $method;
		}
		if ($this->method == 'POST') {
			$res = file_get_contents($this->file, true);
			if ($res) {
			
				return json_decode($res, true);
			} else {
			
				return array();
			}
		}

	}

	public function getPostData(){

		return $_POST;
	}

}
