<?php
namespace GarthFramework\Tools;

/**
 * ToolSession
 *
 * 一支php Session的包覆類別
 *
 * 取經自Ronmi/Fruit Framework的Fruit\Session\PhpSession
 *
 * 舊版本的使用在初始化時容易與別的系統發生session_start()重覆執行的問題
 *
 * 所以在初始化時加入了判斷session_id存在與否的設定來避免這個Fatal Error的發生
 *
 * 再將之改寫為較符合singleton pattern的操作方式(個人喜好)
 *
 * 2014.09.25 Garth_Wang
 */

class SessionHandler
{
    private static $instance;
    private $destroied = false;
    private function __construct()
    {
        //nothing to do
    }
    public static function getInstance()
    {
        if (!session_id()) {
            @session_start();
        }

        if(self::$instance instanceof \GarthFramework\Tools\SessionHandler){

            return self::$instance;
        }
        self::$instance = new self;

        return self::$instance;
    }

    public function get($key)
    {
        if ($this->destroied === true) {
            return null;
        }
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    public function set($key, $val)
    {
        if ($this->destroied === true) {
            return ;
        }
        $_SESSION[$key] = $val;
    }

    public function has($key)
    {
        if ($this->destroied === true) {
            return ;
        }
        return isset($_SESSION[$key]);
    }

    public function del($key)
    {
        if ($this->destroied === true) {
            return ;
        }
        unset($_SESSION[$key]);
    }

    public function destroy($force = null)
    {
        if ($this->destroied === true) {
            return ;
        }
        $this->destroied = session_destroy();

        //強制當頁刪除SESSION變數內容
        if ($force) {
            foreach ($_SESSION as $key => $value) {
                unset($_SESSION[$key]);
            }
        }
    }
}