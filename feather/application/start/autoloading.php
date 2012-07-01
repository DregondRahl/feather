<?php

Autoloader::map(array(
	'Base_Controller' => path('app') . 'controllers/base.php',
	'Bouncer'		  => path('app') . 'classes/bouncer/bouncer.php'
));

Autoloader::directories(array(
	path('app') . 'classes',
	path('app') . 'models'
));