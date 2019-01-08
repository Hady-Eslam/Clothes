<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';

$GLOBALS['Page_API_Error_Code'] = 'SP3';
set_error_handler("Error_Handeler");

if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_SERVER["HTTP_REFERER"]) && 
		isset($_POST['ID']) && isset($_POST['Status']) ){
	
	include_once URL;
    if ( $URL_REFERER->GetURLPath() == -1 ){
        include_once ErrorPage;
        exit();
    }
    
    if ( $URL_REFERER->URLResult != Bill && $URL_REFERER->URLResult != Purchases ){
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

// Filter ID
if ( ( $Result = $FILTER->FilterString($_POST['ID'], ID_Len) )[1] != 'OK' ){
	
	if ( $JSON->MakeJsonCheck( $Result ) == -1 ){
		echo json_encode( $JSON->Error );
		exit();
	}
	echo $JSON->JSONResult;
	exit();
}
$GLOBALS['ID'] = $FILTER->FILTER_Result;

// Filter Status
if ( ( $Result = $FILTER->FilterString($_POST['Status'], 1) )[1] != 'OK' ){
    
    if ( $JSON->MakeJsonCheck( $Result ) == -1 ){
        echo json_encode( $JSON->Error );
        exit();
    }
    echo $JSON->JSONResult;
    exit();
}
if ( $FILTER->FILTER_Result != '1' && $FILTER->FILTER_Result != '2'){
    if ( $JSON->MakeJsonCheck( array(0, 'Not in Range') ) == -1 ){
        echo json_encode( $JSON->Error );
        exit();
    }
    echo $JSON->JSONResult;
    exit();
}
$GLOBALS['Status'] = $FILTER->FILTER_Result;

// Search For ID
if ( $JSON->MakeJsonCheck( SearchType($GLOBALS['ID']) ) == -1 ){
	echo json_encode( array('Result' => -1, 'Object' => $JSON->Error ) );
	exit();
}
echo $JSON->JSONResult;
exit();

/*
	Return :
		return array(-1, $MySql->Error, "Getting Type Data From DataBase", 'Get Type Count');
        return array(0, 'Not Found');
        return array(-1, $Hashing->Error, 'Getting Type Quantity in Store Heshed',
                                    'in Get Type Count');
        return array(-1, $Hashing->Error, 'Getting Type WholeSale Price Heshed',
                                    'in Get Type Count');
    	return array(0, array( $Quantity, $Hashing->HashedText ) );

        return array(-1, $Hashing->Error, 'Getting Type Name Heshed',
                                    'in Get Type Count');
        return array(-1, $Hashing->Error, 'Getting Type Retail Price Heshed',
                                    'in Get Type Count');
        return array(-1, $Hashing->Error, 'Getting Type Retail Discount Heshed',
                                    'in Get Type Count');
        return array(0,
        array(
            $Quantity_in_Store,
            $WholeSale_Price,
            $Name,
            $Retail_Price,
            $Retail_Discount
        ));
*/
function SearchType($ID){
	include_once HashClass;
	include_once MySqlDB;
    
// Search in users
    if ( ($Result = $MySql->FetchOneRow( "SELECT * FROM types WHERE id = ?",
            array(
            	$GLOBALS['ID']
            ))) == -1 )
        return array(-1, $MySql->Error, "Getting Type Data From DataBase",
                                    'Get Type Count');
    else if ( $Result == 0 )
        return array(0, 'Not Found');

// Get Hashed Quantity in Store
    $Quantity_in_Store =  $MySql->Fetched_Data['quantity_in_store'];

// Get Hashed WholeSale Price
    $WholeSale_Price = $MySql->Fetched_Data['wholesale_price'];

    if ( $GLOBALS['Status'] == '1' )
        return array(0, array( $Quantity_in_Store, $WholeSale_Price ) );

// Get Hashed Name
    if ( $Hashing->Get_Hashed_Type($MySql->Fetched_Data['name']) == -1 )
        return array(-1, $Hashing->Error, 'Getting Type Name Heshed',
                                    'in Get Type Count');
    $Name =  $Hashing->HashedText;

// Get Retail Price Hashed
    $Retail_Price = $MySql->Fetched_Data['retail_price'];

// Get Discount Hashed
    $Retail_Discount = $MySql->Fetched_Data['retail_discount'];

    return array(0,
        array(
            $Quantity_in_Store,
            $WholeSale_Price,
            $Name,
            $Retail_Price,
            $Retail_Discount
        ));
}
?>