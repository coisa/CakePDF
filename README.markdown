# CakePHP PDF plugin v2.0.1
This plugin contains a component to generate PDFs using views.
The CakePDF plugin is compatible with CakePHP 2.0+.

## Installation
- Download the repository and extract it in `app/Plugin/CakePdf` or on one of your plugin paths.
- Add a prefix in `app/Config/core.php`. The config declaration will be something like this `Configure::write('Routing.prefixes', array('admin', 'pdf'));`.
- Load the plugin:

	```
	<?php CakePlugin::load('CakePdf'); ?>

## Usage
In a controller add component declaration with something similar to the following:

	<?php
	class Post extends AppController {
		public $components = array(
			'CakePdf.CakePdf'
		);

		public function pdf_index() {
			// content of your action
		}
	}
	?>

In the view you only need use HTML or PHP code, it will be converted to PDF and force to download.

### Configure
By default CakePdf use the prefix `pdf` to render actions.
If you want to change it you can set a `prefix` property on component.

    ...
    public $components = array(
        'CakePdf.CakePdf' => array(
            'prefix' => 'mypdf'
        )
    );

    public function mypdf_index() {
        // content of your action
    }
    ...

Do not forget to add your custom prefix in `app/Config/core.php` file.

You can also set a custom layout, filename, orientation, charset, paper.
By default we have the following properties:

    ...
    public $components = array(
        'CakePdf.CakePdf' => array(
            'prefix' => 'pdf',
            'layout' => 'CakePdf.pdf',
            'filename' => '{name_of_action}.pdf', // this is the name on output pdf (when force download occurs)
            'orientation' => 'P',  // accept 'P' for portrait and 'L' for landscape
            'paper' => 'A4' // accept all paper types of tcpdf library
        )
    );
    ...

In addition to this you can create a header `app/View/Elements/pdf/header.ctp` or footer `app/View/Elements/pdf/footer.ctp` files to place it on every page generated in your pdf.

     ...
    public $components = array(
        'CakePdf.CakePdf' => array(
            'header' => '{name_of_header_file_element}',
            'footer' => '{name_of_footer_file_element}'
        )
    );
    ...

### Additional Methods
- `setFilename($filename)` : It will change the output filename.
- `addStyle($files)` : Add css expressions to render in pdf view. The `$files` argument accepts a string path of file, or an array of path files (it automatically refer to folder `webroot/css/`).
- `dispatch($method, $params)` : Used to perform the TCPDF methods that where not directly implemented in this component.