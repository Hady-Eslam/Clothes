$(document).ready(function(){

    $('#PaySubmit').click(function(){
        if ( CheckData() == true )
            GO('#PayBox');
    })

    $('#GetSubmit').click(function(){
        if ( CheckData() == true )
            GO('#GetBox');
    })
})

function PutValue(){
    $('#Name').val( $('#GName :selected').text() );
}

function CheckData(){
    Result = true;
    if ( CheckLength('#Name', Name_Len ) == false )
        Result = false;

    if ( CheckLength('#Money', Money_Len ) == false )
        Result = false;
    if ( isNumber('#Money') == false )
        Result = false;

    if ( $('#Note').val().length > 0 ){
        if ( CheckLength('#Note', Note_Len ) == false )
            Result = false;
    }
    return Result;
}