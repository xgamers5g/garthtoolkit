<?php
Class LangHandlerTest extends PHPUnit_Framework_TestCase{

	/**
	 * before標記代表在測試運行前會先執行的動作
	 * @before
	 */
	public function setEnvironment(){
		
		$this->obj = \GarthFramework\System\LangHandler::getInstance(TEST_DIR.'/Lang/lang.ini');

	}

	public function testgetInstance(){

		$this->assertEquals(true, $this->obj instanceof \GarthFramework\System\LangHandler);

	}

	public function testgetLangWithoutSection(){

		// [first_section]
		// one = 1
		// five = 5
		// animal = BIRD

		// [second_section]
		// path = "/usr/local/bin"
		// URL = "http://www.example.com/~username"

		// [third_section]
		// phpversion[] = "5.0"
		// phpversion[] = "5.1"
		// phpversion[] = "5.2"
		// phpversion[] = "5.3"


		$assertData = array(
			'first_section' => array(
				'one' => 1,
				'five' => 5,
				'animal' => 'BIRD'
			),
			'second_section' => array(
				'path' => '/usr/local/bin',
				'URL' => 'http://www.example.com/~username'
			),
			'third_section' => array(
				'phpversion' => array(
					"5.0", "5.1", "5.2", "5.3"
				)
			)
		);
		// print_r($this->obj->getLang());
		$this->assertEquals($assertData, $this->obj->getLang());
	}

	public function testgetConfigWithSection(){

		$assertData = array(
			'phpversion' => array(
				"5.0", "5.1", "5.2", "5.3"
			)
		);

		$this->assertEquals($assertData, $this->obj->getLang('third_section'));
	}

}