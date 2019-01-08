$(document).ready(function(){
	
	$('#Submit').click(function(event){
		if ( CheckData() == true )
			GO('#NewTypeForm');
	})
})

function CheckData(){
	Result = true;

	if ( CheckLength('#Name', Name_Len) == false )
		Result = false;

	if ( CheckLength('#Factory', Name_Len) == false )
		Result = false;

	Result = CheckLenAndNumber(Result, '#WholesalePrice', Money_Len);
	Result = Dicount(Result, '#WholesaleDiscount', Money_Len);

	Result = CheckLenAndNumber(Result, '#RetailPrice', Money_Len);
	Result = Dicount(Result, '#RetailDiscount', Money_Len);
	
	return CheckLenAndNumber(Result, '#Q', Money_Len); 
}

function PutValue(){
    $('#Factory').val( $('#FactoryGroup :selected').text() );
}

function Dicount(Result, id){
	if ( $(id).val().length == 0 ){
		$(id).css('border-color', '#645A60');
		return Result;
	}

	if ( CheckLength(id, 3) == false )
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

function CheckLenAndNumber(Result, id, Len){
	if ( $(id).val().length == 0 ){
		$(id).css('border-color', '#645A60');
		return Result;
	}

	if ( CheckLength(id, Len) == false )
		Result = false;
	if ( isNumber(id) == false )
		Result = false;
	else{
		if ( $(id).val() < 0 ){
			$(id).css('border-color', 'red');
			Result = false;
		}
		else
			$(id).css('border-color', '#645A60');
	}
	return Result;
}
