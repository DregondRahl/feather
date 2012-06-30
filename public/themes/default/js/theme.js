var FEATHER = {};

(function($)
{
	/**
	 * Register the tooltips that appear throughout the theme.
	 */
	$('.tooltip-ui').tipme({ 'position': 'top', 'offset': 2, 'live': true });
	$('.tooltip-ui-categories').tipme({ 'position': 'rightTop', 'offset': 2, 'width': 350 });
	$('.tooltip-ui-footer').tipme({ 'position': 'right', 'offset': 5, 'width': 350 });

	/**
	 * Register the leaner modal that works for this theme.
	 */
	 $('a.popup-ui').leaner();
})(jQuery);