<?php
namespace GarthFramework\System;

/**
 * 整合核心
 * 1. 所有物件從這裡初始化出來。
 * 2. 所有環境設定從程式進入點指定到這裡包。
 * 3. 所有物件配合單例模式避免被濫用。
 * 4. 所有成員變數除了環境設定外其它全部設定為private
 */

// use CustomerAdmin\Controller\AdminCommunityController;
// use CustomerAdmin\Model\AdminCommunityModel;

use GarthFramework\Tools\Database\DB;
use GarthFramework\Tools\Request\PostHandler;
use GarthFramework\Tools\Request\ToolCurl;
use GarthFramework\Tools\View\TemplateParser;
use GarthFramework\Tools\SessionHandler;
use GarthFramework\Tools\EncryptHandler;

class Core{

	private static $instance;
	private static $configHandler;
	private static $langHandler;
	private static $db;

	private function __construct(){
		//關閉建構式
	}

	public static function getInstance($configPath = null, $langLocation = null){
		
		self::$configHandler = ConfigHandler::getInstance($configPath);
		self::$langHandler = LangHandler::getInstance($langLocation);

		//把自身物件裝進$instance
		if(self::$instance instanceof Core){
			
			return self::$instance;
		}
		self::$instance = new self;
		
		return self::$instance;

	}

	/**
	 * 工具包
	 */
	public function getPostHandler(){

		return PostHandler::getInstance();

	}

	public function getToolCurl($uri){

		return ToolCurl::getInstance($uri);
	}

	public function getSessionHandler(){

		return SessionHandler::getInstance();	

	}

	public function getEncryptHandler(){

		return EncryptHandler::getInstance();

	}

	public function getTemplateParser(){

		return TemplateParser::getInstance();

	}

	public function getDb($unittest = null){

		if($unittest){

			return new \GarthFramework\Tools\Database\MockDB();

		}

		if(self::$db instanceof DB){

			return self::$db;
		}
		self::$db = new DB(self::$configHandler->getConfig('database'));

		return self::$db;

	}

	public function getConfigHandler($type = null){


		return self::$configHandler;

	}

	public function getLangHandler(){


		return self::$langHandler;

	}
	
	private function __clone(){
		//關閉clone
	}

	public function __destruct(){

		self::$instance = null;
		self::$configHandler = null;
		self::$langHandler = null;
		self::$db = null;

	}

}
