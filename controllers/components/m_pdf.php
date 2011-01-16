<?php
/**
 * Automatic generation of PDFs from output of the view.
 *
 * Allows automatic generation of PDF using the library mPDF.
 * Pass the output of view to create the PDF file.
 *
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @version 2.0
 * @package       cake
 * @subpackage    cake.plugins.cakepdf.controllers.components
 * @link https://github.com/coisa/CakePDF
 */


App::import('Vendor', 'mpdf50/mpdf');

/**
 * mPDF Component library.
 *
 * Automatic generation of PDFs from output of the view.
 */
class mPdfComponent extends Object {

/**
 * Settings of PDF output.
 * @var array
 * @access private
 */
	private $settings = array(
		/**
		 * Layout to render the pdf
		 * @var string
		 */
		'layout' => 'pdf',
		
		/**
		 * Actions to generate PDF
		 * @var string|array
		 */
		'actions' => array(
			'pdf',
			'admin_pdf'
		),
		
		/**
		 * Set user permissions
		 * Allow: 'copy', 'print', 'modify', 'annot-forms'
		 * @var array
		 */
		'permissions' => array(),
		
		/**
		 * Password to open PDF doument
		 * @var string
		 */
		'password' => '',
		
		/**
		 * Password to full access of PDF
		 * @var string
		 */
		'fullAcessPassword' => '',
		
		/**
		 * Stylesheets to load
		 * @var string|array
		 */
		'css' => array(),
		
		/**
		 * Action to load header view for any pages of document
		 * @var string|array
		 */
		'headerAction' => '',
		
		/**
		 * Action to load footer view for any pages of document
		 * @var string|array
		 */
		'footerAction' => '',
		
		/**
		 * Filename of output document
		 * @var string
		 */
		'filename' => 'noname.pdf',
		
		/**
		 * Orientation of document
		 * Allow: P or L
		 * @var string
		 */
		'orientation' => 'P',
		
		/**
		 * Paper format of document
		 * @var string
		 */
		'paper' => 'A4',
		
		/**
		 * Type of document output
		 * Allow: D - Send to the browser and force a file download with the name given by filename.
		 *        F - Save to local file with the name given by filename (may include a path).
		 *        I - Send the file inline to the browser. The plug-in is used if available.
		 *        S - Return the document as a string. filename is ignored.
		 * @var string
		 */
		'outputType' => 'D',
		
		/**
		 * Charset of document
		 * @var string
		 */
		'charset' => 'UTF-8'
	);

/**
 * Configure output properties.
 * @param object $controller Controller using this component
 * @param string $layout Layout to render the pdf
 * @return void
 * @access private
 */
	private function config(&$controller, $layout = 'pdf') {
		# Set PDF Layout
		if (!file_exists(APP . 'views' . DS . 'layouts' . DS . $layout . '.ctp')) {
			$pluginPath = dirname(dirname(dirname(__FILE__)));
		
			App::build(array('views' => array($pluginPath . 'views' . DS)));
		}
	
		$controller->layout = $layout;
		
		# Set Header
		if (!empty($this->settings['headerAction'])) {
			$this->mpdf->setHTMLHeader($controller->requestAction($this->settings['headerAction'], array('return')));
		}
		
		# Set Footer
		if (!empty($this->settings['footerAction'])) {
			$this->mpdf->setHTMLHeader($controller->requestAction($this->settings['footerAction'], array('return')));
		}
		
		# Add CSS's
		if (!emtpy($this->settings['css'])) {
			if (!is_array($this->settings['css'])) {
				$this->settings['css'] = array($this->settings['css']);
			}
			
			foreach($this->settings['css'] as $css) {
				$this->addCSS($css);
			}
		}
		
		# Security
		if (!empty($this->settings['permissions']) || !empty($this->settings['password'])) {
			$this->mpdf->setProtection($this->settings['permissions'], $this->settings['password'], $this->settings['fullAcessPassword']);
		}
	}

/**
 * Initialize Component and create mPDF object
 * @return void
 * @acess public
 */
	public function initialize(&$controller, $settings = array()) {
		$this->settings = array_merge($this->settings, $settings);
		
		if (is_string($this->settings['actions'])) {
			$this->settings['actions'] = array($this->settings['actions']);
		}
		
		if (in_array($controller->action, $this->settings['actions'])) {
			$this->mpdf = new mPDF(low($this->settings['charset']), up("{$this->settings['paper']}-{$this->settings['orientation']}"));
			
			$this->config($controller, $this->settings['layout']);
		}
	}

/**
 * Add a css to document output
 *
 * Used to stylize output html
 * @return void
 * @acess public
 */
	public function addCSS($filename, $path = null) {
		if (!isset($this->mpdf) || !file_exists($path . $filename)) {
			return false;
		}
		$this->mpdf->writeHTML(file_get_contents($path . $filename), 1);
	}

/**
 * Create the PDF with output of the view
 * @return void
 * @acess public
 */
	public function shutdown(&$controller) {
		if (isset($this->mpdf)) {
			@$this->mpdf->writeHTML($controller->output);
			$this->mpdf->output($this->settings['filename'], $this->settings['outputType']);
			exit();
		}
	}
	
}