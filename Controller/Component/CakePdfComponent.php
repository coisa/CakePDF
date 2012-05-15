<?php
App::uses('View', 'View');
App::uses('CakeTcpdf', 'CakePdf.Lib');

class CakePdfComponent extends Component {

	public $prefix = 'pdf';
	public $layout = 'CakePdf.pdf';
	
	public $paper = 'A4';
	public $orientation = '';
	
	public $charset = 'UTF-8';
	
	public $header;
	public $footer;
	
	private $_filename;
	private $_render;
	
	private $_loadCSS = '';
	
	private $_TCPDF;

	public function initialize($controller) {
		$this->_render = $this->prefix === Router::getParam('prefix');
		
		if ($this->_render) {
			$this->_TCPDF = new CakeTcpdf(
				$this->orientation,
				'mm',
				$this->paper,
				strtolower($this->charset) === 'utf-8',
				$this->charset
			);
			
			$controller->set('TCPDF', $this->_TCPDF);
			
			$this->_TCPDF->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$this->_TCPDF->setHeaderMargin(PDF_MARGIN_HEADER);
			$this->_TCPDF->setFooterMargin(PDF_MARGIN_FOOTER);
			
			$this->_TCPDF->setAutoPageBreak(true, PDF_MARGIN_BOTTOM);
			$this->_TCPDF->setFontSize(10);
			
			if (!empty($this->header)) {
				$this->_TCPDF->setHeaderHTML(View::element('pdf' . DS . $this->header));
			} else {
				$this->_TCPDF->setPrintHeader(false);
			}
			
			if (!empty($this->footer)) {
				$this->_TCPDF->setFooterHTML(View::element('pdf' . DS . $this->footer));
			} else {
				$this->_TCPDF->setPrintFooter(false);
			}
			
			$this->_TCPDF->addPage();
		}
	}
	
	public function beforeRender($controller) {
		if ($this->_render) {
			$controller->layout = $this->layout;
			
			if (empty($this->_filename)) {
				$this->setFilename($controller->request->action);
			}
		}
	}
	
	public function shutdown($controller) {
		if ($this->_render) {
			$response = $controller->response->body();
			
			$controller->response->charset($this->charset);
			$controller->response->type('pdf');
			
			if (!empty($this->_loadCSS)) {
				$response = '<style>' . $this->_loadCSS . '</style>' . $response;
			}
			
			if (!empty($response)) {
				@$this->_TCPDF->writeHTML($response, true, false, true, false, '');
			}
			
			$this->_TCPDF->output($this->_filename, 'D');
			$this->_stop();
		}
	}
	
	public function addStyle($files = null) {
		if (!is_array($files)) {
			$files = array($files);
		}
		foreach ($files as $filename) {
			$fullpath = CSS . $filename;
		
			if (file_exists($fullpath)) {
				$this->_loadCSS .= file_get_contents($fullpath);
			}
		}
	}
	
	public function setFilename($filename) {
		if (!strstr($filename, '.pdf')) {
			$filename .= '.pdf';
		}
		$this->_filename = $filename;
	}
	
	public function dispatch($method, $params = array()) {
		return call_user_method_array($method, $this->_TCPDF, $params);
	}

}