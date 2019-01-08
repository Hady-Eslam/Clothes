<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;

$GLOBALS['Page_API_Error_Code'] = 'P8';
set_error_handler("Error_Handeler");


/* 
                            Check if User Is Logging in
*/
$_SESSION['Page Name'] = 'Store Movement';
$Result = array(-2 ,'');

if ( !isset($_SESSION['Name']) ){
    include_once UnAuthurithedUser;
    exit();
}
?>
<!DOCTYPE>
<html>
<head>
	<title>Store Movement</title>
    <link rel="stylesheet" type="text/css" href="<?php echo HeaderCSS; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo CenterCSS; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo FooterCSS; ?>">
    <link rel="icon" type="image/JPG" href="<?php echo LOGO; ?>">

    <script src="<?php echo JQueryScript; ?>"></script>
    <script src="<?php echo DropBoxScript; ?>"></script>
    <script src="<?php echo SetMessageBoxScript; ?>"></script>
    <script src="<?php echo KeepSessionAliveScript; ?>"></script>

    <style type="text/css">
        .BottomBorder{
            border-bottom-style: solid;
            border-bottom-width: 1px;
            margin-left: 200px;
            margin-right: 300px;
        }

        th{
            color: #40535F;
            margin: 0;
            padding: 5;
            padding-bottom: 30px;
            padding-top: 30px;
            border-left-width: 0px;
            border-left-style: solid;
            width: 200px;
            text-align: left;
            height: 50px;
            text-align: center;
        }

        td{
            color: #40535F;
            font-size: 15px;
            padding: 3px;
            text-align: left;
            padding-bottom: 10px;
        }

        tr{
            padding: 0;margin: 0;
        }
    </style>

</head>
<body>

	<?php include_once UserHeader; ?>

    <section>
    
    <?php include_once UserWorkHeader; ?>

        <div class = 'SectionDiv'>

            <div class ='Title'>
                Store Movement
            </div>

            <?php   include_once MessageBox; ?>

            <div>

                <table style="margin-left: 0px;margin-top: 0px;padding-top: 0px;">
                    <tbody>
                        <tr style="background-color: #D1D5CF; text-align: left;
                            border-width: 1px;border-style: solid;border-color: black;">

                            <th style="width: 30px;">id</th>
                            <th style="width: 140px;">Name</th>
                            <th style="width: 150px;">Group</th>
                            <th style="width: 150px;">Factory</th>
                            <th style="width: 150px;text-align: center;" 
                                        colspan="4">Price</th>
                            <th style="width: 150px;text-align: center;">Quantity</th>
                        </tr>
                        <tr style="background-color: #D1D5CF; text-align: left;
                            border-width: 1px;border-style: solid;border-color: black;">
                            <th style="width: 30px;"></th>
                            <th style="width: 140px;"></th>
                            <th style="width: 150px;"></th>
                            <th style="width: 150px;"></th>
                            <th style="width: 80px;">WholeSale</th>
                            <th style="width: 40px;">Discount</th>
                            <th style="width: 70px;">Retail</th>
                            <th style="width: 40px;">Discount</th>
                            <th style="width: 80px;"></th>
                        </tr>
<?php
    include_once MySqlDB;
    include_once HashClass;
    $Counter = 1;
    if ( ($Result = $MySql->FetchAllRows('SELECT * FROM types', array())) == -1 ){
        $Result = array(-1, $MySql->Error, 'Fetching Types Data From DataBase',
                        'in Store Movement');
    ?>
        <script type="text/javascript">
            <?php include_once TrigerErrorMessage; ?>
        </script>
    <?php
    }
    else
        foreach ($MySql->Fetched_Data as $Value) {

            echo '<tr>';
            echo '<td>'.$Value['id'].'</td>';
            echo '<td><p>';
            if ( $Hashing->Get_Hashed_Type($Value['name']) == -1 ){
                $Result = array(-1, $Hashing->Error, 'Getting Type Name Heshed',
                                'in Store Movement');
                ?>
                    <script type="text/javascript">
                        <?php include_once TrigerErrorMessage; ?>
                    </script>
                <?php
                break;
            }
            for ($i = 0; $i < strlen($Hashing->HashedText); $i++ ){
                if ( $i % 20 == 0 && $i != 0 )
                    echo '-</p><p>';
                echo $Hashing->HashedText[$i];
            }
            echo '</p></td>';

            echo '<td><p>';
            if ( $Hashing->Get_Hashed_Type($Value['type_group']) == -1 ){
                $Result = array(-1, $Hashing->Error, 'Getting Type Group Heshed',
                                'in Store Movement');
                ?>
                    <script type="text/javascript">
                        <?php include_once TrigerErrorMessage; ?>
                    </script>
                <?php
                break;
            }
            for ($i = 0; $i < strlen($Hashing->HashedText); $i++ ){
                if ( $i % 20 == 0 && $i != 0 )
                    echo '-</p><p>';
                echo $Hashing->HashedText[$i];
            }
            echo '</p></td>';

            echo '<td><p>';
            if ( $Hashing->Get_Hashed_Type($Value['factory']) == -1 ){
                $Result = array(-1, $Hashing->Error, 'Getting Type Factory Heshed',
                                'in Store Movement');
                ?>
                    <script type="text/javascript">
                        <?php include_once TrigerErrorMessage; ?>
                    </script>
                <?php
                break;
            }
            for ($i = 0; $i < strlen($Hashing->HashedText); $i++ ){
                if ( $i % 20 == 0 && $i != 0 )
                    echo '-</p><p>';
                echo $Hashing->HashedText[$i];
            }
            echo '</p></td>';

            echo '<td>'.$Value['wholesale_price'].'</td>';

            echo '<td>'.$Value['wholesale_discount'].' <strong>%</strong></td>';

            echo '<td>'.$Value['retail_price'].'</td>';

            echo '<td>'.$Value['retail_discount'].' <strong>%</strong></td>';

            echo '<td style="text-align:center;">'.$Value['quantity_in_store'].'</td>';
            $Counter++;
        ?>
        <tr>
            <td>
                
            </td>
            
            <td colspan="8" style="margin: 0;padding: 0;">
                <p style="border-bottom-style: solid;border-bottom-width: 1px;margin: 0px;padding: 0;"></p>
            </td>
        </tr>
        <?php
        }
?>
                    </tbody>
                </table>
            	
            </div>

        </div>

    </section>
    
    <?php  include_once Footer; ?>

    <script type="text/javascript">
        var KeepSessionAlivePage = '<?php echo SimiPages; ?>KeepSessionAlive.php';
    </script>

</body>
</html>