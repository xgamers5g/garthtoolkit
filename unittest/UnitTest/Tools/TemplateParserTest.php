<?php
Class TemplateParserTest extends PHPUnit_Framework_TestCase{

	/**
	 * before標記代表在測試運行前會先執行的動作
 	 * @before
	 */
	public function setEnvironment(){

		//定義亂數產生器，phpunit環境不由php機制定義屬性，應使用phpunit的官方protocal。
		//$this->db = $this->getMock('\Tools\MockDB', array(), array(), '', false, true, true);

		$this->dynamicAddConfig = array(
			array(
				'key' => 'community',
				'value' => array(
						'ocs' => array(
						'type' => 'tool',
						'field' => array('body')
					),
						'analytics' => array(
						'type' => 'tool',
						'filed' => array('body')
					),
						'qq' => array(
						'type' => 'app',
						'field' => array('appid', 'meta')
					),
						'rr' => array(
						'type' => 'app',
						'field' => array('appid', 'meta')
					),
						'wb' => array(
						'type' => 'app',
						'field' => array('appid', 'meta')
					),
						'wx' => array(
						'type' => 'app',
						'field' => array('appid', 'appsecret')
					)
				)
			),
			array(
				'key' => 'maxmEnv',
				'value' => array(
					'title' => 'CustomerAdminTesting',
					'defaultjsp' => 'testdefaultjsp',
					'editadminstatus' => 'update',
					'adminBar' => 'abcde',
					'customerID' => '12345'
				)
			)
		);
	}

	//藕合測試
	public function testView(){

		$db = $this->getMock('\Tools\MockDB', array(), array(), '', false, true, true);

		$templateData = array(
			'title'	=> 'hi',
			'body'	=> '123'
		);
		
		$assertData = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
		$assertData .= '<html xmlns="http://www.w3.org/1999/xhtml">'."\n";
		$assertData .= '<head>'."\n";
		$assertData .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'."\n";
		$assertData .= '<meta http-equiv="Content-Language" content="zh-tw" />'."\n";
		$assertData .= '<title>hi</title>'."\n";
		$assertData .= '</head>'."\n";
		$assertData .= '<body>'."\n";
		$assertData .= '123';
		$assertData .= '</body>'."\n";
		$assertData .= '</html>'."\n";
		$assertData .= '<?php';

		$core = \CustomerAdmin\System\Core::getInstance($db, null, $this->dynamicAddConfig);
		$view = $core->getTemplateParser();
		$res = $view->view(dirname(__FILE__).'/test.php', $templateData, true);
		$this->assertEquals($assertData, $res);
	}

	//藕何測試

}
