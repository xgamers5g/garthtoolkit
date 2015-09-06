<?php
class PostHandlerUnitTest extends PHPUnit_Framework_TestCase
{
	//藕合測試
	// public function testgetJSONDataNotEmpty(){

	// 	// $res = 

	// }

	//解藕測試
	public function testgetJSONDataNotEmpty()
	{
		$obj = \GarthFramework\Tools\Request\PostHandler::getInstance(TEST_DIR.'/UnitTest/Tools/testPostHandler.json', 'POST');
		$this->assertEquals(
			array(
				'statuscode' => 200,
				'detail' => '這是測試檔案'
			),
			$obj->getJSONData()
		);
	}
	public function testgetJSONDataIsEmpty()
	{
		$obj = \GarthFramework\Tools\Request\PostHandler::getInstance(null, 'POST');
		$this->assertEquals(
			array(),
			$obj->getJSONData()
		);
	}
	public function testgetJSONDataNotPost()
	{
		$obj = \GarthFramework\Tools\Request\PostHandler::getInstance();
		$this->assertEquals(
			null,
			$obj->getJSONData()
		);
	}

	public function testgetPostData(){

		$obj = \GarthFramework\Tools\Request\PostHandler::getInstance();

		$assertData = array(
			'test' => 1
		);

		$this->assertEquals(
			$assertData,
			$obj->getPostData($assertData)
		);
	}

	public function testgetPostDataWithNoParam(){

		$obj = \GarthFramework\Tools\Request\PostHandler::getInstance();

		$this->assertEquals(
			null,
			$obj->getPostData()
		);

	}

}
