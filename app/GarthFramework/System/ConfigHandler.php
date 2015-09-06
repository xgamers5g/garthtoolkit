<?php
namespace GarthFramework\System;

class ConfigHandler{

	private static $instance = null;
	private static $config = null;

	private function __construct(){
		//closed
	}

	private function __clone(){
		//closed
	}

	public static function getInstance($path = null){

		if(!$path){
			$path = BASE_DIR.'/Config';
		}

		foreach(glob($path."/*.php") as $filename){
			$basename = explode('.', basename($filename));
			$basename = $basename[0];
		    self::$config[$basename] = include($filename);
		}

		if(self::$instance instanceof \GarthFramework\System\ConfigHandler){

			return self::$instance;
		}
		self::$instance = new self;
		
		return self::$instance;

	}

	/**
	 * @param $section 有給section的話會回傳該section的內容，沒給的話吐全部，如果查無section的key則回傳false
	 */
	public function getConfig($section = null){

		if($section){

			return array_key_exists($section, self::$config) ? self::$config[$section] : false;
		}

		return self::$config;

	}

	public function __destruct(){

		self::$instance = null;
		self::$config = null;

	}
}
