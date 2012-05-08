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
	
	private $__filename;
	private $__render;
	
	private $__loadCSS = '';
	
	private $__TCPDF;

	public function initialize($controller) {
		$this->__render = $this->prefix === Router::getParam('prefix');
		
		if ($this->__render) {
			$this->__TCPDF = new CakeTcpdf(
				$this->orientation,
				'mm',
				$this->paper,
				strtolower($this->charset) === 'utf-8',
				$this->charset
			);
			
			$controller->set('TCPDF', $this->__TCPDF);
			
			$this->__TCPDF->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$this->__TCPDF->setHeaderMargin(PDF_MARGIN_HEADER);
			$this->__TCPDF->setFooterMargin(PDF_MARGIN_FOOTER);
			
			$this->__TCPDF->setAutoPageBreak(true, PDF_MARGIN_BOTTOM);
			$this->__TCPDF->setFontSize(10);
			
			if (!empty($this->header)) {
				$this->__TCPDF->setHeaderHTML(View::element('pdf' . DS . $this->header));
			} else {
				$this->__TCPDF->setPrintHeader(false);
			}
			
			if (!empty($this->footer)) {
				$this->__TCPDF->setFooterHTML(View::element('pdf' . DS . $this->footer));
			} else {
				$this->__TCPDF->setPrintFooter(false);
			}
			
			$this->__TCPDF->addPage();
		}
	}
	
	public function beforeRender($controller) {
		if ($this->__render) {
			$controller->layout = $this->layout;
			
			if (empty($this->__filename)) {
				$this->setFilename($controller->request->action);
			}
		}
	}
	
	public function shutdown($controller) {
		if ($this->__render) {
			$response = $controller->response->body();
			
			$controller->response->charset($this->charset);
			$controller->response->type('pdf');
			
			if (!empty($this->__loadCSS)) {
				$response = '<style>' . $this->__loadCSS . '</style>' . $response;
			}
			
			if (!empty($response)) {
				@$this->__TCPDF->writeHTML($response, true, false, true, false, '');
			}
			
			$this->__TCPDF->output($this->__filename, 'D');
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
				$this->__loadCSS .= file_get_contents($fullpath);
			}
		}
	}
	
	public function setFilename($filename) {
		if (!strstr($filename, '.pdf')) {
			$filename .= '.pdf';
		}
		$this->__filename = $filename;
	}
	
	public function dispatch($method, $params = array()) {
		return call_user_method_array($method, $this->__TCPDF, $params);
	}

}