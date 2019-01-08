<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;

$GLOBALS['Page_API_Error_Code'] = 'P5';
set_error_handler("Error_Handeler");


/* 
                            Check if User Is Logging in
*/
$_SESSION['Page Name'] = 'Clients';
$Result = array(-2 ,'');

if ( !isset($_SESSION['Name']) ){
    include_once UnAuthurithedUser;
    exit();
}

if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_SERVER["HTTP_REFERER"]) &&
    ( 
        ( isset($_POST['EditButton']) && isset($_POST['ID']) && isset($_POST['Ph1']) &&
          isset($_POST['Ph2']) && isset($_POST['Ph3']) && isset($_POST['Add']) &&
          isset($_POST['N']) ) ||
        
        ( isset($_POST['DeleteButton']) && isset($_POST['N']) ) ||

        ( isset($_POST['NewButton']) && isset($_POST['Ph1']) && isset($_POST['Ph2']) &&
          isset($_POST['Ph3']) && isset($_POST['Add']) && isset($_POST['N']) )
    )
){

    if ( isset($_GET['Result']) )
        unset($_GET['Result']);

    include_once URL;
    if ( $URL_REFERER->GetURLPath() == -1 ){
        include_once ErrorPage;
        exit();
    }

    if ( $URL_REFERER->URLResult != Clients ){
        include_once UnAuthurithedUser;
        exit();
    }

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
    include_once PagesPHP.'ClientsProcessData.php';
    $Result = CheckData();
    if ( $Result[0] == 0 ){
        if ( $Result[1] == 'Empty' || $Result[1] == 'Too Long'){
            header("Location:".Clients);
            exit();
        }
        header("Location:".Clients."?Result=".$Result[1]);
        exit();
    }
}
?>
<!DOCTYPE>
<html>
<head>
	<title>Clients</title>
    <link rel="stylesheet" type="text/css" href="<?php echo HeaderCSS; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo CenterCSS; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo FooterCSS; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo CSS; ?>Clients.css">

    <link rel="icon" type="image/JPG" href="<?php echo LOGO; ?>">

    <script src="<?php echo JQueryScript; ?>"></script>
    <script src="<?php echo DropBoxScript; ?>"></script>
    <script src="<?php echo SetMessageBoxScript; ?>"></script>
    <script src="<?php echo KeepSessionAliveScript; ?>"></script>

    <script src="<?php echo CheckLenScript; ?>"></script>
    <script src="<?php echo CheckinputLenScript; ?>"></script>
    <script src="<?php echo TriggerFormScript; ?>"></script>

    <script src="<?php echo PagesScripts; ?>ClientsScript.js"></script>

    <style type="text/css">
        .Input{
            width: 115px;
            margin-left: 0px;
            margin-right: 0px;

        }
    </style>

</head>
<body >

	<?php include_once UserHeader; ?>

    <section>
    
    <?php include_once UserWorkHeader; ?>

        <div class = 'SectionDiv'>

            <div class ='Title'>
                Clients
            </div>

            <?php   include_once MessageBox; ?>

            <script type="text/javascript">
<?php
    if ( $Result[0] == -1 )
        include_once TrigerErrorMessage;
    else if ( isset($_GET['Result']) ){
        if ( $_GET['Result'] == 'Deleted' ){
        ?>
            SetMessage(2000, 'green', '<p>Client Successfully Deleted</p>');
            setTimeout(function () {
                location.href = MyPage;
            }, 2000);
        <?php
        }
        else if ( $_GET['Result'] == 'Edited' ){
        ?>
            SetMessage(2000, 'green', '<p>Client Data Successfully Edited</p>');
            setTimeout(function () {
                location.href = MyPage;
            }, 2000);
        <?php   
        }
        else if ( $_GET['Result'] == 'Added' ){
        ?>
            SetMessage(2000, 'green', '<p>Client Successfully Added</p>');
            setTimeout(function () {
                location.href = MyPage;
            }, 2000);
        <?php   
        }
        else if ( $_GET['Result'] == 'Not Found' ){
        ?>
            SetMessage(2500, 'red', '<p>This Client Not Found</p>');
        <?php
        }
    }
?>
            </script>
            
            <table style="margin-left: 0px;margin-top: 0px;padding-top: 0px;">
                <tbody>
                    <tr style="background-color: #D1D5CF;
                        border-width: 1px;border-style: solid;border-color: black;">
                        
                        <th style="width: 10px;">id</th>
                        <th style="width: 180px;">Name</th>
                        <th>Phone1</th>
                        <th>Phone2</th>
                        <th>Phone3</th>
                        <th style="width: 240px;">Address</th>
                    </tr>

                    <tr id="NewBox">
                        <td>
                            
                        </td>
                        <td>
                            <input class='Input Input_Data' id='NewName'
                                placeholder="Client Name" type="text" name="N" 
                                oninput="CheckinputLen(this, Name_Len);"
                                style="width: 170px;">
                        </td>

                        <td>
                            <input class='Input Input_Data' id='NewPhone1' type="text" 
                                name="Ph1" placeholder="First Phone">
                        </td>

                        <td>
                            <input class='Input Input_Data' id='NewPhone2' type="text" 
                                name="Ph2" placeholder="Second Phone">
                        </td>

                        <td>
                            <input class='Input Input_Data' id='NewPhone3' type="text" 
                                name="Ph3" placeholder="Third Phone">
                        </td>

                        <td>
                            <input class='Input Input_Data' id='NewAddress' type="text" 
                                name="Add" oninput="CheckinputLen(this, Address_Len);"
                                placeholder="Client Address" style="width: 200px;">
                        </td>

                        <td style="padding-left: 0; margin-left: 0">
                            <input type="submit" name="NewButton" class="Button" 
                                value="Add Client" id="NewButton">
                        </td>
                    </tr>
                    <td colspan="6">
                            <p style="border-bottom-style: solid;border-bottom-width: 1px;margin: 0px;margin-left:50px;padding-left: 50px;"></p>
                        </td>
<?php
    include_once MySqlDB;
    include_once HashClass;
    $Counter = 1;
    if ( ($Result = $MySql->FetchAllRows('SELECT * FROM clients',array())) == -1 ){
        $Result = array(-1, $Hashing->Error, 'Getting Client Data From DataBase',
                                'in Clients');
    ?>
        <script type="text/javascript">
            <?php include_once TrigerErrorMessage; ?>
        </script>
    <?php
    }
    else
        foreach ($MySql->Fetched_Data as $Value) {

            echo '<tr>';
            echo '<td>'.$Counter.'</td>';
            echo '<td><p>';
            if ( $Hashing->Get_Hashed_Clients($Value['name']) == -1 ){
                $Result = array(-1, $Hashing->Error, 'Getting Client Name Heshed',
                                'in Clients');
                ?>
                    <script type="text/javascript">
                        <?php include_once TrigerErrorMessage; ?>
                    </script>
                <?php
                break;
            }
            $Name = $Hashing->HashedText;
            for ($i = 0; $i < strlen($Hashing->HashedText); $i++ ){
                if ( $i % 20 == 0 && $i != 0 )
                    echo '-</p><p>';
                echo $Hashing->HashedText[$i];
            }
            echo '</p></td>';

            if ( $Hashing->Get_Hashed_Clients($Value['phone1']) == -1 ){
                $Result = array(-1, $Hashing->Error, 'Getting Client Phone1 Heshed',
                                    'in Clients');
                ?>
                    <script type="text/javascript">
                        <?php include_once TrigerErrorMessage; ?>
                    </script>
                <?php
                break;
            }
            echo '<td>'.$Hashing->HashedText.'</td>';
            $Phone1 = $Hashing->HashedText;

            if ( $Hashing->Get_Hashed_Clients($Value['phone2']) == -1 ){
                $Result = array(-1, $Hashing->Error, 'Getting Client Phone2 Heshed',
                                'in Clients');
                ?>
                    <script type="text/javascript">
                        <?php include_once TrigerErrorMessage; ?>
                    </script>
                <?php
                break;
            }
            echo '<td>'.$Hashing->HashedText.'</td>';
            $Phone2 = $Hashing->HashedText;

            if ( $Hashing->Get_Hashed_Clients($Value['phone3']) == -1 ){
                $Result = array(-1, $Hashing->Error, 'Getting Client Phone3 Heshed',
                                    'in Clients');
                ?>
                    <script type="text/javascript">
                        <?php include_once TrigerErrorMessage; ?>
                    </script>
                <?php
                break;
            }
            echo '<td>'.$Hashing->HashedText.'</td>';
            $Phone3 = $Hashing->HashedText;

            echo '<td><p>';
            if ( $Hashing->Get_Hashed_Clients($Value['address']) == -1 ){
                $Result = array(-1, $Hashing->Error, 'Getting Client Address Heshed',
                                            'in Clients');
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
            $Address = $Hashing->HashedText;
            ?>
            <td>
                <input type="image" src="<?php echo EditPicture; ?>" 
                        width = '25' hight='25' style='margin-right: 20px;'
                        onclick='EditClient(<?php
                        echo '"'.$Value['id'].'",';
                        echo '"'.$Name.'",';
                        echo '"'.$Phone2.'",';
                        echo '"'.$Phone2.'",';
                        echo '"'.$Phone3.'",';
                        echo '"'.$Address.'"'; 
                            ?>);'>

                <input type="image" src="<?php echo DeletePicture; ?>" 
                        width = '25' hight='25'
                        onclick='DeleteClient(<?php
                        echo '"'.$Counter.'",';
                        echo '"'.$Name.'",';
                            ?>);'>
            </td>
            <?php
            echo '</tr>';
            ?>
            <td colspan="6">
                    <p style="border-bottom-style: solid;border-bottom-width: 1px;margin: 0px;margin-left:50px;padding-left: 50px;"></p>
                </td>
            <?php
            $Counter++;
        }
?>
                </tbody>
            </table>
        </div>

    </section>


<!-- The Delete Box -->
    <div id="DeleteBox" class="DeleteBox">
        
        <p style="text-align: center;">
            <strong>Are You Sure Want To Delete This User</strong></p>
        <p style="text-align: center;" id="Deleteid"></p>

        <input type="hidden" name="N" id='DeleteName'>

        <div class="Box_ButtonDiv">
            <input type="submit" class="Box_Button Button" value="Cancel"
                onclick="CancelButton();">

            <input type="submit" name="DeleteButton" class="Button" value="Delete" 
                    style="background-color: red;border-color: red" id="DeleteButton">
        </div>
    </div>


<!-- The Edit Box -->
    <div id="EditBox" class="EditBox">

        <div style="text-align: center;padding: 0px;margin: 0px;">
            <p><strong>Client informations</strong></p>
        </div>

        <p class="Box_P"></p>
            
        <div style="padding: 15px;margin: 15px;">


            <input type="hidden" name="ID" id="EditID">
        
        <!-- Client Name -->
            <div style="padding: 5px;margin: 5px;">

                <p style="padding-left: 40px;"class="EditBox_P">
                    <strong>Client Name </strong></p>

                <input class='Input_Data' id='Name' placeholder="Enter Client Name" 
                    type="text" name="N" oninput="CheckinputLen(this, Name_Len);">
            </div>

        <!-- Client Phone -->
            <div style="padding: 5px;margin: 5px;">
                <p class="EditBox_P"><strong>Client Phone </strong></p>
                <input class='Input_Data' id='Phone1' type="text" name="Ph1"
                    placeholder="Enter First Phone">
            </div>

        <!-- Client Phone -->
            <div style="padding: 5px;margin: 5px;">
                <p class="EditBox_P"><strong>Client Phone </strong></p>
                <input class='Input_Data' id='Phone2' type="text" name="Ph2"
                    placeholder="Enter Second Phone">
            </div>

        <!-- Client Phone -->
            <div style="padding: 5px;margin: 5px;">
                <p class="EditBox_P"><strong>Client Phone </strong></p>
                <input class='Input_Data' id='Phone3' type="text" name="Ph3"
                    placeholder="Enter Third Phone">
            </div>

        <!-- Client Address -->
            <div style="padding: 5px;margin: 5px;">

                <p style="padding-left: 19px;" class="EditBox_P">
                    <strong>Client Address </strong></p>
                <input class='Input_Data' id='Address' type="text" name="Add"
                    oninput="CheckinputLen(this, Address_Len);"
                    placeholder="Enter Client Address">
            </div>
            
        </div>
            
        <p class="Box_P"></p>

        <div class="Box_ButtonDiv">
            <input type="submit" name="" class="Box_Button Button" value="Cancel"
                    onclick="CancelButton();">

            <input type="submit" name="EditButton" class="Button" value="Edit" 
                    id="EditButton" style="width: 75px;">
        </div>
    </div>
    
    <?php  include_once Footer; ?>

    <script type="text/javascript">
        var Name_Len = <?php echo Name_Len; ?>;
        var Phone_Len = <?php echo Phone_Len; ?>;
        var Address_Len = <?php echo Address_Len; ?>;

        var MyPage = '<?php echo Clients; ?>';
        var KeepSessionAlivePage = '<?php echo SimiPages; ?>KeepSessionAlive.php';
    </script>

</body>
</html>