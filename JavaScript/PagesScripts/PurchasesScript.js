function PutValue(){
    $('#Name').val( $('#CustomerGroup :selected').text() );
}

function GetCount(){

    if ( $('#ID').val().length == 0 ){
        $('#ID').css('border-color', '#645A60');
        $('#Store').val('');
        $('#Store').css('border-color', '#645A60');
        $('#WSP').val('');
        $('#WSP').css('border-color', '#645A60');
        $('#Result').text('');
        return ;
    }
    else if ( CheckLength('#ID', ID_Len) == false ){
        $('#ID').css('border-color', 'red');
        $('#Store').val('');
        $('#WSP').val('');
        $('#Result').text('');
        return ;
    }

    $.ajax({
        type : "POST",
        url : GetTypeDataPage,
        data : 'Status=1&ID='+$('#ID').val(),
        error: function (jqXHR, exception) {
            internetError(jqXHR);
            return false;
        },
        
        success : function(Data){
            alert(Data);
            try{
                Data = JSON.parse(Data);
                if ( Data['Result'] == 0 ){
                    
                    if ( typeof Data['Object'] == 'object' ){
                        $('#Result').text('');
                        $('#Store').val(Data['Object'][0]);
                        $('#WSP').val(Data['Object'][1]);
                        $('#Result').text('');

                        if ( Data['Object'][0] == 0 ){
                            $('#Store').css('border-color', 'red');
                            $('#WSP').css('border-color', 'red');
                            $('#Result').text('The Store is Empty');
                        }
                    }
                    else{
                        $('#Store').val('');
                        $('#WSP').val('');
                        $('#Result').text('Not Found');
                        return true;
                    }
                }
                else{
                    SetMessage(5000, '#E30300',
                        '<p>Error Meaasege Code : '+Data['Object']['Error Code']+
                        '</p><p>Something Goes Wrong</p>');
                    
                    SetConsoleErrorMessage('in Getting Type Count', 'in Get Type Count',
                        Data['Object']['Error Location'], Data['Object']['Error Code'],
                        Data['Object']['Error Message']);
                }
            }
            catch(e){
                SetMessage(5000, '#E30300',
                        '<p>Error Meaasege Code : 11'+
                        '</p><p>Something Goes Wrong</p>');

                SetConsoleErrorMessage('in Getting Type Count', 'in Get Type Count',
                        'in Bill.js', '11', 'Failed To Covert JSON');
            }
        }
    });
    return false;
}

function internetError(jqXHR){
    if ( jqXHR['status'] == 404 )
        console.log('Page Not Found');
    else if ( jqXHR['status'] == 403 )
        console.log('Page Forbidden');
    else if ( jqXHR['status'] == 0 ){
        console.log('Connection Error');
        SetMessage(5000, '#E30300',
            '<p>Failed To Send Data</p>'+
            '<p>There Must Be Connection Error</p>'+
            '<p>Please Check internet</p>');
    }
}

function MakeNewRow(){
    if ( Count_Rows >= 15 ){
        alert('This is The Limit');
        return;
    }
    Count_Rows++;

    $('<tr>'+
        '<td style="padding: 5;margin:0 ;">'+
            '<input type="text" class="Input_Data" style="width: 70px;margin: 0;" '+
                'placeholder="Enter id" id="TypeID'+Count_Rows+
                '" oninput="PutData('+Count_Rows+');" name="TypeID'+Count_Rows+'">'+
        '</td>'+
        '<td style="padding: 5;margin:0 ;">'+
            '<input type="text" class="Input_ReadOnly" id="TypeName'+Count_Rows+'" '+
                'style="width: 200px;margin: 0;" readonly placeholder="Name" '+
                'name="TypeName'+Count_Rows+'">'+
        '</td>'+
        '<td style="padding: 5;margin:0 ;">'+
            '<input type="text" class="Input_Data" id="TypeQuantity'+Count_Rows+'"'+
                'style="width: 70px;margin: 0;" placeholder="Quantity"'+
                'oninput="MakePrice('+Count_Rows+');" name="TypeQuantity'+
                    Count_Rows+'">'+
        '</td>'+
        '<td style="padding: 5;margin:0 ;">'+
            '<input type="text" class="Input_Data" id="TypePrice'+Count_Rows+'" '+
                'style="width: 70px;margin: 0;" placeholder="Price"'+
                'oninput="MakePrice('+Count_Rows+');"'+
                'name="TypePrice'+Count_Rows+'">'+
        '</td>'+
        '<td style="padding: 5;margin:0 ;">'+
            '<input type="text" class="Input_Data"'+
                'id="TypeDiscount'+Count_Rows+'" style="width: 70px;margin: 0;"'+
                'placeholder="0" name="TypeDiscount'+Count_Rows+
                '" oninput="GetTypeDiscount('+Count_Rows+')"> %'+

            '<input type="hidden" id="TypeDiscountHidden'+Count_Rows+'" value="0"'+
                'name="TypeDiscountHidden'+Count_Rows+'">'+
        '</td>'+
        '<td style="padding: 5;margin:0 ;">'+
            '<input type="text" class="Input_ReadOnly" readonly '+
                'style="width: 70px;margin: 0;" id="TypeTotall'+Count_Rows+'" '+
                'placeholder="Totall" name="TypeTotall'+Count_Rows+'">'+
        '</td>'+
        '<td style="padding: 5;margin:0 ;">'+
            '<input class="Input_Data" style="width: 250px;margin: 0;"'+
                'type="text" id="TypeNote'+Count_Rows+'" placeholder="Enter Notes"'+
                'oninput="CheckinputLen(this, Note_Len);"'+
                    'name="TypeNote'+Count_Rows+'">'+
        '</td>'+
        '<td style="padding: 5;margin:0 ;">'+
            '<input type="image" src="'+AddPicture+'"'+
                'width="30" hieght="20"'+
                'style="margin-left: 10px;" onclick="MakeNewRow();">'+
            
            '<strong style="font-size: 20px;"> '+Count_Rows+'</strong>'+
        '</td>'+
    '</tr>').appendTo('#Table');
}

function DiscountForAll(The_Object){
    if ( CheckinLenAndNum(The_Object, Money_Len) == false ){
        $('#TotalMoneyWithDiscount').val( $('#TotalMoney').val() );
        return ;
    }
    SetTotalWithDiscount();
}

function DiscountForAllMoney(){
    if ( Discount(true, '#AllDiscount') == false ){
        $('#TotalMoneyWithDiscount').val( $('#TotalMoney').val() );
        return ;
    }
    SetTotalWithDiscount();
}

function SetTotalWithDiscount(){
    Money = 0;
    if ( Number($('#TotalMoney').val()) != NaN ){
        if ( $('#TotalMoney').val().length != 0 )
            Money = Number($('#TotalMoney').val());
    }

    if ( Discount(true, '#AllDiscount') == true ){
        if ( $('#AllDiscount').val().length != 0 ){
            The_Discount = 100.0 - Number($('#AllDiscount').val());
            $('#TotalMoneyWithDiscount').val( (The_Discount * Money) / 100.0 );
        }
        else{
            if ( Number($('#Discount').val()) != NaN ){
                The_Discount = Number($('#Discount').val());
                $('#TotalMoneyWithDiscount').val( Money - The_Discount );
            }
            else
                $('#TotalMoneyWithDiscount').val( Money );
        }
    }
    else{
        if ( Number($('#Discount').val()) != NaN ){
            The_Discount = Number($('#Discount').val());
            $('#TotalMoneyWithDiscount').val( Money - The_Discount );
        }
        else
            $('#TotalMoneyWithDiscount').val( Money );
    }
}

function Discount(Result, id){
    if ( $(id).val().length == 0 ){
        $(id).css('border-color', '#645A60');
        return Result;
    }

    if ( CheckLength(id, 6) == false )
        Result = false;
    if ( isNumber(id) == false )
        Result = false;
    else{
        if ( $(id).val() < 0 || $(id).val() > 100 ){
            $(id).css('border-color', 'red');
            Result = false;
        }
        else
            $(id).css('border-color', '#645A60');
    }
    return Result;
}

function SetTotal(){
    if ( CheckinLenAndNum( document.getElementById('Payied'), Money_Len) == false ){

        return ;
    }

    Money = 0;
    if ( Number($('#TotalMoney').val()) != NaN ){
        if ( $('#TotalMoney').val().length != 0 )
            Money = Number($('#TotalMoney').val());
    }
    if ( Number($('#Payied').val()) != NaN ){
        if ( $('#Payied').val().length != 0 )
            $('#Remainder').val( Money - Number($('#Payied').val()) );
        else
            $('#Remainder').val( Money );
    }
}

function MakePrice(Num){
    if ( CheckinLenAndNum( 
            document.getElementById('TypePrice'+Num), Money_Len) == false )
        return ;
    
    Count = 1;
    if ( Number($('#TypeQuantity'+Num).val()) != NaN )
        Count = Number($('#TypeQuantity'+Num).val());

    Count = Number($('#TypePrice'+Num).val()) * Count;
    The_Discount = Number($('#TypeDiscountHidden'+Num).val());
    The_Discount = 100.0 - The_Discount;
    $('#TypeTotall'+Num).val( (Count * The_Discount) / 100.0 );
    GetTotal();
}

function GetTotal(){
    Money = 0;
    for ( i = 1 ;i <= Count_Rows; i++ ){
        if ( Number($('#TypeTotall'+i).val()) != NaN ){
            if ( $('#TypeTotall'+i).val().length != 0 )
                Money += Number($('#TypeTotall'+i).val());
        }
    }
    $('#Payied').val( Money );
    $('#TotalMoney').val( Money );
    
    if ( Number($('#Payied').val()) != NaN ){
        if ( $('#Payied').val().length != 0 )
            $('#Remainder').val( Money - Number($('#Payied').val()) );
        else
            $('#Remainder').val( Money );
    }
    SetTotalWithDiscount();
}

function PutData(The_ID){

    if ( $('#TypeID'+The_ID).val().length == 0 ){
        $('#TypeID'+The_ID).css('border-color', '#645A60');
        Empty(The_ID);
        GetTotal();
        return ;
    }
    else if ( CheckLength('#TypeID'+The_ID, ID_Len) == false ){
        $('#TypeID'+The_ID).css('border-color', 'red');
        Empty(The_ID);
        GetTotal();
        return ;
    }

    $.ajax({
        type : "POST",
        url : GetTypeDataPage,
        data : 'Status=2&ID='+$('#TypeID'+The_ID).val(),
        error: function (jqXHR, exception) {
            internetError(jqXHR);
            return false;
        },
        
        success : function(Data){
            alert(Data);
            try{
                Data = JSON.parse(Data);
                if ( Data['Result'] == 0 ){
                    
                    if ( typeof Data['Object'] == 'object' ){
                        $('#Store').val(Data['Object'][0]);
                        $('#WSP').val(Data['Object'][1]);
                        $('#TypeName'+The_ID).val(Data['Object'][2]);
                        $('#TypePrice'+The_ID).val(Data['Object'][3]);
                        $('#TypeDiscount'+The_ID).val(Data['Object'][4]);
                        $('#TypeQuantity'+The_ID).val('1');
                        $('#TypeDiscountHidden'+The_ID).val(Data['Object'][4]);
                        MakePrice(The_ID);
                        $('#Result').text('');

                        $('#Store').css('border-color', '#645A60');
                        $('#WSP').css('border-color', '#645A60');
                        $('#TypeName'+The_ID).css('border-color', '#645A60');
                        $('#TypePrice'+The_ID).css('border-color', '#645A60');
                        $('#TypeTotall'+The_ID).css('border-color', '#645A60');
                        $('#TypeDiscount'+The_ID).css('border-color', '#645A60');
                        $('#TypeQuantity'+The_ID).css('border-color', '#645A60');
                    }
                    else{
                        Empty(The_ID);
                        $('#Result').text('Not Found');
                        return true;
                    }
                }
                else{
                    SetMessage(5000, '#E30300',
                        '<p>Error Meaasege Code : '+Data['Object']['Error Code']+
                        '</p><p>Something Goes Wrong</p>');
                    
                    SetConsoleErrorMessage('in Getting Type Count', 'in Get Type Count',
                        Data['Object']['Error Location'], Data['Object']['Error Code'],
                        Data['Object']['Error Message']);
                }
            }
            catch(e){
                SetMessage(5000, '#E30300',
                        '<p>Error Meaasege Code : 11'+
                        '</p><p>Something Goes Wrong</p>');

                SetConsoleErrorMessage('in Getting Type Count', 'in Get Type Count',
                        'in Bill.js', '11', 'Failed To Covert JSON');
            }
        }
    });
    return false;
}

function Empty(The_ID){
    $('#Store').val('');
    $('#Store').css('border-color', '#645A60');
    
    $('#WSP').val('');
    $('#WSP').css('border-color', '#645A60');
    
    $('#TypeName'+The_ID).val('');
    $('#TypeName'+The_ID).css('border-color', '#645A60');
    
    $('#TypePrice'+The_ID).val('');
    $('#TypePrice'+The_ID).css('border-color', '#645A60');
    
    $('#TypeTotall'+The_ID).val('');
    $('#TypeTotall'+The_ID).css('border-color', '#645A60');

    $('#TypeDiscount'+The_ID).val('');
    $('#TypeDiscount'+The_ID).css('border-color', '#645A60');
    
    $('#TypeQuantity'+The_ID).val('');
    $('#TypeQuantity'+The_ID).css('border-color', '#645A60');
    
    $('#TypeDiscountHidden'+The_ID).val('');
    $('#Result').text('');
}

function GetTypeDiscount(Num){
    if ( $('#TypeDiscount'+Num).val().length == 0 )
        $('#TypeDiscountHidden'+Num).val('0');
    else if ( Discount(true, '#TypeDiscount'+Num) == false )
        return ;
    
    $('#TypeDiscountHidden'+Num).val( $('#TypeDiscount'+Num).val() );
    MakePrice(Num);
}

$(document).ready(function(){

    $('#SavePurchase').click(function(){
        if ( CheckData() == true )
            SaveBill();
    })
})

function CheckData(){
    Result = true;
    Found = false;
//
    //The Customer
    if ( CheckLength('#Name', Name_Len) == false )
        Result = false;
    
    //The Note
    if ( $('#Note').val().length > 0 ){
        if ( CheckLength('#Note', Note_Len) == false )
            Result = false;
    }
//
    for ( i = 1; i <= Count_Rows; i++ ){
        if ( $('#TypeID'+i).val().length != 0 ){
            if ( $('#TypeName'+i).val().length != 0 ){

                if ( CheckinLenAndNum( 
                    document.getElementById('TypeQuantity'+i), Money_Len) == false )
                    Result = false;

                if ( Discount(true, '#TypeDiscount'+i) == false )
                    Result = false;

                if ( CheckinLenAndNum( 
                    document.getElementById('TypePrice'+i), Money_Len) == false )
                    Result = false;
                
                if ( $('#TypeNote'+i).val().length > 0 ){
                    if ( CheckLength('#TypeNote'+i, Note_Len) == false )
                        Result = false;
                }
                Found = true;
            }
        }
    }

    if ( $('#Discount').val().length > 0 ){
        if ( CheckinLenAndNum(
            document.getElementById('Discount'), Money_Len) == false )
            Result = false;
    }
    
    if ( $('#AllDiscount').val().length > 0 ){
        if ( Discount(true, '#AllDiscount') == false )
            Result = false;
    }
    
    if ( $('#Payied').val().length > 0 ){
        if ( CheckinLenAndNum( document.getElementById('Payied'), Money_Len) == false ){
            $('#Payied').css('border-color', 'red');
            Result = false;
        }
    }
    
    if ( Found == false ){
        Result = false;
        alert('You Must Put Value');
    }
    return Result;
}

function GetData(){
    Data = '';
//
    //The Customer
    Data += 'Status=2';
    Data += '&Name='+$('#Name').val();
    Data += '&Buying='+$('input[name=Buying]:checked').val();
    Data += '&Note='+$('#Note').val();
//
    for ( i = 1; i <= Count_Rows; i++ ){
        if ( $('#TypeID'+i).val().length != 0 ){
            if ( $('#TypeName'+i).val().length != 0 ){

                Data += '&TypeID'+i+'='+$('#TypeID'+i).val();

                Data += '&TypeQuantity'+i+'='+$('#TypeQuantity'+i).val();
                Data += '&TypePrice'+i+'='+$('#TypePrice'+i).val();
                Data += '&TypeDiscount'+i+'='+$('#TypeDiscount'+i).val();
                Data += '&TypeNote'+i+'='+$('#TypeNote'+i).val();
            }
        }
    }

    Data += '&Discount='+$('#Discount').val();
    Data += '&AllDiscount='+$('#AllDiscount').val();
    Data += '&Payied='+$('#Payied').val();
    return Data;
}

function SaveBill(){
alert(GetData());
    $.ajax({
        type : "POST",
        url : SaveBillPage,
        data : GetData(),
        error: function (jqXHR, exception) {
            internetError(jqXHR);
            return false;
        },
        
        success : function(Data){
            alert(Data);
            try{
                Data = JSON.parse(Data);
                if ( Data['Result'] == 0 ){
                    
                    if ( Data['Object'] != 'Done' )
                        SetMessage(5000, 'red',
                            '<p>Data is : '+Data['Object']+
                            ' ... Please Check Data</p>');
                    else{
                        SetMessage(2500, 'green', '<p>The Purchase is Saved</p>');
                        setTimeout(function () {
                            location.href = MyPage;
                        }, 2500);
                    }
                }
                else{
                    SetMessage(5000, '#E30300',
                        '<p>Error Meaasege Code : '+Data['Object']['Error Code']+
                        '</p><p>Something Goes Wrong</p>');
                    
                    SetConsoleErrorMessage(Data['Doing'], Data['Where'],
                        Data['Object']['Error Location'], Data['Object']['Error Code'],
                        Data['Object']['Error Message']);
                }
            }
            catch(e){
                SetMessage(5000, '#E30300',
                        '<p>Error Meaasege Code : 11'+
                        '</p><p>Something Goes Wrong</p>');

                SetConsoleErrorMessage('in Saving Bill', 'in Save Data',
                        'in Bill.js', '11', 'Failed To Covert JSON');
            }
        }
    });
    return false;
}