function preventDefault(e) {
	e.preventDefault()
};

function notify(message) {
	var close = '<a href="" class="close action"><span class="replace">Close</span></a>';
	var info = '';
	$('.notify').addClass('hide');
	setTimeout(function() {
		$('.notify .message').text(message).append(info).append(close);
		$('.notify').removeClass('hide').fadeIn('slow');
		$('.notify').delay(10000).fadeOut('slow'); 
	}, 500);
	return $('.notify');
};

function gvaction(ACTIONS, ID, RETURN, TEXT){
      $.post('gvactions.php', { action: ACTIONS, messageId: ID, messagenote: TEXT }, 
	  function(data) { 
		notify(data);
			if (ACTIONS == 'delete'){
				if (RETURN){
					window.location.href = 'gvdashboard.php?command=' + RETURN;
					} else {
					window.location.href = 'gvdashboard.php';
					}
				}
		});
};


jQuery.fn.buttonista = function(options) {
	var settings = jQuery.extend({
		menu : 'ul',
		toggler : '.toggler'
	}, options);
	
	var toggleMenu = function() {
		$(this).parent()
			.children(settings.menu).toggleClass('open');
		if($(this).parent().children(settings.menu).hasClass('open') &&
			typeof settings.focus != 'undefined')
		{
			$(this).parent()
				.children(settings.menu).children(settings.focus).focus()
		}
		return false;
	};
	
	var closeMenu = function(event) {
		if (event.keyCode == '27') {
			$(settings.menu).removeClass('open');
		}
	};

	$(window).keypress( closeMenu );

	return this.each(function() {
		var link = $(this);
		link.click( toggleMenu );

		var toggler = $(settings.toggler, link.parent());
		toggler.click(function(event) {
			event.preventDefault();

			link.click();
		});

	});
};

$(document).ready(function() {

	$('.caller-id-phone a').click(function() {
		var anchor = $(this);
		var popup = $(this).parents('.quick-call-popup');
		if(anchor.parents('ul.caller-id-phone')) {
			anchor.hide()
				.parents('ul')
				.append('<li class="calling">Calling...</li>');

		} else {
			anchor.hide()
				.parent()
				.append('<li class="calling">Calling...</li>');
		}
		$.ajax({
				url : "gvactions.php",
				data : {
					action : "callNumber",
					numberToCall : $('.to', popup).text()
				},
				dataType: 'html',
				success : function(data) {
					$('.quick-call-popup .calling').remove();
					$('.quick-call-popup.open').toggleClass('open');
					notify(data);
					anchor.show();
				},
				type : 'POST'
			});		
		return true;
	});




	$('.quick-sms-popup .send-button').click(function(event) {
		var popup = $(this).parents('.quick-sms-popup');
		$('.sending-sms-loader').show();
		$.ajax({ 
			url : "gvactions.php",
			data : {
				action : "sendText",
				to : $('.sms-to-phone', popup).text(),
				content : $('input[name="content"]', popup).val()
			},
			dataType: 'html',
			success : function(data) {
				$('.sending-sms-loader').hide();
				if(!data.error) {
					$('textarea', popup).val('');
					$(popup).hide();
					notify(data);
				}
			},
			error : function() {
				$('.sending-sms-loader').hide();
			},
			type : 'POST'
		});
		event.preventDefault();
	});


	$('.quick-call-button').buttonista({ menu : '.quick-call-popup' });
	$('.quick-sms-button').buttonista({ menu : '.quick-sms-popup', toggler : '.sms-toggler', focus: '.sms-message' });
	var updateCount = function() {
		var length = $(this).val().length;
		$(this).parents('.quick-sms-popup, #reply-sms')
			.find('.count')
			.text(1600 - length);
	};
	$('.quick-sms-popup input[name="content"]').live('keyup', updateCount);
	$('.quick-sms-popup input[name="content"]').keypress();
	$('#reply-sms textarea').live('keyup', updateCount);
	$('#reply-sms textarea').live('keyup', updateCount);
	$('#reply-sms textarea').keypress();
	$('#reply-sms .submit-button').click(function(event) {
		event.preventDefault();
		$('#reply-sms button').prop('disabled', true);
		$('#reply-sms .loader').show();
		$.ajax({
			url : $('#reply-sms').attr('action'),
			data : $('#reply-sms').serializeArray(),
			success : function(data) {
				$('#reply-sms .loader').hide();
				$.notify(data);
				$('#reply-sms textarea').val('');
				$('#reply-sms .submit-button').prop('disabled', false).flicker();
				window.location.href = 'gvdetails.php?messageId=' + $('#reply-sms').attr('messageId');
			},
			type : 'POST',
			dataType: 'html'
		});

		return false;
	});
});
