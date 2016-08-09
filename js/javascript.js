$(document).ready(function(){
	$.getJSON("js/smileys.json", function(data){
		$.each(data, function(index, value){
			var _this = $(value),
				img = '<img src="'+ _this[0].src +'" alt="'+ _this[0].alt +'" width="20px" height="20px" data-toggle="tooltip" title="'+ _this[0].alt +'"/>';
			$('#smileys').prepend(img);
		})
		$('[data-toggle="tooltip"]').tooltip();
	})
})

$(document).on('click', '#smileys img', function(){
	$('#snd_msg').val($('#snd_msg').val() + ' ' + this.alt + ' ').focus();
})

$('[data-type="toggleSmileys"] a').on('click', function(){
	var _this = $(this);
	_this.text((_this.text() == _this.attr('data-default-text')) ? _this.attr('data-toggle-text') : _this.attr('data-default-text'));
	$('#smileys').fadeToggle();
})