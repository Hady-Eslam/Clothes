<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;

$GLOBALS['Page_API_Error_Code'] = 'P3';
set_error_handler("Error_Handeler");


/* 
                            Check if User Is Logging in
*/
$_SESSION['Page Name'] = 'Users';
$Result = array(-2 ,'');

if ( !isset($_SESSION['Name']) ){
    include_once UnAuthurithedUser;
    exit();
}

if ( $_SESSION['Status'] != '0' ){
    include_once UnAuthurithedUser;
    exit();
}

if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_SERVER["HTTP_REFERER"]) &&
    (
        ( isset($_POST['SaveButton']) && isset($_POST['Status']) && isset($_POST['N']) &&
          isset($_POST['P']) && isset($_POST['Ph']) ) ||

        ( isset($_POST['DeleteButton']) && isset($_POST['N']) ) ||

        ( isset($_POST['EditButton']) && isset($_POST['ID']) &&
          isset($_POST['EStatus']) && isset($_POST['Ph']) )
    )
){

    if ( isset($_GET['Result']) )
        unset($_GET['Result']);

    include_once URL;
    if ( $URL_REFERER->GetURLPath() == -1 ){
        include_once ErrorPage;
        exit();
    }

    if ( $URL_REFERER->URLResult != Users ){
        include_once UnAuthurithedUser;
        exit();
    }

/*
    - Return : 
        return array(0, 'Empty');
        return array(0, 'Too Long');
        return array(0, 'Wrong Data');
        return array(-1, $MySql->Error, 'Checking Name Found Or Not', 'CheckData');
        return array(0, 'Found');

        return array(-1, $MySql->Error, 'inserting User into Database', 'Check Data');
        return array(0, 'Added');

        return array(-1, $MySql->Error, 'Deleting User From DataBase', 'Delete User');
        return array(0, 'Deleted');

        return array(-1, $MySql->Error, 'Checking The User Found Or Not','Edit Data');
        return array(0, 'Not Found');
        return array(-1, $MySql->Error, 'Editing The User Data', 'Edit Data');
        return array(0, 'Edited');
*/
    include_once PagesPHP.'UsersProcessData.php';
    $Result = CheckData();
    if ( $Result[0] == 0 ){
        if ( $Result[1] == 'Empty' || $Result[1] == 'Too Long' ||
             $Result[1] == 'Wrong Data'
         ){
            header("Location:".Users);
            exit();
        }
        header("Location:".Users."?Result=".$Result[1]);
        exit();
    }
}
?>
<!DOCTYPE >
<html>
<head>
	<title>Users</title>
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
    <script src="<?php echo ConfirmPasswordScript; ?>"></script>
    <script src="<?php echo TriggerFormScript; ?>"></script>

    <script src="<?php echo PagesScripts; ?>UsersScript.js"></script>

    <style type="text/css">
        th{
            color: #40535F;
            margin: 0;
            padding: 10;
            padding-bottom: 30px;
            padding-top: 30px;
            border-left-width: 0px;
            border-left-style: solid;
            width: 200px;
            text-align: left;
            padding-left: 20px;
        }

        td{
            color: #40535F;
            font-size: 15px;
            padding: 10px;
        }

        tr{
            padding: 0;margin: 0;
        }

        .EditBox{
            font-size: 18px;
            position: fixed;
            top: 30px;
            background-color: #F1F1F1;
            left: 430px;
            width: 500px;
            height: 290px;
            border-style: solid;
            border-radius: 8px;
            border-width: 1px;
            border-color: #F1F1F1;
            z-index: 200;
            opacity: 1;
            visibility: hidden;
            color: #454747;
        }

        .DeleteBox{
            position: fixed;
            top: 200px;
            background-color: #F1F1F1;
            left: 440px;
            width: 360px;
            height: 120px;
            border-style: solid;
            border-radius: 8px;
            border-width: 1px;
            border-color: #F1F1F1;
            z-index: 200;
            opacity: 1;
            visibility: hidden;
            color: #2F3231;
        }

        .Box_P{
            border-bottom-style: solid;
            border-bottom-width: 1px;
            padding: 0px;
            margin: 0px;
            margin-left:50px;
            padding-left: 50px;
            margin-right: 50px;
        }

        .Box_ButtonDiv{
            text-align: center;
            padding: 0px;
            margin: 20px;
        }

        .Box_Button{
            background-color: #A4A7A7;
            border-color: #A4A7A7;
        }

        .EditBox_P{
            display: inline;
            padding-left: 35px;
            padding-right: 30px;
        }
    </style>
</head>

<body>

    <?php include_once UserHeader; ?>

    <section>
    
    <?php include_once UserWorkHeader; ?>

        <div class = 'SectionDiv'>

            <div class ='Title'>
                Users
            </div>

            <?php   include_once MessageBox; ?>

            <table style="margin-left: 0px;margin-top: 0px;padding-top: 0px;">
                <tbody>
                    <tr style="background-color: #D1D5CF; text-align: left;
                        border-width: 1px;border-style: solid;border-color: black;">
                        
                        <th style="width: 30px;">id</th>
                        <th style="width: 140px;">Status</th>
                        <th style="width: 150px;">Name</th>
                        <th style="width: 150px;">Phone</th>
                        <th style="width: 150px;">Password</th>
                    </tr>

                    <tr id="NewBox" style="text-align: left;">

                        <td>
                            
                        </td>
                        <td style="width: 100px;">
                            <input type="radio" name="Status" class="RadioButton"
                                value="User" checked>User
                            <input type="radio" name="Status" class="RadioButton"
                                value="Admin">Admin
                        </td>

                        <td style="padding: 0;margin: 0">
                            <input class='Input_Data' id='Name' type="text" 
                                name="N" placeholder="Enter Name" 
                                style="width: 150;"
                                oninput="CheckUser();">
                        </td>
                        <td style="padding: 0;margin: 0">
                            <input class='Input_Data' id='Phone' type="text" 
                                name="Ph" placeholder="Enter Phone"
                                style="width: 150">
                        </td>
                        <td style="padding: 0;margin: 0">
                            <input class='Input_Data' id='Password' type="password" 
                                name="P" placeholder="Enter Password" 
                                style="margin-bottom: 10px;width: 150">
                            <br>
                            <input class='Input_Data' id='ConPassword' type="password" 
                                placeholder="Re Enter Password"
                                style="margin-bottom: 0;width: 150;">
                        </td>

                        <td>
                            <input type="submit" value="Save User" class='Button' 
                                id="SaveButton" name="SaveButton">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            
                        </td>
                        
                        <td colspan="4" style="margin: 0;padding: 0;">
                            <p style="border-bottom-style: solid;border-bottom-width: 1px;margin: 0px;padding: 0;"></p>
                        </td>
                    </tr>

            <script type="text/javascript">
<?php
    if ( $Result[0] == -1 )
        include_once TrigerErrorMessage;
    else if ( isset($_GET['Result']) ){
        if ( $_GET['Result'] == 'Added' ){
        ?>
            SetMessage(2500, 'green', '<p>User Successfully Added</p>');
            setTimeout(function () {
                location.href = MyPage;
            }, 2500);
        <?php
        }
        else if ( $_GET['Result'] == 'Deleted' ){
        ?>
            SetMessage(2500, 'green', '<p>User Successfully Deleted</p>');
            setTimeout(function () {
                location.href = MyPage;
            }, 2500);
        <?php
        }
        else if ( $_GET['Result'] == 'Edited' ){
        ?>
            SetMessage(2500, 'green', '<p>User Successfully Edited</p>');
            setTimeout(function () {
                location.href = MyPage;
            }, 2500);
        <?php
        }
        else if ( $_GET['Result'] == 'Found' ){
        ?>
            $('#Name').css('border-color', 'red');
            SetMessage(4000, 'red', '<p>User Found</p>');
        <?php
        }
        else if ( $_GET['Result'] == 'Not Found' ){
        ?>
            SetMessage(2500, 'red', '<p>User Not Found</p>');
        <?php
        }
    }
?>
            </script>

<?php

    include_once MySqlDB;
    include_once HashClass;
    $Counter = 1;
    if ( ($Result = $MySql->FetchAllRows('SELECT * FROM users WHERE name != ?',
                array(
                    $Hashing->Hash_Users($_SESSION['Name'])
                ))) == -1 ){
        $Result = array(-1, $MySql->Error, 'Fetching Users Data', 'in Users');
    ?>
        <script type="text/javascript">
            <?php include_once TrigerErrorMessage; ?>
        </script>
    <?php
    }
    else
        foreach ($MySql->Fetched_Data as $Value) {

            echo '<tr style="text-align:center;">';
            echo '<td>'.$Counter.'</td>';
            if ( $Value['status'] == '0' )
                echo '<td>Admin</td>';
            else
                echo '<td>User</td>';

            echo '<td><p>';
            if ( $Hashing->Get_Hashed_Users($Value['name']) == -1 ){
                $Result = array(-1, $Hashing->Error, 'Getting User Name Heshed',
                                'in Users');
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

            if ( $Hashing->Get_Hashed_Users($Value['phone']) == -1 ){
                $Result = array(-1, $Hashing->Error, 'Getting User Phone Heshed',
                        'in Users');
                ?>
                    <script type="text/javascript">
                        <?php include_once TrigerErrorMessage; ?>
                    </script>
                <?php
                break;
            }
            echo '<td>'.$Hashing->HashedText.'</td>';
            $Phone = $Hashing->HashedText;
    ?>
    <td>
        
    </td>
    <td>
        <input type="image" src="<?php echo EditPicture; ?>" 
                width = '25' hight='25' style='margin-right: 20px;'
                onclick='EditClient(<?php
                        echo '"'.$Value['id'].'",';
                        echo '"'.$Value['status'].'",';
                        echo '"'.$Phone.'"';
                    ?>);'>

        <input type="image" src="<?php echo DeletePicture; ?>" 
                width = '25' hight='25'
                onclick='DeleteClient(<?php
                echo '"'.$Counter.'",';
                echo '"'.$Name.'"';
                    ?>);'>
    </td>
    <?php
            echo '</tr>';
    ?>
        <tr>
            <td>
                
            </td>
            
            <td colspan="4" style="margin: 0;padding: 0;">
                <p style="border-bottom-style: solid;border-bottom-width: 1px;margin: 0px;padding: 0;"></p>
            </td>
        </tr>
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
            <strong>Are You Sure Want To Delete This Client</strong></p>
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

            <div style="padding: 5px;margin: 5px;">
                <p class="EditBox_P"><strong>User Status </strong></p>

                <input type="radio" name="EStatus" class="RadioButton"
                    value="User" id="NewStatus1">User
                <input type="radio" name="EStatus" class="RadioButton"
                    value="Admin" id="NewStatus2">Admin
            </div>

        <!-- User Phone -->
            <div style="padding: 5px;margin: 5px;">
                <p class="EditBox_P"><strong>User Phone </strong></p>
                <input class='Input_Data' id='NewPhone' type="text" name="Ph"
                    placeholder="Enter User Phone">
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
        var Password_Len = <?php echo Password_Len; ?>;

        var CheckUserPage = '<?php echo SimiPages; ?>CheckUser.php';
        var MyPage = '<?php echo Users; ?>';
        var KeepSessionAlivePage = '<?php echo SimiPages; ?>KeepSessionAlive.php';
    </script>
</body>
</html>