var ShowPopUp = function(text) {
    $('#wrapper').append(text);
    $('.PopUpHeader a').bind('click', function() {
        ClosePopUp();
    });
};
var ClosePopUp = function() {
    $('.PopUpBg').addClass('hide');
};
var SubmitFrom = function() { //Submit Form
    $('#mainForm').submit();
};

$(function() {
	$('input').keypress(function(e){
    	if (e.which == 13) {
    		$('form').submit();
    	}
    });
});