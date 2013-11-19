/*
 * UPDATED: 12.19.07
 *
 * jNice
 * by Sean Mooney (sean@whitespace-creative.com) 
 *
 * To Use: place in the head 
 *  <link href="inc/style/jNice.css" rel="stylesheet" type="text/css" />
 *  <script type="text/javascript" src="inc/js/jquery.jNice.js"></script>
 *
 * And apply the jNice class the form you want to style
 *
 * To Do: Add textareas, Add File upload
 *
 ******************************************** */
(function($){
	$.fn.jNice = function(options){
		var self = this;
		var safari = $.browser.safari; /* We need to check for safari to fix the input:text problem */
	
		/* each form */
		this.each(function(){

			/***************************
			  Selects 
			 ***************************/
			$('select', this).each(function(index){
				var $select = $(this);
				/* First thing we do is Wrap it */
				$(this).addClass('jNiceHidden').wrap('<div class="jNiceSelectWrapper"></div>');
				var $wrapper = $(this).parent().css({zIndex: 100-index});
				/* Now add the html for the select */
				$wrapper.prepend('<div><span></span><a href="#" class="jNiceSelectOpen"></a></div><ul></ul>');
				var $ul = $('ul', $wrapper);
				/* Now we add the options */
				$('option', this).each(function(i){
					$ul.append('<li><a href="#" index="'+ i +'">'+ this.text +'</a></li>');
				});
				/* Hide the ul and add click handler to the a */
				$ul.hide().find('a').click(function(){
						$('a.selected', $wrapper).removeClass('selected');
						$(this).addClass('selected');	
						/* Fire the onchange event */
						if ($select[0].selectedIndex != $(this).attr('index') && $select[0].onchange) { $select[0].selectedIndex = $(this).attr('index'); $select[0].onchange(); }
						$select[0].selectedIndex = $(this).attr('index');
						$('span:eq(0)', $wrapper).html($(this).html());
						$ul.hide();
						return false;
				});
				/* Set the defalut */
				$('a:eq('+ this.selectedIndex +')', $ul).click();
			});/* End select each */
			
			/* Apply the click handler to the Open */
			$('a.jNiceSelectOpen', this).click(function(){
														var $ul = $(this).parent().siblings('ul');
														if ($ul.css('display')=='none'){hideSelect();} /* Check if box is already open to still allow toggle, but close all other selects */
    													$ul.slideToggle();
														var offSet = ($('a.selected', $ul).offset().top - $ul.offset().top);
														$ul.animate({scrollTop: offSet});
														return false;
												});
		
		}); /* End Form each */
		
		/* Hide all open selects */
		var hideSelect = function(){
			$('.jNiceSelectWrapper ul:visible').hide();
		};
		
		/* Check for an external click */
		var checkExternalClick = function(event) {
			if ($(event.target).parents('.jNiceSelectWrapper').length === 0) { hideSelect(); }
		};

		/* Apply document listener */
		$(document).mousedown(checkExternalClick);
		
			
		/* Add a new handler for the reset action */
		var jReset = function(f){
			var sel;
			$('.jNiceSelectWrapper select', f).each(function(){sel = (this.selectedIndex<0) ? 0 : this.selectedIndex; $('ul', $(this).parent()).each(function(){$('a:eq('+ sel +')', this).click();});});
			$('a.jNiceCheckbox, a.jNiceRadio', f).removeClass('jNiceChecked');
			$('input:checkbox, input:radio', f).each(function(){if(this.checked){$('a', $(this).parent()).addClass('jNiceChecked');}});
		};
		
	};/* End the Plugin */

	/* Automatically apply to any forms with class jNice */
	$(function(){$('form.jNice').jNice();	});

})(jQuery);
				   
