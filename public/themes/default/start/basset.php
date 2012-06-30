<?php

/**
 * Default theme uses the Basset asset manager to handle the loading and compressing of assets.
 */

Bundle::start('basset');

// Register the theme styles.
Basset::styles('theme', function($basset)
{
	$basset->directory('themes/default/css', function($basset)
	{
		$basset->add('theme', 'theme.css')
			   ->add('tipme', 'tipme.css');
	});

});

// Register the theme scripts.
Basset::scripts('theme', function($basset)
{
	$basset->directory('themes/default/js', function($basset)
	{
		$basset->add('jquery', 'jquery.js')
			   ->add('tipme', 'tipme.js')
			   ->add('leaner', 'leaner.js')
			   ->add('theme', 'theme.js');
	});
});