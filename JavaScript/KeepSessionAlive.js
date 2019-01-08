$(document).ready(function(){

	KeepSessionAlive();
})

function KeepSessionAlive(){

	setTimeout(function () {
	    $.ajax({
	        type : "POST",
	        url : KeepSessionAlivePage,
	        error: function (jqXHR, exception) {

	        },
	        success : function(){
	        	
	        }
	    })
	    KeepSessionAlive();
    }, 60000 );
}