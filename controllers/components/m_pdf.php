<?php
	if (!App::import('Vendor', 'mpdf50/mpdf')) {
		exit('Não foi possível importar a biblioteca mPDF, usada para geração de relatórios PDF.');
	}

	class mPdfComponent extends Object {
		var $settings = array(
			'actions' => array('pdf', 'admin_pdf'),
			'filename' => 'noname.pdf',
			'orientation' => 'P',
			'paper' => 'A4',
			'outputType' => 'D',
			'charset' => 'UTF-8'
		);
		
		private function __setLayout(&$controller, $layout = 'pdf') {
			if (!file_exists(APP . 'views' . DS . 'layouts' . DS . $layout . '.ctp')) {
				$pluginPath = dirname(dirname(dirname(__FILE__)));
			
				App::build(array('views' => array($pluginPath . 'views' . DS)));
			}
		
			$controller->layout = $layout;
		}
	
		public function initialize(&$controller, $settings = array()) {
			$this->settings = array_merge($this->settings, $settings);
			
			if (is_string($this->settings['actions'])) {
				$this->settings['actions'] = array($this->settings['actions']);
			}
			
			if (in_array($controller->action, $this->settings['actions'])) {
				$this->mpdf = new mPDF(low($this->settings['charset']), up("{$this->settings['paper']}-{$this->settings['orientation']}"));
				
				$this->__setLayout($controller, 'pdf');
			}
		}
		
		public function shutdown(&$controller) {
			if (isset($this->mpdf)) {
				@$this->mpdf->writeHTML($controller->output);
				$this->mpdf->output($this->settings['filename'], $this->settings['outputType']);
				exit();
			}
		}
		
	}
?>