<?php
namespace GarthFramework\System;

class LangHandler{

	private static $instance = null;
	private static $lang = null;

	private function __construct(){
		//closed
	}

	private function __clone(){
		//closed
	}

	/**
	 * fileLocation請傳語系檔的絕對路徑
	 * @param String $fileLocation 語系檔位置
	 */
	public static function getInstance($fileLocation){

		self::$lang = parse_ini_file($fileLocation, true);

		if(self::$instance instanceof \GarthFramework\System\LangHandler){

			return self::$instance;
		}
		self::$instance = new self;

		return self::$instance;

	}

	/**
	 * @param $section 有給section的話會回傳該section的內容，沒給的話吐全部，如果查無section的key則回傳false
	 */
	public function getLang($section = null){

		if($section){

			return array_key_exists($section, self::$lang) ? self::$lang[$section] : false;
		}

		return self::$lang;

	}

	public function __destruct(){

		self::$instance = null;
		self::$lang = null;

	}

}
