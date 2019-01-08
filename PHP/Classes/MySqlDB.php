<?php
/* 	
	-some important info

	-info
		php page  	=>  MySqlDB.php
		init name 	=>  MySqlDB
		class name 	=> 	MYSQLClass
		object name => 	MySql

	-users 				Password 			What is Doing
		root  		=> 	''

	-Errors
		1 	=>	My Sql Error 	=>	Access Denied
		2	=>	My Sql Error 	=>	Error in excuting Query
		3	=>	My Sql Error 	=>	Error in Counting Query
		4	=>	My Sql Error 	=>	Error in Fetching One Row
		5	=>	My Sql Error 	=>	Error in Fetching All Rows
		6	=>	My Sql Error 	=>	Error in Searching For Column
*/
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';

if ( isset($GLOBALS['Page_API_Error_Code']) )
    $GLOBALS['Page_API_Error_Code'] .= '.C1';
else
    $GLOBALS['Page_API_Error_Code'] = 'C1';
set_error_handler("Error_Handeler");

class MYSQLClass{
	
	public $SQL;
	public $pdo;
	public $Fetched_Data;

	private $Password = '';
	// Error Array
	public $Error = array(
					'Error Location' 	=> '',
					'Error Code' 		=> '',
					'Error Message' 	=> '',
				);

	function __construct(){
		try{
			$this->pdo = new PDO("mysql:host=localhost;dbname=clothes", 'root', '');
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(Exception $e){
			$this->ErrorReturn('My Sql Error', '1', "Access Denied");
	    }
	}

	private function ErrorReturn($ErrorLocation, $ErrorCode, $ErrorMessage){
		$this->Error['Error Location'] = $ErrorLocation;
    	$this->Error['Error Code'] = $ErrorCode;
    	$this->Error['Error Message'] = $ErrorMessage;
    	return -1;
	}

	public function excute($Query, $Array){
		if ( $this->Error['Error Code'] == '1' )
			return -1;
		try{
			$this->SQL = $this->pdo->prepare($Query);
	    	$this->SQL->execute($Array);
	        return 1;
	    }
	    catch(Exception $e){
	    	return $this->ErrorReturn('My Sql Error', '2', 'Error in excuting Query');
	    }
	}

	public function GetRowCount($Query){
		if ( $this->Error['Error Code'] == '1' )
			return -1;
		try{
			return $this->pdo->query($Query)->fetchColumn();
		}
		catch(Exception $e){
	    	return $this->ErrorReturn('My Sql Error', '3', 'Error in Counting Query');
	    }
	}

	public function FetchOneRow($Query, $Array){
		if ( $this->Error['Error Code'] == '1' )
			return -1;
		try{
			if ( $this->excute($Query, $Array) == -1 )
				return -1;

			$this->Fetched_Data = $this->SQL->fetch(PDO::FETCH_ASSOC);
			if ( empty($this->Fetched_Data) )
				return 0;
			return 1;
		}
		catch(Exception $e){
			return $this->ErrorReturn('My Sql Error', '4', 'Error in Fetching One Row');
		}
	}

	public function FetchAllRows($Query, $Array){
		if ( $this->Error['Error Code'] == '1' )
			return -1;

		try{
			if ( $this->excute($Query, $Array) == -1 )
				return -1;

			$this->Fetched_Data = $this->SQL->fetchAll(PDO::FETCH_ASSOC);
			if ( empty($this->Fetched_Data) )
				return 0;
			return 1;
		}
		catch(Exception $e){
			return $this->ErrorReturn('My Sql Error', '5', 'Error in Fetching All Rows');
		}
	}

	public function isFound($Query, $Array){
		if ( $this->Error['Error Code'] == '1' )
			return -1;

		try{
			if ( $this->excute($Query, $Array) == -1 )
				return -1;

			while ( $Fetch = $this->SQL->fetch(PDO::FETCH_ASSOC) )
				return 1;
			return 0;
		}
		catch(Exception $e){
			return $this->ErrorReturn('My Sql Error', '6', 'Error in Searching For Column');
		}
	}
}
$MySql = new MYSQLClass();
?>