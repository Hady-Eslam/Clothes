<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;

$GLOBALS['Page_API_Error_Code'] = 'SP4';
set_error_handler("Error_Handeler");

if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_SERVER["HTTP_REFERER"]) && 
	 isset($_POST['Name']) && isset($_POST['Note']) && isset($_POST['Buying']) &&
	 isset($_POST['Discount']) && isset($_POST['AllDiscount']) && isset($_POST['Payied']) &&
	 isset($_POST['Status'])
){
	
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

include_once JSON;
include_once FILTERS;
include_once HashClass;
include_once MySqlDB;

// Check Data
if ( $JSON->MakeJsonCheck( CheckData() ) == -1 ){
	echo json_encode( array('Result' => -1, 'Object' => $JSON->Error ) );
	exit();
}
echo $JSON->JSONResult;
exit();

/*
	Return :
		return array(0, 'Empty');
        return array(0, 'Too Long');
        return array(0, 'Not Number');
        return array(0, 'Non Positive Value');
        return array(0, 'Negative Value');
        return array(0, 'Not in Range');
        return array(0, 'You Must Put Values');
        return array(-1, $MySql->Error, 'in Fetching Type Data', 'Check Data');
		return array(0, 'Type Not Found');
		return array(0, 'Not Enough Quantity');
		return array(-1, $MySql->Error, 'Getting Bill id', 'Check Data');
		return array(-1, $MySql->Error, 'inserting Bill into DataBase', 'Check Data');
		return array(-1, $MySql->Error, 'inserting Bill Data into DataBase',
										'Check Data');
		return array(0, 'Done');
*/
function CheckData(){
	$FILTER = new FILTERSClass();
	$Hashing = new HashingClass();
	$MySql = new MYSQLClass();

// Check Status
	if ( ( $Result = $FILTER->FilterString($_POST['Status'], 2) )[1] != 'OK' )
	    return $Result;
	$GLOBALS['Status'] = $FILTER->FILTER_Result;
	if ( $GLOBALS['Status'] != '1' && $GLOBALS['Status'] != '2' )
		return array(0, 'Not Authurized');

// Check Customer Name
	if ( ( $Result = $FILTER->FilterString($_POST['Name'], Name_Len) )[1] != 'OK' )
	    return $Result;
	$GLOBALS['Name'] = $FILTER->FILTER_Result;

// Check Type Of Buying
	if ( $_POST['Buying'] == 'Deferred' )
		$GLOBALS['Buying'] = 2;
	else
		$GLOBALS['Buying'] = 1;

// Check Note
	if ( ( $Result = $FILTER->FilterString($_POST['Note'], Note_Len) )[1] == 'Too Long' )
	    return $Result;
	else
		$GLOBALS['Note'] = $FILTER->FILTER_Result;

// Check Discount in Pound
	if ( ( $Result = $FILTER->FilterNumber($_POST['Discount'], Money_Len, 2) )[1] != 'OK' ){
	    if ( $Result[1] == 'Empty' )
	    	$GLOBALS['Discount'] = 0;
	    else
	    	return $Result;
	}
	else
		$GLOBALS['Discount'] = $FILTER->FILTER_Result;

// Check Discount in Percent
	if ( ( $Result = $FILTER->FilterNumber($_POST['AllDiscount'], Money_Len, 2) )[1] 
								!= 'OK' ){
	    if ( $Result[1] == 'Empty' )
	    	$GLOBALS['AllDiscount'] = 0;
	    else
	    	return $Result;
	}
	else if ( $FILTER->FILTER_Result > 100 || $FILTER->FILTER_Result < 0 )
		return array(0, 'Not in Range');
	else
		$GLOBALS['AllDiscount'] = $FILTER->FILTER_Result;

// Check Payied
	if ( ( $Result = $FILTER->FilterNumber($_POST['Payied'], Money_Len, 2) )[1] 
								!= 'OK' ){
	    if ( $Result[1] == 'Empty' )
	    	$GLOBALS['Payied'] = 0;
	    else
	    	return $Result;
	}
	else
		$GLOBALS['Payied'] = $FILTER->FILTER_Result;

// Check Type
	for ($i = 1; $i <= 15; $i++){
		if ( isset($_POST['TypeID'.$i]) ){
			if ( !empty($_POST['TypeID'.$i]) ){

			// Check Type id
			    if ( ( $Result = $FILTER->FilterString($_POST['TypeID'.$i], ID_Len) )[1] 
			    					!= 'OK' )
			        return $Result;
			    $GLOBALS['TypeID'.$i] = $FILTER->FILTER_Result;

			// Check Type Quantity
			    if ( ( $Result = $FILTER->FilterNumber($_POST['TypeQuantity'.$i], 
			    			Money_Len, 1) )[1] != 'OK' ){
			    	if ( $Result[1] == 'Empty' )
			    		$GLOBALS['TypeQuantity'.$i] = 1;
			    	else
			    		return $Result;
			    }
			    else
			    	$GLOBALS['TypeQuantity'.$i] = $FILTER->FILTER_Result;

			// Check Type Price
			    if ( ( $Result = $FILTER->FilterNumber($_POST['TypePrice'.$i], 
			    			Money_Len, 2) )[1] != 'OK' ){
			    	if ( $Result[1] == 'Empty' )
			    		$GLOBALS['TypePrice'.$i] = 0;
			    	else
			    		return $Result;
			    }
			    else
			    	$GLOBALS['TypePrice'.$i] = $FILTER->FILTER_Result;

			// Check Type Discount
			    if ( ( $Result = $FILTER->FilterNumber($_POST['TypeDiscount'.$i], 
			    			Money_Len, 2) )[1] != 'OK' ){
			    	if ( $Result[1] == 'Empty' )
			    		$GLOBALS['TypeDiscount'.$i] = 0;
			    	else
			    		return $Result;
			    }
			    else if ( $FILTER->FILTER_Result > 100 || $FILTER->FILTER_Result < 0 )
					return array(0, 'Not in Range');
			    else
			    	$GLOBALS['TypeDiscount'.$i] = $FILTER->FILTER_Result;

			 // Check Type Note
			    if ( ( $Result = $FILTER->FilterString($_POST['TypeNote'.$i], Note_Len) )[1]
			    					== 'Too Long' )
			        return $Result;
			    else if ( $Result == 'Empty' )
			    	$GLOBALS['TypeNote'.$i] = '';
			    else
			    	$GLOBALS['TypeNote'.$i] = $FILTER->FILTER_Result;

			    $Found = true;
				$GLOBALS['Number'.$i] = true;
			}
		}
	}

	if ( $Found == false )
		return array(0, 'You Must Put Values');

// Check Type is Found Or Not
	for ($i = 1; $i <= 15; $i++){
		if ( isset($GLOBALS['Number'.$i]) ){
			
			if ( ($Result = $MySql->FetchOneRow('SELECT quantity_in_store FROM types WHERE'
							.' id = ?',
						array(
							$GLOBALS['TypeID'.$i]
						))) == -1 )
				return array(-1, $MySql->Error, 'in Fetching Type '.$i.' Data',
											'Check Data');
			else if ( $Result == 0 )
				return array(0, 'Type '.$i.' Not Found');
			
			if ( $GLOBALS['Status'] == '1' ){
				if ( $MySql->Fetched_Data['quantity_in_store'] 
								< $GLOBALS['TypeQuantity'.$i] )
					return array(0, 'Not Enough Quantity in Type '.$i);
			}

			if ( $GLOBALS['Status'] == '1' )
				$GLOBALS['TypeQuantityRemainder'.$i] = 
					$MySql->Fetched_Data['quantity_in_store'] - $GLOBALS['TypeQuantity'.$i];
			else
				$GLOBALS['TypeQuantityRemainder'.$i] = 
					$MySql->Fetched_Data['quantity_in_store'] + $GLOBALS['TypeQuantity'.$i];
		}
	}

// Getting bill id
	if ( ($Result = $MySql->GetRowCount('SELECT COUNT(*) FROM bill')) == -1 )
		return array(-1, $MySql->Error, 'Getting Bill id', 'Check Data');
	else
		$GLOBALS['Bill_id'] = $Result;

	if ( $GLOBALS['Status'] == '1' )
		return SaveBill();
	else 
		return SavePurchase();
}

/*
	Return :
		return array(-1, $MySql->Error, 'inserting Purchase into DataBase', 'Check Data');
		return array(-1, $MySql->Error, 'inserting Purchase item '.$i.' into DataBase',
										'Check Data');
		return array(-1, $MySql->Error, 'Add Quantity To Type '.$i, 
										'Check Data');
		return array(0, 'Done');
*/

function SaveBill(){
	$Hashing = new HashingClass();
	$MySql = new MYSQLClass();

// inserting bill
	if ( $MySql->excute('INSERT INTO bill(status, user, bill_date, customer, '
			.'type_of_buying, note, discount_in_pound, discount_in_percent, payied)'
			.' VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
				array(
					'1',
					$Hashing->Hash_Bill($_SESSION['Name']),
					date('D d-m-Y H:i:s'),
					$Hashing->Hash_Bill($GLOBALS['Name']),
					$GLOBALS['Buying'],
					$Hashing->Hash_Bill($GLOBALS['Note']),
					$GLOBALS['Discount'],
					$GLOBALS['AllDiscount'],
					$GLOBALS['Payied'],
				)) == -1 )
		return array(-1, $MySql->Error, 'inserting Bill into DataBase', 'Check Data');

// inserting bill items
	for ($i = 1; $i <= 15; $i++){
		if ( isset($GLOBALS['Number'.$i]) ){
			
			if ( $MySql->excute('INSERT INTO bill_items (bill_id, type_id, quantity, '
					.'piece_price, discount, notes) VALUES (?, ?, ?, ?, ?, ?)',
					array(
						$GLOBALS['Bill_id']+1,
						$GLOBALS['TypeID'.$i],
						$GLOBALS['TypeQuantity'.$i],
						$GLOBALS['TypePrice'.$i],
						$GLOBALS['TypeDiscount'.$i],
						$Hashing->Hash_Bill($GLOBALS['TypeNote'.$i])
					)) == -1 )
				return array(-1, $MySql->Error, 'inserting Bill item '.$i.' into DataBase',
										'Check Data');
			
			// Delete The Quantity From Type
			if ( $MySql->excute('UPDATE types SET quantity_in_store = ? WHERE id = ?',
					array(
						$GLOBALS['TypeQuantityRemainder'.$i],
						$GLOBALS['TypeID'.$i]
					)) == -1 )
				return array(-1, $MySql->Error, 'Delete Quantity From Type '.$i, 
										'Check Data');
		}
	}

	return array(0, 'Done');
}

/*
	Return :
		return array(-1, $MySql->Error, 'inserting Purchase into DataBase', 'Check Data');
		return array(-1, $MySql->Error, 'inserting Purchase item '.$i.' into DataBase',
										'Check Data');
		return array(-1, $MySql->Error, 'Add Quantity To Type '.$i, 
										'Check Data');
		return array(0, 'Done');
*/
function SavePurchase(){
	$Hashing = new HashingClass();
	$MySql = new MYSQLClass();

// inserting Purchase
	if ( $MySql->excute('INSERT INTO bill(status, user, bill_date, customer, '
			.'type_of_buying, note, discount_in_pound, discount_in_percent, payied)'
			.' VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
				array(
					'2',
					$Hashing->Hash_Bill($_SESSION['Name']),
					date('D d-m-Y H:i:s'),
					$Hashing->Hash_Bill($GLOBALS['Name']),
					$GLOBALS['Buying'],
					$Hashing->Hash_Bill($GLOBALS['Note']),
					$GLOBALS['Discount'],
					$GLOBALS['AllDiscount'],
					$GLOBALS['Payied'],
				)) == -1 )
		return array(-1, $MySql->Error, 'inserting Purchase into DataBase', 'Check Data');

// inserting Purchase items
	for ($i = 1; $i <= 15; $i++){
		if ( isset($GLOBALS['Number'.$i]) ){
			
			if ( $MySql->excute('INSERT INTO bill_items (bill_id, type_id, quantity, '
					.'piece_price, discount, notes) VALUES (?, ?, ?, ?, ?, ?)',
					array(
						$GLOBALS['Bill_id']+1,
						$GLOBALS['TypeID'.$i],
						$GLOBALS['TypeQuantity'.$i],
						$GLOBALS['TypePrice'.$i],
						$GLOBALS['TypeDiscount'.$i],
						$Hashing->Hash_Bill($GLOBALS['TypeNote'.$i])
					)) == -1 )
				return array(-1, $MySql->Error, 'inserting Purchase item '.$i
										.' into DataBase', 'Check Data');
			
			// Add The Quantity To Type
			if ( $MySql->excute('UPDATE types SET quantity_in_store = ?, '
					.'retail_discount = ? WHERE id = ?',
					array(
						$GLOBALS['TypeQuantityRemainder'.$i],
						$GLOBALS['TypeDiscount'.$i],
						$GLOBALS['TypeID'.$i]
					)) == -1 )
				return array(-1, $MySql->Error, 'Add Quantity To Type '.$i, 'Check Data');
		}
	}

	return array(0, 'Done');
}
?>