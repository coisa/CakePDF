## CakePHP PDF plugin

This plugin contains various components to generate PDFs using views.
The CakePDF plugin is compatible with CakePHP 1.3+.

### Installation

First download the repository and place it in `app/plugins/cake_pdf` or on one of your plugin paths.

### Usage

In a controller (or AppController if you need use this in all controllers) add component declaration with something similar to the following:

	<?php
	class Post extends AppController {
		var $name = 'Post';
		var $components = array(
			'CakePdf.mPdf' => array(
				'actions' => array('pdf', 'admin_pdf'), // action(s) converted in pdf
				'filename' => 'noname.pdf', // filename
				'orientation' => 'P', // accept P or L
				'charset' => 'UTF-8' // charset of content
			);
		);
		
		public function pdf() {
			// content of your action
		}
	}
	?>

In the view you only need use HTML or PHP code, it will be converted to PDF and force to download.