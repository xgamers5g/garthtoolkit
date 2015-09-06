<?php
class SessionHandlerUnitTest extends PHPUnit_Framework_TestCase{

	/**
	 * before標記代表在測試運行前會先執行的動作
 	 * @before
	 */
	public function setEnvironment(){

		$this->obj = \GarthFramework\Tools\SessionHandler::getInstance();

	}

	public function testget(){

		@session_start();
		$_SESSION['test'] = 'abc';
		$res = $this->obj->get('test');
		$this->assertEquals('abc', $res);

	}

	public function testset(){

		@session_start();
		$this->obj->set('test', 'abcd');
		$res = $_SESSION['test'];
		$this->assertEquals('abcd', $res);

	}

	public function testhas(){

		@session_start();
		$_SESSION['testhas'] = '';
		$res = $this->obj->has('testhas');
		$this->assertEquals(true, $res);

	}

	public function testdel(){

		@session_start();
		$_SESSION['testdel'] = 'asdf';
		$res = $this->obj->del('testdel');
		$this->assertEquals(false, array_key_exists('testdel', $_SESSION));

	}

	public function testdestroy(){

		@session_start();
		$_SESSION['testdestroy1'] = 'aa';
		$_SESSION['testdestroy2'] = 'bb';
		$_SESSION['testdestroy3'] = 'cc';
		$_SESSION['testdestroy4'] = 'dd';
		$this->obj->destroy();

		//理論上destroy在當頁並不會摧毀$_SESSION變數，所以還是會存在
		$this->assertEquals(true, array_key_exists('testdestroy1', $_SESSION));
		$this->assertEquals(true, array_key_exists('testdestroy2', $_SESSION));
		$this->assertEquals(true, array_key_exists('testdestroy3', $_SESSION));
		$this->assertEquals(true, array_key_exists('testdestroy4', $_SESSION));

	}

	public function testdestroyByForce(){

		@session_start();
		$_SESSION['testdestroy1'] = 'aa';
		$_SESSION['testdestroy2'] = 'bb';
		$_SESSION['testdestroy3'] = 'cc';
		$_SESSION['testdestroy4'] = 'dd';
		$this->obj->destroy(true);

		//傳入true代表啟動force,直接unset$_SESSION變數所有的key
		$this->assertEquals(false, array_key_exists('testdestroy1', $_SESSION));
		$this->assertEquals(false, array_key_exists('testdestroy2', $_SESSION));
		$this->assertEquals(false, array_key_exists('testdestroy3', $_SESSION));
		$this->assertEquals(false, array_key_exists('testdestroy4', $_SESSION));

	}
	
}
