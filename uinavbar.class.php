<?php

class uiNavBar extends uiElement
{
	function __construct()
	{
		parent::__construct();
		$this->ui_tag="div";
		$this->ui_class="container";
	}

	function __toString()
	{
		return(
			$this->Tag("div class=\"navbar\"",
				$this->Tag("div class=\"navbar-inner\"",
					$this->Tag("div class=\"container\"",
						$this->GenerateContent()
					)
				)
			)
		);
	}
}
