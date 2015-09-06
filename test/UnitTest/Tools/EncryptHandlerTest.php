<?php
class EncryptHandlerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * before標記代表在測試運行前會先執行的動作
	 * @before
	 */
	public function setEnvironment(){

		$this->secret = '12345';
		$this->salt = 'YmVkZjAy';
		$this->obj = \GarthFramework\Tools\EncryptHandler::getInstance();

	}

	public function testgetInstance()
	{
		$this->assertTrue($this->obj instanceof \GarthFramework\Tools\EncryptHandler);
	}

	public function testsecretEncode()
	{
		if (version_compare(phpversion(), '5.5', '>')) {
			$this->assertEquals(null, $this->obj->secretEncode(null));

			for ($i=0; $i<300; $i++) {
				$this->assertNotEquals(
					array(
						'salthash' => 'YmVkZjAy',
						'usersecret' => 'f4ad1dc858725af4133f2e17159979'
					),
					$this->obj->secretEncode($this->secret),
					'nonono'
				);
			}

			$this->assertEquals('f4ad1dc858725af4133f2e17159979', $this->obj->secretEncode($this->secret, $this->salt));

		}else{
			echo 'please upgrade php to 5.5+(EncryptHandlerTest::secretEncode)';
			$this->assertEquals(
				'please upgrade php to 5.5+',
				$this->obj->secretEncode($this->secret, $this->salt)
			);
		}

	}

}
