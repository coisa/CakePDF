<?php
App::import('Vendor', 'CakePdf.TCPDF', array('file' => 'tcpdf/tcpdf.php'));

class CakeTcpdf extends TCPDF {

	private $__header;
	private $__footer;
	
	public function Header() {
		if (!empty($this->headerHtml)) {
			@parent::writeHTML($this->__header);
		}
	}
	 
	public function Footer() {
		if (!empty($this->footerHtml)) {
			@parent::writeHTML($this->__footer);
		}
	}
	
	public function setHeaderHTML($html = null) {
		$this->__header = $html;
	}
	
	public function setFooterHTML($html = null) {
		$this->__footer = $html;
	}

}