<?php

if ( ! class_exists( 'PB_Newsletter_Gen_Templates_WZ' ) ) {

	class PB_Newsletter_Gen_Templates_WZ {

		public function getWrestlezone($data) {
			$html = $this->getHTML($data);
		}

		/**/
		public function getHTML($data) {
			$html = "<html>";
			$html .= "<head><link href='https://fonts.googleapis.com/css?family=Merriweather+Sans:300,400,400i,700|Merriweather:400,400i,700|Open+Sans+Condensed:700|Open+Sans:400,600' rel='stylesheet'><style>".$this->getStyles()."</style></head>";
			$html .= "<body>";
			$html .= "<header>";
			$html .= "<img src='https://cdn1-www.wrestlezone.com/assets/uploads/2018/06/logo_wrestlezone.png' alt='Wrestlezone Logo'>";
			$html .= "</header>";
			$html .= "<h3>Latest News</h3>";

			foreach ($data as $article) {
				$html .= "<article>";
				$html .= "<a href='".$article->link."'>";
				$html .= "<div class='post-listed-image'><img src='".$article->img."' /></div>";
				$html .= "<div class=''><h4>".$article->title."</h4>";
				$html .= "<p>".$article->excerpt."</p></div>";
				$html .= "</a>";
				$html .= "</article>";
			}

			$html .= "</body>";
			$html .= "</html>";

			return $html;
		}

		public function getStyles() {
			return 'body { background-color:black; color:white; font-family:"Merriweather Sans"; }
			a { text-decoration: none; }
			h3 { color:white;text-transform:uppercase; }
			h3::before { width: 88px; height: 7px; display: block; content:""; background-color:#ec2224; position: relative; margin-bottom: 7px; }
			h4 { color:#fff; font:"800 20px/24px Merriweather Sans ,sans-serif"; text-transform:uppercase; }
			#ac-footer { color:white !important;}
			article { display: inline-block; margin-right:1%; vertical-align: top; width: 30%; }
			article p { color:#ccc; font-size: 15px; line-height: 22px; }
			.post-listed-image { margin-bottom: 14px; }
			.post-listed-image img {  width: 100%; }';
		}

	}

}