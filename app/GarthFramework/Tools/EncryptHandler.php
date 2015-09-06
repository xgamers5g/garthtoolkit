<?php
namespace GarthFramework\Tools;

/**
 * 密碼加密工具(library)
 */

class EncryptHandler{

	private static $instance;

	public static function getInstance(){

		if(self::$instance instanceof \GarthFramework\Tools\EncryptHandler){
			return self::$instance;
		}
		self::$instance = new self;
		
		return self::$instance;
	}
	/**
	 * 多型
	 * 單傳入secret，回傳usersecret與salthash
	 * @param string $secret secret
	 * @return array() {
	 *      'salthash' => string//encrypt
	 *      'usersecret' => string//hash
	 * }
	 * 傳入 secret, salthash，回傳usersecret
	 * @param string $secret secret
	 * @param string $salthash salthash
	 * @return string $usersecret usersecret
	 *
	 * 加密儲存用述: 傳入密碼時，將會亂數產生salt,接著利用pbkdf2公式對密碼及salt進行sha256加密30000次。然後返回加密結果，以及base64過後的salt。
	 * secret及base64過後的salt應該被儲存起來。
	 *
	 * 比對用途： 取得base64過後的salt，利用pbkdf2將傳入的密碼及 base64解密的salt進行sha256加密30000次。
	 * 將此結果回傳後就可以用來跟資料庫中的密碼比對。
	 *
	 * 本類別不負責比對之工作，只負責加密。
	 */
	public function secretEncode($secret, $salthash = null){

		if (version_compare(phpversion(), '5.5', '>')) {

			//只給secret代表產生，新增時調用。
			if (!$secret) {
				return;
			}
			//有給salt直接回傳密碼，比對時調用。
			if ($salthash) {
				return hash_pbkdf2("sha256", $secret, base64_decode($salthash), 1000, 30);
			} else {
				$newsalt = substr(md5(uniqid(rand(), true)), 0, 6);
				return array(
					'salthash' => base64_encode($newsalt),
					'usersecret' => hash_pbkdf2("sha256", $secret, $newsalt, 1000, 30)
				);
			}
		}

		return 'please upgrade php to 5.5+';

	}

	/* 產生長度為password_len的字串，其中只包含a-zA-Z0-9 */
	public function generateHash($password_len = 10){

		//$password_len = 7;
		$password = '';
		// remove o,0,1,l
		$word = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ1234567890';
		$len = strlen($word);
		for ($i = 0; $i < $password_len; $i++) {
			$password .= $word[rand() % $len];
		}

		return $password;
	}

	public function __destruct(){

		self::$instance = null;

	}

}
