/**
 * Tipme 1.1
 * -----------------------------------------
 * Tipme is a simple jQuery plugin that provies tooltips on elements you specify. It's
 * easy to setup and easy to configure some basic styling.
 * Tipme uses pure CSS to generate the box and arrow of the tooltip.
 *
 * For help with using the plugin see the README or visit http://jasonlewis.me/tipme/docs
 *
 * @author Jason Lewis
 * @copyright 2011 Jason Lewis
 * @version 1.1
 * @package Tipme
 * @url http://jasonlewis.me/projects/tipme
 * @license MIT License - See LICENSE for details.
 */
(function($){
	$.fn.tipme = function(opts){

		var options = $.extend({}, $.fn.tipme.defaults, opts),
			elements = {
				container: 	$('<div id="tipme-container" class="arrow-n"></div>'),
				wrapper: 	$('<div id="tipme-wrapper"></div>'),
				arrow:		$('<div id="tipme-arrow"></div>'),
				content:	$('<div id="tipme-content"></div>')
			},
			binder = options.live ? 'live' : 'bind',
			title = '';

		// Append the tipme contents to the body
		$('body').append(elements.container.html(elements.wrapper.html(elements.arrow).append(elements.content)));

		// Set some options
		elements.container.css({ maxWidth: options.width + 'px' });

		return this[binder]({
			mouseover: function(){
				var elm = $(this);
				title = elm.attr(options.attr);

				if(title.length > 0){
					elements.content.text(title);
					elm.attr(options.attr, '');

					var dimensions = {
							top: elm.offset().top,
							left: elm.offset().left,
							width: elm.outerWidth(),
							height: elm.outerHeight(),
							actualWidth: elm.outerWidth(true),
							actualHeight: elm.outerHeight(true),
							arrow: {
								width: elements.arrow.outerWidth() / 2,
								height: elements.arrow.outerHeight() / 2
							}
						},
						offsets = {};

					// Determine the position.
					switch(options.position.charAt(0)){
						case 'b':
							elements.container.removeAttr('class').addClass('arrow-b');
							offsets = {
								top: (dimensions.top + dimensions.height) + dimensions.arrow.height + options.offset,
								left: dimensions.left + (dimensions.width / 2) - (elements.container.outerWidth() / 2)
							};
						break;
						case 't':
							elements.container.removeAttr('class').addClass('arrow-t');
							offsets = {
								top: (dimensions.top - elements.container.outerHeight(true)) - dimensions.arrow.height - options.offset,
								left: dimensions.left + (dimensions.width / 2) - (elements.container.outerWidth() / 2)
							};
						break;
						case 'l':
							elements.container.removeAttr('class').addClass('arrow-l');
							offsets = {
								top: dimensions.top + (dimensions.height / 2) - (elements.container.outerHeight() / 2),
								left: dimensions.left - elements.container.outerWidth(true) - dimensions.arrow.width - options.offset
							};
						break;
						case 'r':
							elements.container.removeAttr('class').addClass('arrow-r');
							offsets = {
								top: dimensions.top + (dimensions.height / 2) - (elements.container.outerHeight() / 2),
								left: dimensions.left + dimensions.width + dimensions.arrow.width + options.offset
							};
						break;
					}

					if(options.position == 'topLeft' || options.position == 'bottomLeft')
					{
						offsets.left += (elements.container.outerWidth() / 2) - (dimensions.width / 2);

						elements.arrow.css('left', elements.arrow.outerWidth());
					}
					else if(options.position == 'topRight' || options.position == 'bottomRight')
					{
						offsets.left = (dimensions.left + dimensions.width) - elements.container.outerWidth();

						elements.arrow.css('left', elements.container.outerWidth() - elements.arrow.outerWidth());
					}
					else if(options.position == 'rightTop' || options.position == 'leftTop')
					{
						offsets.top = dimensions.top;
						elements.arrow.css('top', dimensions.height / 2);
					}

					elements.container.css(offsets).show();
				}
			},
			mouseout: function(){
				elements.container.hide();

				$(this).attr(options.attr, title);
			}
		});

		return this;
	};

	// Default Options
	$.fn.tipme.defaults = {
		width: 300,
		offset: 0,
		live: false,
		position: 'top',
		attr: 'title'
	};
})(jQuery);