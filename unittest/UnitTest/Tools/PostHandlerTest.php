<?php
class PostHandlerUnitTest extends PHPUnit_Framework_TestCase
{
	//藕合測試
	public function testgetJSONDataNotEmpty(){

		// $res = 

	}

	//解藕測試
	public function testgetJSONDataNotEmpty()
	{
		$res = \Auth\Model\PostHandler::getInstance();
		$this->assertEquals(
			array(
				'statuscode' => 200,
				'detail' => '這是測試檔案'
			),
			$res->getJSONData(TEST_DIR.'/Auth/Model/UnitTest/mypostdata.dat', 'POST')
		);
	}
	public function testgetJSONDataIsEmpty()
	{
		$res = \Auth\Model\PostHandler::getInstance();
		$this->assertEquals(
			array(),
			$res->getJSONData(null, 'POST')
		);
	}
	public function testgetJSONDataNotPost()
	{
		$res = \Auth\Model\PostHandler::getInstance();
		$this->assertEquals(
			null,
			$res->getJSONData()
		);
	}
}
