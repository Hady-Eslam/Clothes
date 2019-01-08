$(document).ready(function(){

	$('#SaveButton').click(function(){
        if ( CheckData() == true )
            GO('#NewBox');
    })

    $('#DeleteButton').click(function(event){
        GO('#DeleteBox');
    })

    $('#EditButton').click(function(event){
        GO('#EditBox');
    })
})

function CheckData(){
	Result = true;
    if ( CheckLength('#Name', Name_Len ) == false )
        Result = false;

    if ( $('#Phone').val().length > 0 ){
        if ( CheckLength('#Phone', Phone_Len ) == false )
            Result = false;
    }

    if ( CheckLength('#Password', Password_Len ) == false )
        Result = false;

    if ( CheckLength('#ConPassword', Password_Len ) == false )
        Result = false;

    if ( CheckPassword() == false )
    	Result = false;
    return Result;
}

function CheckUser(){
    if ( $('#Name').val().length == 0 ){
        $('#Name').css('border-color', '#645A60');
        return ;
    }
    else if ( CheckLength('#Name', Name_Len) == false ){
        $('#Name').css('border-color', 'red');
        return ;
    }

    $.ajax({
        type : "POST",
        url : CheckUserPage,
        data : 'N='+$('#Name').val(),
        error: function (jqXHR, exception) {
            console.log(jqXHR);
            return false;
        },
        
        success : function(Data){
            try{
                Data = JSON.parse(Data);
                if ( Data['Result'] == 0 ){
                    
                    if ( Data['Object'] == 'Empty' || Data['Object'] == 'Too Long' )
                        $('#Name').css('border-color','red');
                    
                    else if ( Data['Object'] == 'Found' )
                        $('#Name').css('border-color','red');
                    else{
                        $('#Name').css('border-color','green');
                        return true;
                    }
                }
                else{
                    SetMessage(2500, 'red',
                    '<p>Error Meaasege Code : '+Data['Object']['Error Code']+
                    '</p><p>Something Goes Wrong</p>');
                
                    SetConsoleErrorMessage('in Checking User', 'in Check User',
                        Data['Object']['Error Location'], Data['Object']['Error Code'],
                        Data['Object']['Error Message']);
                }
            }
            catch(e){
                SetMessage(5000, '#E30300',
                        '<p>Error Meaasege Code : 11'+
                        '</p><p>Something Goes Wrong</p>');

                SetConsoleErrorMessage('in Checking User', 'in Check User',
                        'in UserScript.js', '11', 'Failed To Covert JSON');
            }    
        }
    });
    return false;
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

function EditClient(ID, Status, Phone){
    $('body').css('background-color', 'black');
    $('body').children().not('#EditBox').css('opacity', '0.2');
    $('#DeleteBox').css('visibility','hidden');
    $('#EditBox').css('opacity','1');
    $('#EditBox').css('visibility','visible');

    $('#EditID').val(ID);
    if ( Status == '1' ){
        $('#NewStatus1').attr('checked', true);
    }
    else{
        $('#NewStatus2').attr('checked', true);
    }
    $('#NewPhone').val(Phone);
}