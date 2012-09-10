<?php

	// simple http digest authentication

	class auDigest
	{
		public function __construct($realm,$users,$fail=false)
		{
			if (!is_array($users)) Fatal("arDigest: second argument is not array of user=>password");
			if (!$fail) $fail="Authorization required.  Go away.";

			$nonce=md5(round(time()/60));
			$stale=false;

			if (!empty($_SERVER['PHP_AUTH_DIGEST']))
				$digest=$this->http_digest_parse($_SERVER['PHP_AUTH_DIGEST']);

			if ($digest['nonce']!=$nonce)
				$stale=true;

			$user=$digest['username'];

			if (!empty($users[$user]))
			{
				$pass=$users[$user];
				$A1=md5($user.':'.$realm.':'.$pass);
				$A2=md5($_SERVER['REQUEST_METHOD'].':'.$digest['uri']);
				$valid=md5($A1.':'.$digest['nonce'].':'.$digest['nc'].':'.$digest['cnonce'].':'.$digest['qop'].':'.$A2);
				if ($digest['response']==$valid)
					return($digest);
			}

			header('HTTP/1.1 401 Unauthorized');
			header("WWW-Authenticate: Digest realm=\"$realm\", nonce=\"$nonce\", qop=auth".($stale?', stale=true':''));

			echo $fail;
			exit;
		}

		private function unquote($text)
		{
			if (substr($text,0,1)=='"' && substr($text,-1,1)=='"')
				return(substr($text,1,-1));
			return($text);
		}
		private function http_digest_parse($text)
		{
			$digest=array();
			foreach (explode(', ',$text) as $pair)
			{
				$expression=explode('=',$pair);
				$digest[$expression[0]]=$this->unquote($expression[1]);
			}
			return($digest);
		}
	}
