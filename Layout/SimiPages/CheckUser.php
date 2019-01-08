<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';

$GLOBALS['Page_API_Error_Code'] = 'SP2';	// Only For Pages
set_error_handler("Error_Handeler");

if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_SERVER["HTTP_REFERER"]) && 
		isset($_POST['N']) ){
	
	include_once URL;
    if ( $URL_REFERER->GetURLPath() == -1 ){
        include_once ErrorPage;
        exit();
    }
    
    if ( $URL_REFERER->URLResult != Users ){
        include_once UnAuthurithedUser;
        exit();
    }
}
else{
	include_once UnAuthurithedUser;
	exit();
}

include_once FILTERS;
include_once JSON;

// Filter Name
if ( ( $Result = $FILTER->FilterString($_POST['N'], Name_Len) )[1] != 'OK' ){
	
	if ( $JSON->MakeJsonCheck( $Result ) == -1 ){
		echo json_encode( $JSON->Error );
		exit();
	}
	echo $JSON->JSONResult;
	exit();
}
$GLOBALS['N'] = $FILTER->FILTER_Result;

// Search For Name
if ( $JSON->MakeJsonCheck( CheckUser($GLOBALS['N']) ) == -1 ){
	echo json_encode( array('Result' => -1, 'Object' => $JSON->Error ) );
	exit();
}
echo $JSON->JSONResult;
exit();

function CheckUser($Name){
	include_once HashClass;
	include_once MySqlDB;
    
// Search in users
    if ( ($Result = $MySql->isFound( "SELECT name FROM users WHERE name = ?",
            array(
            	$Hashing->Hash_Users($GLOBALS['N'])
            ))) == -1 )
        return array(-1, $MySql->Error, "Check Name Found Or Not", 'Check User');
    else if ( $Result == 1 )
        return array(0, 'Found');

    return array(0, 'Not Found');
}
?>