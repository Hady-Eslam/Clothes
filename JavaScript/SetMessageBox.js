/*
    - info :
        javascript page     =>  SetMessageBox.js
        init name           =>  SetMessageBoxScript

    -The Scripts it Depends On (ROOT Name) :
        JQueryScript

    - Data Needed :
        id  => #MessageBox
*/

function SetMessage(Timeout, BackgroundColor, Text){
    $('#MessageBox').empty();
    $(Text).appendTo('#MessageBox');
    left  = (screen.width)/2 - (document.getElementById("MessageBox").clientWidth)/2;

    $('#MessageBox').css('background-color', BackgroundColor)
              		.css('left', left);

    $('#MessageBox').css('visibility','visible');

    setTimeout(function () {
        $('#MessageBox').css('visibility','hidden');
    }, Timeout);
}