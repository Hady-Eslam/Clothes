<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;
include_once FILTERS;
include_once HashClass;
include_once MySqlDB;

if ( isset($GLOBALS['Page_API_Error_Code']) )
    $GLOBALS['Page_API_Error_Code'] .= '.P1F1';
else
    $GLOBALS['Page_API_Error_Code'] = 'P1F1';
set_error_handler("Error_Handeler");

/*
	- Return : 
        return array(0, 'Empty');
        return array(0, 'Too Long');
        return array(-1, $MySql->Error, 'Check The Name And Password', 'Check Data');
        return array(0, 'Wrong Password');
        return array(0, 'Not Found');

        return array(-1, $Hashing->Error, 'Getting Hashed Name From DataBase',
                                'Open Session');
        return array(-1, $Hashing->Error, 'Getting Hashed phone From DataBase',
                                'Open Session');
        return array(0, 'Done');
*/
function CheckData(){
	$MySql = new MYSQLClass();
    $Hashing = new HashingClass();
    $FILTER = new FILTERSClass();

// Check Name
    if ( ( $Result = $FILTER->FilterString($_POST['N'], Name_Len) )[1] != 'OK' )
        return $Result;
    $GLOBALS['N'] = $FILTER->FILTER_Result;

    if ( ( $Result = $MySql->isFound('SELECT name FROM users WHERE name = ?',
				array(
					$Hashing->Hash_Users($GLOBALS['N'])
				))) == -1 )
    	return array(-1, $MySql->Error, 'Checking Name is Found Or Not', 'Check Data');
    else if ( $Result == 0 )
    	return array(0, 'Not Found');

// Check Password
    if ( ( $Result = $FILTER->FilterString($_POST['P'], Password_Len) )[1] != 'OK' )
        return $Result;
    $GLOBALS['P'] = $FILTER->FILTER_Result;

    if ( ($Result = $MySql->
    			FetchOneRow('SELECT * FROM users WHERE name = ? AND password = ?',
			array(
				$Hashing->Hash_Users($GLOBALS['N']),
				$Hashing->Hash_Users($GLOBALS['P'])
			))) == -1 )
    	return array(-1, $MySql->Error, 'Check The Name And Password', 'Check Data');
    else if ( $Result == 0 )
    	return array(0, 'Wrong Password');

    return OpenSession($MySql->Fetched_Data);
}

/*
	Return :
		return array(-1, $Hashing->Error, 'Getting Hashed Name From DataBase',
                                'Open Session');
        return array(-1, $Hashing->Error, 'Getting Hashed phone From DataBase',
                                'Open Session');
        return array(0, 'Done');                  
*/
function OpenSession($Data){
    $Hashing = new HashingClass();

// Get Hashed Name
    if ( $Hashing->Get_Hashed_Users($Data['name']) == -1 )
        return array(-1, $Hashing->Error, 'Getting Hashed Name From DataBase',
                                'Open Session');
    $_SESSION['Name'] = $Hashing->HashedText;

// Get Hashed Phone
    if ( $Hashing->Get_Hashed_Users($Data['phone']) == -1 )
        return array(-1, $Hashing->Error, 'Getting Hashed phone From DataBase',
                                'Open Session');
    $_SESSION['Phone'] = $Hashing->HashedText;
    
    $_SESSION['Status'] = $Data['status'];
    $_SESSION['SignUPDate'] = $Data['sign_up_date'];

    return array(0, 'Done');
}
?>