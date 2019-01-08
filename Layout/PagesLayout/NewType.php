<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;

$GLOBALS['Page_API_Error_Code'] = 'P7';
set_error_handler("Error_Handeler");


/* 
                            Check if User Is Logging in
*/
$_SESSION['Page Name'] = 'New Type';
$Result = array(-2 ,'');

if ( !isset($_SESSION['Name']) ){
    include_once UnAuthurithedUser;
    exit();
}
else if ( $_SESSION['Status'] != '0' ){
    include_once UnAuthurithedUser;
    exit();
}

if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_SERVER["HTTP_REFERER"]) &&
     isset($_POST['G']) && isset($_POST['N']) && isset($_POST['F']) && 
     isset($_POST['WP']) && isset($_POST['WD']) && isset($_POST['RP']) && 
     isset($_POST['RD']) && isset($_POST['Q'])
){

    if ( isset($_GET['Result']) )
        unset($_GET['Result']);

    include_once URL;
    if ( $URL_REFERER->GetURLPath() == -1 ){
        include_once ErrorPage;
        exit();
    }

    if ( $URL_REFERER->URLResult != NewType ){
        include_once UnAuthurithedUser;
        exit();
    }

/*
    Return :    
        return array(0, 'Empty');
        return array(0, 'Too Long');
        return array(0, 'Not Number');
        return array(0, 'Negative Value');

        return array(0, 'Wrong Data');
        return array(0, 'Not in Range');

        return array(-1, $MySql->Error, 'inserting New Type into DataBase', 'Check Data');
        return array(0, 'Done');
*/
    include_once PagesPHP.'NewTypeProcessData.php';
    $Result = CheckData();
    if ( $Result[0] == 0 ){
        if ( $Result[1] == 'Empty' || $Result[1] == 'Too Long' ||
             $Result[1] == 'Not Number' || $Result[1] == 'Negative Value' ||
             $Result[1] == 'Wrong Data' || $Result[1] == 'Not in Range'
         ){
            header("Location:".NewType);
            exit();
        }
        header("Location:".NewType."?Result");
        exit();
    }
}
?>
<!DOCTYPE>
<html>
<head>
	<title>New Type</title>
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

    <script src="<?php echo PagesScripts; ?>NewTypeScript.js"></script>

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
                New Type
            </div>

            <?php   include_once MessageBox; ?>

            <script type="text/javascript">
<?php
                if ( $Result[0] == -1 )
                    include_once TrigerErrorMessage;
                else if ( isset($_GET['Result']) ){
                ?>
                    SetMessage(2500, 'green', '<p>Type Successfully Added</p>');
                <?php
                }
?>
            </script>

            <div id='NewTypeForm'>

                <div style="text-align: left;padding-left: 120px;">
                    Group &nbsp;&nbsp;=&nbsp;
                    <select class="Input_Data" style="margin-right: 160px;" name="G">
                        <option>Pantes</option>
                        <option>T-Shert</option>
                        <option>Cap</option>
                    </select>
                    Name = &nbsp;<input type="text" name="N" class="Input_Data" 
                            placeholder="Enter Name" id="Name"
                            oninput="CheckinputLen(this, Name_Len);">
                </div>

                <div style="text-align: left;padding-left: 120px;">
                    Factory = &nbsp;<input type="text" name="F" class="Input_Data" 
                                placeholder="Enter Factory Name" id="Factory" 
                                oninput="CheckinputLen(this, Name_Len);">
                    
                    <select class="Input_Data" onchange="PutValue();" id="FactoryGroup">
<?php
    include_once MySqlDB;
    include_once HashClass;
    if ( ($Result = $MySql->FetchAllRows('SELECT * FROM customers WHERE status = ?',
                        array(
                            '2'
                        ))) == -1 ){
        $Result = array(-1, $MySql->Error, 'Getting Customers From DataBase',
                            'in New Type');
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
                                    'in New Type');
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
                </div>

                <p class="BottomBorder"></p>
                
                <div style="text-align: left;padding-left: 45px;">
                    
                    Wholesale Price = &nbsp;<input type="text" name="WP" 
                        style="margin-right: 50px;" class="Input_Data" 
                        placeholder="Enter Wholesale Price" id="WholesalePrice" 
                        oninput="CheckinLenAndNum(this, Money_Len);">

                    Wholesale Discount = &nbsp;<input type="text" name="WD"
                        id="WholesaleDiscount" class="Input_Data" 
                        placeholder="Enter Wholesale Discount"
                        oninput="Dicount('true', '#WholesaleDiscount');"> %
                </div>

                <div style="text-align: left;padding-left: 88px;">
                    Retail Price = &nbsp;<input type="text" name="RP" class="Input_Data"
                            placeholder="Enter Retail Price"style="margin-right: 92px;"
                            id="RetailPrice" 
                            oninput="CheckinLenAndNum(this, Money_Len);">
                    
                    Retail Discount = &nbsp;<input type="text"name="RD"id="RetailDiscount" 
                            class="Input_Data" placeholder="Enter Retail Discount"
                            oninput="Dicount('true', '#RetailDiscount');"> %
                </div>

                <p class="BottomBorder"></p>

                <div style="text-align: left;padding-left: 50px;">
                    
                    <p style="text-align: left;color: red;padding: 0px;margin: 0px;
                            margin-bottom: 10px;">
                            initial Quantity</p>
                    
                    <p style="display: inline;margin-left: 68" 
                        >Quantity = &nbsp;<input type="text" name="Q" 
                            class="Input_Data" placeholder="Enter Quantity" id="Q" 
                        oninput="CheckinLenAndNum(this, Money_Len);"></p>
                </div>

                <p class="BottomBorder"></p>

                <div>
                    <input type="submit" value="Save Type" class='Button' id="Submit">
                </div>

            </div>

        </div>

    </section>
    
    <?php  include_once Footer; ?>

    <script type="text/javascript">
        var Name_Len = <?php echo Name_Len; ?>;
        var Money_Len = <?php echo Money_Len; ?>;

        var MyPage = '<?php echo NewType; ?>';
        var KeepSessionAlivePage = '<?php echo SimiPages; ?>KeepSessionAlive.php';
    </script>

</body>
</html>