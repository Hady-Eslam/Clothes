<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;

$GLOBALS['Page_API_Error_Code'] = 'P10';
set_error_handler("Error_Handeler");


/* 
                            Check if User Is Logging in
*/
$_SESSION['Page Name'] = 'Bill';
$Result = array(-2 ,'');

if ( !isset($_SESSION['Name']) ){
    include_once UnAuthurithedUser;
    exit();
}
?>
<!DOCTYPE>
<html>
<head>
	<title>Bill</title>
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

    <script src="<?php echo PagesScripts; ?>BillScript.js"></script>

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
                Bill
            </div>

            <?php   include_once MessageBox; ?>

            <div style="text-align: left;" id="BillBox">

                <div style="margin-left: 77px;">
                    id = &nbsp;<input type="text" class="Input_ReadOnly" readonly 
                        style="margin-right: 380px;width: 100px;" 
                        value="<?php
    include_once MySqlDB;
    include_once HashClass;
    if ( ($Result = $MySql->GetRowCount('SELECT COUNT(*) FROM bill WHERE status = "1"')) 
                            == -1 ){
        $Result = array(-1, $MySql->Error, 'Getting Bill id', 'in Bill');
    ?>">
        <script type="text/javascript">
            <?php include_once TrigerErrorMessage; ?>
        </script>
    <?php
        exit();
    }
    else
        echo $Result+1;
                        ?>">

                    Date = &nbsp;<input type="text" name="" class="Input_Data"
                            style="background-color: #DBDBDB;" readonly 
                            value="<?php echo date('D  d-m-Y  H:i:s'); ?>">
                </div>

                <div>
                    Customer = &nbsp;<input type="text" class="Input_Data"
                            placeholder="Enter Customer Name" id="Name" name="N" 
                            style="width: 180px;" value="Customer"
                            oninput="CheckinputLen(this, Name_Len);">

                    <select class="Input_Data" style="margin-right: 81px;"
                        onchange="PutValue();" id="CustomerGroup" >
<?php
    if ( ($Result = $MySql->FetchAllRows('SELECT * FROM customers WHERE status = ?',
                        array(
                            '4'
                        ))) == -1 ){
        $Result = array(-1, $MySql->Error, 'Getting Customers From DataBase',
                            'in Bill');
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
                                    'in Purchases');
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
                    Type Of Buying =<input type="radio" name="Buying" class="RadioButton"
                                    value="Deferred">Deferred
                    <input type="radio" name="Buying" class="RadioButton" checked 
                                    value="Cash">Cash
                </div>

                <div style="margin-left: 40px;">
                    Notes = &nbsp;<input type="text" name="Note" class="Input_Data"
                        style="width: 400px;" placeholder="Enter Notes" id="Note" 
                        oninput="CheckinputLen(this, Note_Len);">
                </div>

                <p class="BottomBorder"></p>

                <div>
                    id = <input type="text" id="ID" class="Input_Data" style="width: 80px;"
                        placeholder="Enter id" oninput="GetCount();"> Search
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    
                    in Store = &nbsp;<input type="text" class="Input_ReadOnly"
                        readonly style="width: 80px;" id="Store">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    
                    WholeSale Price = &nbsp;<input type="text" class="Input_ReadOnly"
                        readonly style="width: 80px;" id="WSP">
                    &nbsp;&nbsp;&nbsp;&nbsp;

                    <p id="Result" style="color: red;display: inline;"></p>
                </div>

                <p class="BottomBorder"></p>


                <div style="padding: 0;margin: 0;">
                    <table style="margin-left: 0px;margin-top: 0px;padding-top: 0px;">
                        <tbody style="padding: 0;margin: 0;" id="Table">
                            
                        <tr style="background-color: #D1D5CF;
                            border-width: 1px;border-style: solid;border-color: black;">
                            
                            <th style="padding: 5;margin: 0;width: 12px;">id</th>
                            <th style="padding: 5;margin: 0;width: 200px;">Name</th>
                            <th style="padding: 5;margin: 0;width: 20px;">Quantity</th>
                            <th style="padding: 5;margin: 0;width: 20px;
                                        text-align: center;">Piece Price</th>
                            <th style="padding: 5;margin: 0;width: 20px;">Discount</th>
                            <th style="padding: 5;margin: 0;width: 20px;">Tatall</th>
                            <th style="padding: 5;margin: 0;width: 20px;">Notes</th>
                        </tr>

                        <tr>
                            <td style="padding: 5;margin:0 ;">
                                <input type="text" class="Input_Data" style="width: 70px;margin: 0;" placeholder="Enter id" id="TypeID1"
                                    oninput="PutData(1);" name="TypeID1">
                            </td>
                            <td style="padding: 5;margin:0 ;">
                                <input type="text" class="Input_ReadOnly" id="TypeName1" 
                                    style="width: 200px;margin: 0;" readonly 
                                    placeholder="Name" name="TypeName1">
                            </td>
                            <td style="padding: 5;margin:0 ;">
                                <input type="text" class="Input_Data" id="TypeQuantity1"
                                    style="width: 70px;margin: 0;" placeholder="Quantity"
                                    oninput="MakePrice(1);" name="TypeQuantity1">
                            </td>
                            <td style="padding: 5;margin:0 ;">
                                <input type="text" class="Input_Data" id="TypePrice1" 
                                    style="width: 70px;margin: 0;" placeholder="Price"
                                    oninput="MakePrice(1);" name="TypePrice1">
                            </td>
                            <td style="padding: 5;margin:0 ;">
                                <input type="text" class="Input_ReadOnly" readonly
                                    id="TypeDiscount1" style="width: 80px;margin: 0;"
                                    placeholder="0 %" name="TypeDiscount1">

                                <input type="hidden" id="TypeDiscountHidden1" value="0"
                                    name="TypeDiscountHidden1">
                            </td>
                            <td style="padding: 5;margin:0 ;">
                                <input type="text" class="Input_ReadOnly" readonly
                                    style="width: 70px;margin: 0;" id="TypeTotall1"  
                                    placeholder="Totall" name="TypeTotall1">
                            </td>
                            <td style="padding: 5;margin:0 ;">
                                <input class="Input_Data" style="width: 250px;margin: 0;"
                                    type="text" id="TypeNote1" placeholder="Enter Notes"
                                    oninput="CheckinputLen(this, Note_Len);"
                                    name="TypeNote1">
                            </td>
                            <td style="padding: 5;margin:0 ;">
                                <input type="image" src="<?php echo AddPicture; ?>"
                                    width='30' hieght='20'
                                    style='margin-left: 10px;'onclick='MakeNewRow();'>
                                
                                <strong style="font-size: 20px;">1</strong>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <p class="BottomBorder"></p>

                <div>
                    Discount : <input type="text" class="Input_Data" id="Discount"
                        style="width: 60px;text-align: center;" placeholder="0.00"
                        oninput="DiscountForAll(this);" name="Discount">

                    <input type="text" class="Input_Data" placeholder="0.0" 
                        style="width: 60px;text-align: center;" id="AllDiscount" 
                        oninput="DiscountForAllMoney();" name="AllDiscount"> %

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Payied = <input type="text" class="Input_Data" placeholder="0.0"
                                style="width: 70px;text-align: center;" id="Payied"
                                oninput="SetTotal();" name="Payied">
                    
                    Remainder = <input type="text" class="Input_ReadOnly" readonly
                                placeholder="0.0" style="width: 70px;text-align: center;"
                                id="Remainder" name="Remainder">

                    Total Money = <input type="text" class="Input_ReadOnly" readonly 
                                style="width: 70px;text-align: center;" placeholder="0.0"
                                id="TotalMoney" name="TotalMoney">
                </div>

                <div style="text-align: left;">
                    Total Money With Discount = <input type="text" class="Input_ReadOnly"
                            readonly style="width: 150px;text-align: center;" 
                            placeholder="0.0" id="TotalMoneyWithDiscount"
                            name="TotalMoneyWithDiscount">
                </div>

                <p class="BottomBorder"></p>

                <div style="text-align: center;">
                    <input type="button" class="Button" value="Save Bill" id="SaveBill">
                </div>
            </div>

        </div>

    </section>
    
    <?php  include_once Footer; ?>

    <script type="text/javascript">
        var Count_Rows = 1;
        var Found = false;

        var Name_Len = <?php echo Name_Len; ?>;
        var Note_Len = <?php echo Note_Len; ?>;
        var ID_Len = <?php echo ID_Len; ?>;
        var Money_Len = <?php echo Money_Len; ?>;

        var AddPicture = '<?php echo AddPicture; ?>';

        var MyPage = '<?php echo Bill; ?>';
        var GetTypeDataPage = '<?php echo SimiPages; ?>GetTypeData.php';
        var SaveBillPage = '<?php echo SimiPages; ?>SaveBill.php';
        var KeepSessionAlivePage = '<?php echo SimiPages; ?>KeepSessionAlive.php';
    </script>

</body>
</html>