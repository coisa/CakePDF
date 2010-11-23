<?php
	App::import('Vendor', 'dompdf', array('file' => 'dompdf/dompdf_config.inc.php'));

	class DomPdfComponent extends Object {
		var $settings = array();
	
		public function initialize(&$controller, $settings = array()) {
			$this->settings = array_merge(array(
				'actions' => array('pdf', 'admin_pdf'),
				'filename' => 'relatorio.pdf',
				'orientation' => 'portrait',
				'paper' => 'A4',
				'outputType' => 'D'
			), $settings);
			
			if (is_string($this->settings['actions'])) {
				$this->settings['actions'] = array($this->settings['actions']);
			}
			
			if (in_array($controller->action, $this->settings['actions'])) {
				$this->dompdf = new DOMPDF();
				
				$this->dompdf->set_paper(low($this->settings['paper']), $this->settings['orientation']);
				
				$controller->layout = 'pdf';
			}
		}
		
		public function shutdown(&$controller) {
			if (isset($this->dompdf)) {
				$this->dompdf->load_html(utf8_decode($controller->output));
				$this->dompdf->render();
				
				$this->dompdf->stream($this->settings['filename']);
				exit();
			}
		}
		
	}
?>