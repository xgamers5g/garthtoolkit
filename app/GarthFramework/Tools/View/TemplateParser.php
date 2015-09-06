<?php
namespace GarthFramework\Tools\View;
/**
 * ptt借來用的純php樣版解析器
 */
class TemplateParser{

    private static $instance;

    private function __construct(){

    }

    public static function getInstance(){

        if(self::$instance){
            return self::$instance;
        }
        self::$instance = new self;

        return self::$instance;

    }

    public function view($source_page, $variable = '', $return = FALSE){

        $variable = $this->_check_array($variable);

        foreach($variable as $key => $value){
            $$key = $value;
        }
        
        if($return){
            ob_start();
                eval("?>".file_get_contents($source_page)."<?php");
                $buffer_tmp = ob_get_contents();
            ob_end_clean();
            return $buffer_tmp;
        }
        else{
            eval("?>".file_get_contents($source_page)."<?php");
        }
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
