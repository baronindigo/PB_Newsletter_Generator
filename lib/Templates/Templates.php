<?php

if ( ! class_exists( 'PB_Newsletter_Gen_Templates' ) ) {

	require PB_NEWSLETTER_GEN_PATH . '/lib/Templates/Wrestlezone.php';

	class PB_Newsletter_Gen_Templates {

		const TEMPLATES = [
			//"CT" => "Cattime",
			//"DT" => "Dogtime",
			"WZ" => "Wrestlezone",
		];

		public function __construct() {
		}

		public function getTemplate($name, $data) {
			if ($name === 'WZ') {
				$template = new PB_Newsletter_Gen_Templates_WZ();
				$html = $template->getHTML($data);

				return $html;
			}
		}


		public function getTemplates() {
			return $this::TEMPLATES;
		}


	}

}