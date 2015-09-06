<?php
namespace GarthFramework\Tools\Database;

use \PDO;

class DB extends PDO{
	/**
	* 
	* APIS:
	* 
	* 1.  __construct(array $connection,boolean $debug) => $connection 於物件具預設值,$debug 為真時會自動列印錯誤
	* 
	* 2.  query(string $sql,array $param default empty) => 執行 SQL 語法,如代入 $param 則自動替代 prepare 變數
	*      將傳回 $stmt 物件
	* 
	* 3.  get_result(string or object $sql,$param default empty,int $rows) => 取得結果集
	*      將傳回結果集陣列,$rows=1 時將傳回一維陣列
	* 
	* 4.  insert(array $data,string $tb) => 寫入資料
	*      寫入之陣列為 key[欄位]=>value[值] 之聯想陣列,
	*      支援一維(單行)、二維(多行)陣列寫入,
	*      執行成功傳回 true,反之 false
	* 
	* 5.  update(array $data,array or string $where,string $tb) => 執行 Update 語法
	*      陣列使用上均為 key[欄位]=>value[值] 之聯想陣列,
	*      $where 亦可直接傳入字串 ex. $where="WHERE id='1'";
	*      執行成功傳回 true,反之 false
	* 
	* 6.  delete(array or string $where,string $tb) => 執行 Delete 語法
	*      $where 亦可直接傳入字串 ex. $where="WHERE id='1'";
	*      執行成功傳回 true,反之 false
	* 
	* 7.  num_rows(object $res default null) => 傳回查詢或變更影響之列數
	*      ex-1. $res=$db->query($sql,$param);
	*            $num_rows=$db->num_rows($res);
	*      ex-2. $num_rows=$db->num_rows();
	*      例一為查詢指定之 object 影響列數
	*      例二為查詢前一次執行之影響列數
	* 
	* 8.  insert_id() => 傳回前一次寫入資料表之自動編號欄位值
	* 
	* 9.  begin_tran() => 啟動交易功能
	* 
	* 10. commit() => 將啟動交易功能後的變更生效
	* 
	* 11. rollback() => 將啟動交易功能後的變更無效化
	* 
	*/
	var $conn,$stmt,$error,$debug;
	var $connection=array(
		'host'=>'localhost',
		'dbname'=>'test',
		'usr'=>'root',
		'pwd'=>'',
		'charset'=>'utf8'
	);

	//建構子
	public function __construct($connection=null,$debug=false){
		if($connection!=null){
			$this->connection['host']=isset($connection['host'])?$connection['host']:$this->connection['host'];
			$this->connection['dbname']=isset($connection['dbname'])?$connection['dbname']:$this->connection['dbname'];
			$this->connection['usr']=isset($connection['usr'])?$connection['usr']:$this->connection['usr'];
			$this->connection['pwd']=isset($connection['pwd'])?$connection['pwd']:$this->connection['pwd'];
			$this->connection['charset']=isset($connection['charset'])?$connection['charset']:$this->connection['charset'];
		}
		$this->debug=$debug;
		$connStr='mysql:host='.$this->connection['host'].';dbname='.$this->connection['dbname'];
		$connOpt=array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$this->connection['charset']);
		try{
			$this->conn=new PDO($connStr,$this->connection['usr'],$this->connection['pwd'],$connOpt);
		}
		catch(PDOException $e){
			$this->error=$e->getMessage();echo $this->error;
			if($this->debug){
				echo $this->error.'<br />';
			}
		}
		$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
	}

	//執行查詢
	public function query($sql,$param=array()){
		$this->stmt=$this->conn->prepare($sql);
		$errorInfo=$this->conn->errorInfo();
		if(current($errorInfo)!=0){
			$this->error=@end($errorInfo);
			if($this->debug){
				echo $this->error.'<br />';
			}
			return false;
		}
		else{
			$this->stmt->execute($param);
			return $this->stmt;
		}
	}

	//取得結果集
	public function get_result($sql,$param=array(),$rows=null){
		if(is_object($sql)){
			$data=$sql->fetchAll(PDO::FETCH_ASSOC);
		}
		else{
			if($res=$this->query($sql,$param)){
				$data=$res->fetchAll(PDO::FETCH_ASSOC);
			}
			else{
				$data=false;
			}
		}
		if($rows!=null){
			if($rows==1){
				$data=$data[0];
			}
			else{
				$loop=0;
				$result=array();
				foreach($data as $k=>$v){
					if($loop<$rows){
						$result[$loop]=$v;
					}
					$loop++;
				}
				$data=$result;
			}
		}
		return $data;
	}

	//新增
	public function insert($data,$tb){
		$multiple=false;
		foreach($data as $k=>$v){
			if(is_array($v)){
				$multiple=true;
				break;
			}
		}
		if($multiple){//多維
			$multipleIns=true;
			foreach($data as $k=>$v){
				$loop=0;
				$cols='';
				$vals='';
				$param=array();
				foreach($v as $kk=>$vv){
					$cols.=$loop>0?',':'';
					$vals.=$loop>0?',':'';
					$param[]=$vv;
					$cols.="`$kk`";
					$vals.='?';
					$loop++;
				}
				$multipleIns=$this->query("INSERT INTO $tb($cols) VALUES($vals);",$param)?$multipleIns:false;
			}
			return $multipleIns;
		}
		else{//單維
			$loop=0;
			$cols='';
			$vals='';
			$param=array();
			foreach($data as $k=>$v){
				$cols.=$loop>0?',':'';
				$vals.=$loop>0?',':'';
				$param[]=$v;
				$cols.="`$k`";
				$vals.='?';
				$loop++;
			}
			return $this->query("INSERT INTO $tb($cols) VALUES($vals);",$param);
		}
	}

	//修改
	public function update($data,$where,$tb){
		$param=array();
		$data_stm='';
		$where_stm='';
		$loop=0;
		foreach($data as $k=>$v){
			$param[]=$v;
			$data_stm.=$loop>0?',':'';
			$data_stm.="`$k`=?";
			$loop++;
		}
		if(is_array($where)){
			$where_stm=' WHERE ';
			$loop=0;
			foreach($where as $k=>$v){
				$param[]=$v;
				$where_stm.=$loop>0?' AND ':'';
				$where_stm.="`$k`=?";
				$loop++;
			}
		}
		else{
			$where_stm=$where;
		}
		return $this->query("UPDATE $tb SET $data_stm $where_stm;",$param);
	}

	//刪除
	public function delete($where,$tb){
		$param=array();
		$where_stm='';
		if(is_array($where)){
			$where_stm=' WHERE ';
			$loop=0;
			foreach($where as $k=>$v){
				$param[]=$v;
				$where_stm.=$loop>0?' AND ':'';
				$where_stm.="`$k`=?";
				$loop++;
			}
		}
		else{
			$where_stm=$where;
		}
		return $this->query("DELETE FROM $tb $where_stm;",$param);
	}

	//取得查詢或影響列數
	public function num_rows($res=null){
		$res=$res==null?$this->stmt:$res;
		return $res->rowCount();
	}

	//取得最後寫入的自動編號 ID
	public function insert_id(){
		return $this->conn->lastInsertId();
	}

	//Begin Tran
	public function begin_tran(){
		return $this->conn->beginTransaction();
	}

	//Commit
	public function commit(){
		return $this->conn->commit();
	}

	//RollBack
	public function rollback(){
		return $this->conn->rollBack();
	}

}
?>
