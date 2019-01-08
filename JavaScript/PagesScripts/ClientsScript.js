$(document).ready(function(){

	$('#DeleteButton').click(function(event){
        GO('#DeleteBox');
	})

    $('#EditButton').click(function(event){
        if ( CheckData() == true )
            GO('#EditBox');
    })

    $('#NewButton').click(function(event){
        if ( CheckData1() == true )
            GO('#NewBox');
    })
})

function CheckData(){
	Result = true;
    if ( CheckLength('#Name', Name_Len ) == false )
        Result = false;

    if ( $('#Phone1').val().length > 0 ){
        if ( CheckLength('#Phone1', Phone_Len ) == false )
            Result = false;
    }

    if ( $('#Phone2').val().length > 0 ){
        if ( CheckLength('#Phone2', Phone_Len ) == false )
            Result = false;
    }

    if ( $('#Phone3').val().length > 0 ){
        if ( CheckLength('#Phone3', Phone_Len ) == false )
            Result = false;
    }

    if ( $('#Address').val().length > 0 ){
        if ( CheckLength('#Address', Address_Len ) == false )
            Result = false;
    }
    return Result;
}

function CheckData1(){
    Result = true;
    if ( CheckLength('#NewName', Name_Len ) == false )
        Result = false;

    if ( $('#NewPhone1').val().length > 0 ){
        if ( CheckLength('#NewPhone1', Phone_Len ) == false )
            Result = false;
    }

    if ( $('#NewPhone2').val().length > 0 ){
        if ( CheckLength('#NewPhone2', Phone_Len ) == false )
            Result = false;
    }

    if ( $('#NewPhone3').val().length > 0 ){
        if ( CheckLength('#NewPhone3', Phone_Len ) == false )
            Result = false;
    }

    if ( $('#NewAddress').val().length > 0 ){
        if ( CheckLength('#NewAddress', Address_Len ) == false )
            Result = false;
    }
    return Result;
}

function CancelButton(){
    $('body').css('background-color', '#EDF3E9');
    $('body').children().not('#EditBox').css('opacity', '1');
    $('#EditBox').css('visibility','hidden');
    $('#DeleteBox').css('visibility','hidden');
}


function DeleteClient(ID, Name){
    $('body').css('background-color', 'black');
    $('body').children().not('#DeleteBox').css('opacity', '0.2');
    $('#EditBox').css('visibility','hidden');
    $('#DeleteBox').css('opacity','1');
    $('#DeleteBox').css('visibility','visible');

    $('#DeleteName').val(Name);

    $('#Deleteid').html('<strong>With ID ( '+ID+' ) ?</strong>');
}

function EditClient(ID, Name, Phone1, Phone2, Phone3, Address){
    $('body').css('background-color', 'black');
    $('body').children().not('#EditBox').css('opacity', '0.2');
    $('#DeleteBox').css('visibility','hidden');
    $('#EditBox').css('opacity','1');
    $('#EditBox').css('visibility','visible');

    $('#EditID').val(ID);
    $('#Name').val(Name);
    $('#Phone1').val(Phone1);
    $('#Phone2').val(Phone2);
    $('#Phone3').val(Phone3);
    $('#Address').val(Address);
}