<?php
App::import('Vendor', 'CakePdf.TCPDF', array('file' => 'tcpdf/tcpdf.php'));

class CakeTcpdf extends TCPDF {

	private $_header;
	private $_footer;
	
	public function Header() {
		if (!empty($this->headerHtml)) {
			@parent::writeHTML($this->_header);
		}
	}
	 
	public function Footer() {
		if (!empty($this->footerHtml)) {
			@parent::writeHTML($this->_footer);
		}
	}
	
	public function setHeaderHTML($html = null) {
		$this->_header = $html;
	}
	
	public function setFooterHTML($html = null) {
		$this->_footer = $html;
	}

}