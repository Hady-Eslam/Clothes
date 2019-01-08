<?php
/*	
	-info
		php page  	=>  Hashing.php
		init name 	=>  HashClass
		class name 	=> 	HashingClass
		object name => 	Hashing

	-Errors
		7	=>	Hashing $Location Error 	=> Length is Short, Can't Hashing
*/
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';

if ( isset($GLOBALS['Page_API_Error_Code']) )
    $GLOBALS['Page_API_Error_Code'] .= '.C2';
else
    $GLOBALS['Page_API_Error_Code'] = 'C2';
set_error_handler("Error_Handeler");

class HashingClass{

	public $Error = array(
					'Error Location' 	=> '',
					'Error Code' 		=> '',
					'Error Message' 	=> '',
				);

	public $HashedText = '';

	private $SESSION_HASH_LEN = 2;
	private $USERS_HASH_LEN = 4;
	private $CLIENTS_HASH_LEN = 6;
	private $CUSTOMERS_HASH_LEN = 8;
	private $TYPE_HASH_LEN = 10;
	private $BILL_HASH_LEN = 12;

	private function ErrorReturn($ErrorLocation, $ErrorCode, $ErrorMessage){
		$this->Error['Error Location'] = $ErrorLocation;
    	$this->Error['Error Code'] = $ErrorCode;
    	$this->Error['Error Message'] = $ErrorMessage;
    	return -1;
	}

/*
	Hashing Area
*/
	function Hash_Session($Session){
		return 'MY'.$Session;
	}

	function Hash_Users($Users){
		return 'MYNA'.$Users;
	}

	function Hash_Clients($Clients){
		return 'MYNAME'.$Clients;
	}

	function Hash_Customers($Customers){
		return 'MYNAMEHA'.$Customers;
	}

	function Hash_Type($Type){
		return 'MYNAMEHADY'.$Type;
	}

	function Hash_BILL($Bill){
		return 'MYNAMEHADYES'.$Bill;
	}

/*
	Get Hashed Area
*/
	function Get_Hashed_Session($Session){
		if ( $this->CheckLength($Session, $this->SESSION_HASH_LEN, 'Session') != 1 )
			return -1;
		$this->HashedText = substr($Session, 2);
		return 1;
	}

	function Get_Hashed_Users($Users){
		if ( $this->CheckLength($Users, $this->USERS_HASH_LEN, 'Users') != 1 )
			return -1;
		$this->HashedText = substr($Users, 4);
		return 1;
	}

	function Get_Hashed_Clients($Clients){
		if ( $this->CheckLength($Clients, $this->CLIENTS_HASH_LEN, 'Clients') != 1 )
			return -1;
		$this->HashedText = substr($Clients, 6);
		return 1;
	}

	function Get_Hashed_Customers($Customers){
		if ( $this->CheckLength($Customers, $this->CUSTOMERS_HASH_LEN, 'Customers') != 1 )
			return -1;
		$this->HashedText = substr($Customers, 8);
		return 1;
	}

	function Get_Hashed_Type($Type){
		if ( $this->CheckLength($Type, $this->TYPE_HASH_LEN, 'Type') != 1 )
			return -1;
		$this->HashedText = substr($Type, 10);
		return 1;
	}

	function Get_Hashed_Bill($Bill){
		if ( $this->CheckLength($Bill, $this->BILL_HASH_LEN, 'Bill') != 1 )
			return -1;
		$this->HashedText = substr($Type, 12);
		return 1;
	}

/*
	important Functions
*/
	function CheckLength($String, $LEN, $Location){
		if ( strlen($String)<$LEN ){
			$this->ErrorReturn('Hashing '.$Location.' Error', '7', 
								"Length is Short, Can't Hashing");
			return 0;
		}
		return 1;
	}
}
$Hashing = new HashingClass();
?>