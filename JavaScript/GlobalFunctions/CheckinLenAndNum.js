function CheckinLenAndNum(The_Object, Len){
	if ( $('#'+The_Object.id).val().length == 0 ){
		$('#'+The_Object.id).css('border-color', '#645A60');
		return false;
	}
    Result = CheckLength('#'+The_Object.id, Len);
    Result1 = isNumber('#'+The_Object.id);
    if ( Result == true && Result1 == true ){
        $('#'+The_Object.id).css('border-color', '#645A60');
        return true;
    }
    else{
    	$('#'+The_Object.id).css('border-color', 'red');
        return false;
    }
}