<?php
/*
	-info
		php page  	=>  JSON.php
		init name 	=>  JSON
		class name 	=> 	JSONClass
		object name => 	JSON

	-Errors
		21 =>	JSON Error 	=>	Error in Converting To JSON
		22 =>	JSON Error 	=>	Object Indix Not Found
*/
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';

if ( isset($GLOBALS['Page_API_Error_Code']) )
    $GLOBALS['Page_API_Error_Code'] .= '.C6';
else
    $GLOBALS['Page_API_Error_Code'] = 'C6';
set_error_handler("Error_Handeler");

class JSONClass{

	// Error Array
	public $Error = array(
					'Error Location' 	=> '',
					'Error Code' 		=> '',
					'Error Message' 	=> '',
				);
	public $JSONResult = '';
	private function ErrorReturn($ErrorLocation, $ErrorCode, $ErrorMessage){
		$this->Error['Error Location'] = $ErrorLocation;
    	$this->Error['Error Code'] = $ErrorCode;
    	$this->Error['Error Message'] = $ErrorMessage;
    	return -1;
	}

	function MakeJsonCheck($Object){
		try{
			if ( !isset($Object[0]) || !isset($Object[1]) )
				return ErrorReturn('JSON Error', '9', 'Object Indix Not Found');
			
			if ( $Object[0] == -1 )
				return MakeErrorReturn();
			
			$JSON = array(
				'Result' => $Object[0],
				'Object' => $Object[1],
			);
			$this->JSONResult = json_encode($JSON);
			return 1;
		}
		catch (Exception $e){
			return ErrorReturn('JSON Error', '10', 'Error in Converting To JSON');
		}
	}

	function MakeJsonReturn($Object){
		try{
			if ( !isset($Object[0]) || !isset($Object[1]) || !isset($Object[2]) ||
				 !isset($Object[3]) )
				return ErrorReturn('JSON Error', '9', 'Object Indix Not Found');

			$JSON = array(
				'Result' => $Object[0],
				'Object' => $Object[1],
				'Doing' => $Object[2],
				'Where' => $Object[3],
			);
			$this->JSONResult = json_encode($JSON);
			return 1;
		}
		catch (Exception $e){
			return ErrorReturn('JSON Error', '12', 'Error in Converting To JSON');
		}
	}
}

$JSON = new JSONClass();
?>