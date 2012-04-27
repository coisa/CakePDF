<?php
$path = App::pluginPath('CakePdf');

App::build(array(
	'Vendor' => $path . 'Vendor' . DS,
	'Lib' => $path . 'Lib' . DS,
	'View' => $path . 'View' . DS
));