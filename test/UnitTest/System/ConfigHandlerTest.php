<?php
Class ConfigHandlerTest extends PHPUnit_Framework_TestCase{

	/**
	 * before標記代表在測試運行前會先執行的動作
	 * @before
	 */
	public function setEnvironment(){
		
		$this->obj = \GarthFramework\System\ConfigHandler::getInstance(TEST_DIR.'/Config');

	}

	public function testgetInstance(){

		$this->assertEquals(true, $this->obj instanceof \GarthFramework\System\ConfigHandler);

	}

	public function testgetConfigWithoutSection(){

		$assertData = array(
			'database' => array(
				'usr'=>'root',
				'pwd'=>'test'
			)
		);

		$this->assertEquals($assertData, $this->obj->getConfig());
	}

	public function testgetConfigWithSection(){

		$assertData = array(
			'usr' => 'root',
			'pwd' => 'test'
		);

		$this->assertEquals($assertData, $this->obj->getConfig('database'));
	}

}