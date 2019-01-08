/*
    - info :
        javascript page     =>  ConfirmPassword.js
        init name           =>  ConfirmPasswordScript

    -The Scripts it Depends On (ROOT Name) :
        JQueryScript

    - Data Needed :
        id  => #Password
        id  => #ConPassword
*/

function CheckPassword(){
    Pass = $('#Password').val();
    ConPass = $('#ConPassword').val();
    if ( Pass === ConPass )
        return true;
    $('#ConPassword').css('border-color','red');
    return false;
}