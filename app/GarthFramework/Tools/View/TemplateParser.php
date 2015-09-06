<?php
namespace GarthFramework\Tools\View;
/**
 * 導入twig
 * getInstance取view物件
 * method view使用twig輸出樣板
 */
class TemplateParser{

    private static $instance;

    private function __construct(){

    }

    public static function getInstance(){

        /* 定義twig檔案讀取器 */
        
        if(self::$instance){
            return self::$instance;
        }
        self::$instance = new self;

        return self::$instance;

    }

    public function view($fileLocation, $variable = '', $return = FALSE){

        $variable = $this->_check_array($variable);
         
        $twig = new \Twig_Environment(
            (new \Twig_Loader_Filesystem(dirname($fileLocation)))
        );

        return $twig->render(
            basename($fileLocation),
            $variable
        );
        
    }

    private function _check_array($variable){
        if($variable == null)
            return array();
        if(!is_array($variable)){
            echo 'This variable is not array';
            exit();
        }
        return $variable;
    }

}
