<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;
include_once FILTERS;
include_once HashClass;
include_once MySqlDB;

if ( isset($GLOBALS['Page_API_Error_Code']) )
    $GLOBALS['Page_API_Error_Code'] .= '.P5F1';
else
    $GLOBALS['Page_API_Error_Code'] = 'P5F1';
set_error_handler("Error_Handeler");


/*
	Return :
		return array(0, 'Empty');
        return array(0, 'Too Long');
        return array(-1, $MySql->Error, 'Deleting Client From DataBase', 'Check Data');
		return array(0, 'Deleted');

        return array(-1, $MySql->Error, 'Checking The Client Found Or Not','CheckData');
        return array(0, 'Not Found');

        return array(-1, $MySql->Error, 'Changing Client Data', 'CheckData');
        return array(0, 'Edited');

        return array(-1, $MySql->Error,'inserting Client Data into DataBase','Check Data');
        return array(0, 'Added');
*/
function CheckData(){
	$MySql = new MYSQLClass();
    $Hashing = new HashingClass();
    $FILTER = new FILTERSClass();

	if ( isset($_POST['DeleteButton']) )
		return DeleteClient();
	else if ( isset($_POST['EditButton']) )
		return EditClient();
	else if ( isset($_POST['NewButton']) )
		return NewClient();
}

/*
	Return :
		return array(0, 'Empty');
        return array(0, 'Too Long');

        return array(-1, $MySql->Error, 'Checking The Client Found Or Not','CheckData');
        return array(0, 'Not Found');

        return array(-1, $MySql->Error, 'Changing Client Data', 'CheckData');
        return array(0, 'Edited');
*/
function EditClient(){
	$MySql = new MYSQLClass();
    $Hashing = new HashingClass();
    $FILTER = new FILTERSClass();

// Check Client ID
    if ( ( $Result = $FILTER->FilterString($_POST['ID'], ID_Len) )[1] != 'OK' )
        return $Result;
    $GLOBALS['ID'] = $FILTER->FILTER_Result;

// Check if ID is Found Or NOT
    if ( ($Result = $MySql->isFound('SELECT name FROM clients WHERE id = ?',
			array(
				$GLOBALS['ID']
			))) == -1 )
    	return array(-1, $MySql->Error, 'Checking The Client Found Or Not','CheckData');
    else if ( $Result == 0 )
    	return array(0, 'Not Found');

// Check Client Name
    if ( ( $Result = $FILTER->FilterString($_POST['N'], Name_Len) )[1] != 'OK' )
        return $Result;
    $GLOBALS['N'] = $FILTER->FILTER_Result;

// Check Client Phone 1
    if ( ( $Result = $FILTER->FilterString($_POST['Ph1'], Phone_Len) )[1] != 'OK' )
        $GLOBALS['Ph1'] = 'No Phone';
    else
    	$GLOBALS['Ph1'] = $FILTER->FILTER_Result;

// Check Client Phone 2
    if ( ( $Result = $FILTER->FilterString($_POST['Ph2'], Phone_Len) )[1] != 'OK' )
        $GLOBALS['Ph2'] = 'No Phone';
    else
    	$GLOBALS['Ph2'] = $FILTER->FILTER_Result;

// Check Client Phone 3
    if ( ( $Result = $FILTER->FilterString($_POST['Ph3'], Phone_Len) )[1] != 'OK' )
        $GLOBALS['Ph3'] = 'No Phone';
    else
    	$GLOBALS['Ph3'] = $FILTER->FILTER_Result;

// Check Client Address
    if ( ( $Result = $FILTER->FilterString($_POST['Add'], Address_Len) )[1] != 'OK' )
        $GLOBALS['Add'] = 'No Address';
    else
    	$GLOBALS['Add'] = $FILTER->FILTER_Result;

// inserting into DataBase
    if ( $MySql->excute('UPDATE clients SET name = ?, phone1 = ?, phone2 = ?, '
    		.'phone3 = ?, address = ? WHERE id = ?',
    		array(
    			$Hashing->Hash_Clients($GLOBALS['N']),
    			$Hashing->Hash_Clients($GLOBALS['Ph1']),
    			$Hashing->Hash_Clients($GLOBALS['Ph2']),
    			$Hashing->Hash_Clients($GLOBALS['Ph3']),
    			$Hashing->Hash_Clients($GLOBALS['Add']),
    			$GLOBALS['ID']
    		)) == -1 )
    	return array(-1, $MySql->Error, 'Changing Client Data', 'CheckData');
    return array(0, 'Edited');
}

/*
	Return :
		return array(0, 'Empty');
        return array(0, 'Too Long');

        return array(-1, $MySql->Error, 'Deleting Client From DataBase', 'Check Data');
    	return array(0, 'Deleted');
*/
function DeleteClient(){
	$MySql = new MYSQLClass();
    $Hashing = new HashingClass();
    $FILTER = new FILTERSClass();

// Check Client Name
    if ( ( $Result = $FILTER->FilterString($_POST['N'], Name_Len) )[1] != 'OK' )
        return $Result;
    $GLOBALS['N'] = $FILTER->FILTER_Result;

    // Delete Client
    if ( $MySql->excute('DELETE FROM clients WHERE name = ?',
			array(
				$Hashing->Hash_Clients($GLOBALS['N'])
			)) == -1 )
    	return array(-1, $MySql->Error, 'Deleting Client From DataBase', 'Check Data');
    return array(0, 'Deleted');
}

/*
	Return :
		return array(0, 'Empty');
        return array(0, 'Too Long');

        return array(-1, $MySql->Error, 'inserting Client Data into DataBase','Check Data');
    	return array(0, 'Added');
*/
function NewClient(){
	$MySql = new MYSQLClass();
    $Hashing = new HashingClass();
    $FILTER = new FILTERSClass();

// Check Client Name
    if ( ( $Result = $FILTER->FilterString($_POST['N'], Name_Len) )[1] != 'OK' )
        return $Result;
    $GLOBALS['N'] = $FILTER->FILTER_Result;

// Check Client Phone 1
    if ( ( $Result = $FILTER->FilterString($_POST['Ph1'], Phone_Len) )[1] != 'OK' )
        $GLOBALS['Ph1'] = 'No Phone';
    else
    	$GLOBALS['Ph1'] = $FILTER->FILTER_Result;

// Check Client Phone 2
    if ( ( $Result = $FILTER->FilterString($_POST['Ph2'], Phone_Len) )[1] != 'OK' )
        $GLOBALS['Ph2'] = 'No Phone';
    else
    	$GLOBALS['Ph2'] = $FILTER->FILTER_Result;

// Check Client Phone 3
    if ( ( $Result = $FILTER->FilterString($_POST['Ph3'], Phone_Len) )[1] != 'OK' )
        $GLOBALS['Ph3'] = 'No Phone';
    else
    	$GLOBALS['Ph3'] = $FILTER->FILTER_Result;

// Check Client Address
    if ( ( $Result = $FILTER->FilterString($_POST['Add'], Address_Len) )[1] != 'OK' )
        $GLOBALS['Add'] = 'No Address';
    else
    	$GLOBALS['Add'] = $FILTER->FILTER_Result;

// insert into DataBase
    if ( $MySql->excute('INSERT INTO clients(name, phone1, phone2, phone3 ,address) '
    		.'VALUES(?, ?, ?, ?, ?)',
    		array(
    			$Hashing->Hash_Clients($GLOBALS['N']),
    			$Hashing->Hash_Clients($GLOBALS['Ph1']),
    			$Hashing->Hash_Clients($GLOBALS['Ph2']),
    			$Hashing->Hash_Clients($GLOBALS['Ph3']),
    			$Hashing->Hash_Clients($GLOBALS['Add'])
    		)) == -1 )
    	return array(-1, $MySql->Error, 'inserting Client Data into DataBase','Check Data');
    return array(0, 'Added');
}
?>