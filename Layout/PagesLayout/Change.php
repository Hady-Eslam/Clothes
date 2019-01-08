<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;

$GLOBALS['Page_API_Error_Code'] = 'P2';
set_error_handler("Error_Handeler");


/* 
                            Check if User Is Logging in
*/
$_SESSION['Page Name'] = 'Change Password';
$Result = array(-2 ,'');

if ( !isset($_SESSION['Name']) ){
    include_once UnAuthurithedUser;
    exit();
}

if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['OP'])
    && isset($_POST['P']) && isset($_SERVER["HTTP_REFERER"]) ){

    include_once URL;
    if ( $URL_REFERER->GetURLPath() == -1 ){
        include_once ErrorPage;
        exit();
    }

    if ( $URL_REFERER->URLResult != Change ){
        include_once UnAuthurithedUser;
        exit();
    }
/*
    CheckData : 
        return array(0, 'Empty');
        return array(0, 'Too Long');

        return array(-1, $MySql->Error, 'Check The Password is Right', 'Check Data');
        return array(0, 'Wrong Password');
        return array(-1, $MySql->Error, 'inserting Password into DataBase', 'Check Data');
        return array(0, 'Done');
*/
    include_once PagesPHP.'ChangeProcessData.php';
    $Result = CheckData();
    if ( $Result[0] == 0 ){

        if ( $Result[1] == 'Empty' || $Result[1] == 'Too Long' ){
            header("Location:".Change);
            exit();
        }
    }
}
?>
<!DOCTYPE >
<html>
<head>
	<title>Change Password</title>
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

    <script src="<?php echo PagesScripts; ?>ChangeScript.js"></script>
</head>

<body>

    <?php include_once UserHeader; ?>

    <section>
    
    <?php include_once UserWorkHeader; ?>

        <div class = 'SectionDiv'>

            <div class ='Title'>
                Change Password
            </div>

            <?php   include_once MessageBox; ?>

            <form id='ChangeForm' method="post" enctype="multipart/form-data"
                action="<?php echo $_SERVER['PHP_SELF']; ?>">
                
                <div>
                    <input class='Input_Data' id='OldPassword' type="Password" name="OP"
                        placeholder="Enter Old Password" 
                        oninput="CheckinputLen(this, Password_Len);">
                </div>

                <div>
                    <input class='Input_Data' id='Password' type="Password" name="P"
                        placeholder="Enter New Password"
                        oninput="CheckinputLen(this, Password_Len);">
                </div>

                <div>
                    <input class='Input_Data' id='ConPassword' type="Password"
                        placeholder="Confirm Password"
                        oninput="CheckinputLen(this, Password_Len);">
                </div>

                <div>
                    <input type="submit" value="Change Password" class='Button'>
                </div>

            </form>
        </div>

    </section>
    
    <?php  include_once Footer; ?>

    <script type="text/javascript">
        var Password_Len = <?php echo Password_Len; ?>;
        
        var MyPage = '<?php echo Change; ?>';
        var KeepSessionAlivePage = '<?php echo SimiPages; ?>KeepSessionAlive.php';
    </script>

    <script type="text/javascript">
<?php
    if ( $Result[0] == 0 ){
        if ( $Result[1] == 'Wrong Password' ){
        ?>
            $('#OldPassword').css('border-color', 'red');
        <?php
        }
        else{
        ?>
            SetMessage(2500, 'green', '<p>Password Changed</p>');
            setTimeout(function () {
                location.href = MyPage;
            }, 2500);
        <?php
        }
    }
    else if ( $Result[0] == -1 )
        include_once TrigerErrorMessage;
?>
    </script>
</body>
</html>