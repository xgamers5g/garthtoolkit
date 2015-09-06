<?php
Class CoreTest extends PHPUnit_Framework_TestCase{

	/**
	 * before標記代表在測試運行前會先執行的動作
	 * @before
	 */
	public function setEnvironment(){
		
		$this->core = \GarthFramework\System\Core::getInstance(TEST_DIR.'/Config', TEST_DIR.'/Lang/lang.ini');

	}

	public function testgetInstance(){

		
		$this->assertEquals(true, $this->core instanceof \GarthFramework\System\Core);

	}

	public function testgetPostHandler(){

		$obj = $this->core->getPostHandler();
		$this->assertEquals(true, $obj instanceof \GarthFramework\Tools\Request\PostHandler);

	}

	public function testGetSessionHandler(){

		$obj = $this->core->getSessionHandler();
		$this->assertEquals(true, $obj instanceof \GarthFramework\Tools\SessionHandler);

	}

	public function testGetToolCurl(){

		$obj = $this->core->getToolCurl('http://localhost');
		$this->assertEquals(true, $obj instanceof \GarthFramework\Tools\Request\ToolCurl);
	}

	public function testGetEncryptHandler(){
		$obj = $this->core->getEncryptHandler();
		$this->assertEquals(true, $obj instanceof \GarthFramework\Tools\EncryptHandler);
	}

	public function testgetTemplateParser(){

		$obj = $this->core->getTemplateParser();
		$this->assertEquals(true, $obj instanceof \GarthFramework\Tools\View\TemplateParser);

	}

	public function testgetConfigHandler(){

		$obj = $this->core->getConfigHandler();
		$this->assertEquals(true, $obj instanceof \GarthFramework\System\ConfigHandler);

	}

	public function testgetDb(){

		$obj = $this->core->getDb(true);
		$this->assertEquals(true, $obj instanceof \GarthFramework\Tools\Database\MockDB);
		
	}

}
