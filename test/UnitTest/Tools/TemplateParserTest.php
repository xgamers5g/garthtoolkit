<?php
Class TemplateParserTest extends PHPUnit_Framework_TestCase{

	/**
	 * before標記代表在測試運行前會先執行的動作
 	 * @before
	 */
	public function setEnvironment(){
		//
	}

	//藕合測試
	public function testView(){

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
		$assertData .= '123'."\n";
		$assertData .= '</body>'."\n";
		$assertData .= '</html>';

		$obj = \GarthFramework\Tools\View\TemplateParser::getInstance();
		$res = $obj->view(dirname(__FILE__).'/testTemplate.tpl', $templateData, true);
		//print_r($res);
		$this->assertEquals($assertData, $res);
	}

	//藕何測試

}
