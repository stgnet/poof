<?php

class uiGoogleAnalytics extends uiElement
{
	protected $account;
	protected $domain;

	function __construct($account,$domain=false)
	{
		parent::__construct();
		$this->account=$account;
		$this->domain=$domain;
	}
	function __toString()
	{
		// don't generate a tag for this element
		return($this->GenerateContent());
	}
	function PreGenerate($page)
	{
		$account=$this->account;
		$domain=$this->domain;
		$page->HeadScript('googleanalytics-'.$this->ui_name,"
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '$account']);
".($domain?"  _gaq.push(['_setDomainName', '$domain']);
":"")."  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();");

	}
}
