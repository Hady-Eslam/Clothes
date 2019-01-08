<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;

$GLOBALS['Page_API_Error_Code'] = 'P6';
set_error_handler("Error_Handeler");


/* 
                            Check if User Is Logging in
*/

if ( isset($_GET['Status']) ){
    if ( $_GET['Status'] == 'Pay' ){
        $GLOBALS['PageStatus'] = '0';
        $_SESSION['Page Name'] = 'Pay Money';
    }
    else{
        $GLOBALS['PageStatus'] = '1';
        $_SESSION['Page Name'] = 'Get Money';
    }
}
else{
    $GLOBALS['PageStatus'] = '0';
    $_SESSION['Page Name'] = 'Pay Money';
}
$Result = array(-2 ,'');

if ( !isset($_SESSION['Name']) ){
    include_once UnAuthurithedUser;
    exit();
}

if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_SERVER["HTTP_REFERER"]) &&
     isset($_POST['N']) && isset($_POST['M']) && isset($_POST['Note']) &&
     ( isset($_POST['Pay']) || isset($_POST['Get']) )
){

    if ( isset($_GET['Result']) )
        unset($_GET['Result']);

    include_once URL;
    if ( $URL_REFERER->GetURLPath() == -1 ){
        include_once ErrorPage;
        exit();
    }

    if ( $URL_REFERER->URLResult != Receipt ){
        include_once UnAuthurithedUser;
        exit();
    }

/*
    Return :
        return array(0, 'Empty');
        return array(0, 'Too Long');
        return array(0, 'Not Number');
        return array(0, 'Non Positive Value');

        return array(-1, $MySql->Error, 'Checking Name in DataBase', 'Pay Money');
        return array(-1, $MySql->Error, 'inserting Customer Name into DataBase',
                        'Pay Money');
        return array(-1, $MySql->Error, 'inserting Receipt Data into DataBase',
                        'Pay Money');
        return array(0, 'Done');
*/
    include_once PagesPHP.'ReceiptProcessData.php';
    $Result = CheckData();
    
    if ( $Result[0] == 0 ){
        if ( $Result[1] == 'Empty' || $Result[1] == 'Too Long' ||
             $Result[1] == 'Not Number' || $Result[1] == 'Non Positive Value' 
         ){
            header("Location:".Receipt);
            exit();
        }
        if ( $GLOBALS['PageStatus'] == '0' ){
            header("Location:".Receipt."?Status=Pay&Result=".$Result[1]);
            exit();
        }
        else{
            header("Location:".Receipt."?Status=Get&Result=".$Result[1]);
            exit();
        }
    }
}
?>
<!DOCTYPE>
<html>
<head>
	<title><?php echo $_SESSION['Page Name']; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo HeaderCSS; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo CenterCSS; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo FooterCSS; ?>">
    <link rel="icon" type="image/JPG" href="<?php echo LOGO; ?>">

    <script src="<?php echo JQueryScript; ?>"></script>
    <script src="<?php echo DropBoxScript; ?>"></script>
    <script src="<?php echo SetMessageBoxScript; ?>"></script>
    <script src="<?php echo KeepSessionAliveScript; ?>"></script>

    <script src="<?php echo CheckLenScript; ?>"></script>
    <script src="<?php echo CheckinputLenScript; ?>"></script>
    <script src="<?php echo CheckinLenAndNumScript; ?>"></script>
    <script src="<?php echo isNumberScript; ?>"></script>
    <script src="<?php echo TriggerFormScript; ?>"></script>

    <script src="<?php echo PagesScripts; ?>ReceiptScript.js"></script>

    <style type="text/css">
    	.BottomBorder{
            border-bottom-style: solid;
            border-bottom-width: 1px;
            margin-left: 200px;
            margin-right: 300px;
        }
        select{
            width: 120px;
        }
        select option{
            width: 120px;
        }
    </style>
</head>
<body>

	<?php include_once UserHeader; ?>

    <section>
    
    <?php include_once UserWorkHeader; ?>

        <div class = 'SectionDiv'>

            <div class ='Title'>
                <?php echo $_SESSION['Page Name']; ?>
            </div>

            <?php   include_once MessageBox; ?>

            <script type="text/javascript">
<?php
    if ( $Result[0] == -1 )
        include_once TrigerErrorMessage;
    else if ( isset($_GET['Result']) ){
        if ( $_GET['Result'] == 'Done' ){
        ?>
            SetMessage(2000, 'green', '<p>Receipt Successfully Added</p>');
        <?php
        }
    }
?>
        </script>
            
            <div style="text-align: left;" id="<?php
                if ( $_SESSION['Page Name'] == 'Pay Money' )
                    echo 'PayBox';
                else
                    echo 'GetBox';
            ?>">
            	<div style="margin-left: 43px;">
            		id = <input type="text" class="Input_ReadOnly" readonly
            				style="margin-right: 312px;width: 100px;"
                            value="
<?php
    include_once MySqlDB;
    include_once HashClass;
    if ( ($Result = $MySql->GetRowCount('SELECT COUNT(*) FROM receipt WHERE'
                    .' status = "'.$GLOBALS['PageStatus'].'"')) == -1 ){
        $Result = array(-1, $MySql->Error, 'Getting Receipt id From DataBase',
                            'in Receipt');
    ?>">
        <script type="text/javascript">
            <?php include_once TrigerErrorMessage; ?>
        </script>
    <?php
    }
    else{
        echo $Result+1;
    }
?>">
            		Date = <input type="text" name="" class="Input_ReadOnly" readonly
            					value="<?php echo date('D   d/m/Y   H:i:s'); ?>">
            	</div>

            	<div style="margin-left: <?php
                    if ( $GLOBALS['PageStatus'] == '0' )
                            echo '36';
                        else
                            echo '11';
                ?>px;">

            		<?php
                        if ( $GLOBALS['PageStatus'] == '0' )
                            echo 'To';
                        else
                            echo 'From';
                    ?> = <input type="text" name="N" class="Input_Data"
            			placeholder="Enter Name" style="width: 180px;" id="Name"
            			oninput="CheckinputLen(this, Name_Len);">

            		<select class="Input_Data" style="margin-right: 90px;" id="GName"
                        onchange="PutValue();">
<?php
    include_once MySqlDB;
    include_once HashClass;
    if ( ($Result = $MySql->FetchAllRows('SELECT * FROM customers WHERE status = ?',
                array(
                    $GLOBALS['PageStatus']
                ))) == -1 ){
        $Result = array(-1, $MySql->Error, 'Getting Customers From DataBase','in Receipt');
    ?>
        <script type="text/javascript">
            <?php include_once TrigerErrorMessage; ?>
        </script>
    <?php
    }
    else
        foreach ($MySql->Fetched_Data as $Value) {
            echo '<option>';
            if ( $Hashing->Get_Hashed_Customers($Value['name']) == -1 ){
                $Result = array(-1, $Hashing->Error, 'Getting Customer Name Heshed',
                                    'in Receipt');
                ?>
                    <script type="text/javascript">
                        <?php include_once TrigerErrorMessage; ?>
                    </script>
                <?php
                break;
            }
            echo $Hashing->HashedText.'</option>';
        }
?>
            		</select>

            		Money = <input type="text" name="M" class="Input_Data"
            					placeholder="Enter Money" id="Money"
            					oninput="CheckinLenAndNum(this, Money_Len);">
            	</div>

            	<div>
            		Notes = <input type="text" name="Note" class="Input_Data" id="Note" 
            				placeholder="Enter Notes" style="width: 400px;"
            				oninput="CheckinputLen(this, Note_Len);">
            	</div>

            	<p class="BottomBorder"></p>

            	<div style="text-align: center;margin-right: 120px;">
            		<input type="submit" value="Save" class="Button" 
            			style="width: 200px;" 
                        id="<?php
                             if ( $GLOBALS['PageStatus'] == '0' )
                                echo 'PaySubmit';
                            else
                                echo 'GetSubmit';
                        ?>"
                        name="<?php
                            if ( $GLOBALS['PageStatus'] == '0' )
                                echo 'Pay';
                            else
                                echo 'Get';
                        ?>">
            	</div>
            </div>

        </div>

    </section>
    
    <?php  include_once Footer; ?>

    <script type="text/javascript">
    	var Name_Len = <?php echo Name_Len; ?>;
    	var Money_Len = <?php echo Money_Len; ?>;
    	var Note_Len = <?php echo Note_Len; ?>;

        var MyPage = '<?php 
                if ($_SESSION['Page Name'] == 'Pay Money')
                    echo Receipt.'?Status=Pay';
                else
                    echo Receipt.'?Status=Get';
                ?>';
        var KeepSessionAlivePage = '<?php echo SimiPages; ?>KeepSessionAlive.php';
    </script>
</body>
</html>