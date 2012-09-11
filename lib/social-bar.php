<?php 

/**
 *	Share Bar
 *	Darren A Coxall
 *	2012
 */

class ShareBar
{
	private $_endpoints = array(
		'facebook' => 'https//www.facebook.com/sharer.php',
		'twitter' => 'https://twitter.com/share',
		'google_plus' => 'https://plus.google.com/share',
		'email' => null);

	private $_target_url = null;
	private $_target_title = null;
	private $_target_description = null;
	private $_target_screen_name = null;
	private $_target_javascript = true;

	public function __construct($opts = array()) {
		foreach ($opts as $key => $val) {
			if (method_exists($this, "_target_$key"))
				$this->{'_target_'.$key} = $val;
		}
	}

	public function twitter_share_button() {
		$params = array(
			'url' => $this->_target_url,
			'via' => $this->_target_screen_name,
			'text' => $this->_target_description);
		$url = $this->build_url('twitter', $params);
		return "<a class='twitter share-bar-btn' target='blank' onclick=\"" . $this->onclick_javascript() . "\" href='" . $url . "' title='Tweet on Twitter'>Tweet on Twitter</a>"
	}

	public function facebook_share_button() {
		$params = array(
			'u' => $this->_target_url,
			't' => $this->_target_title);
		$url = $this->build_url('facebook', $params);
		return "<a class='facebook share-bar-btn' target='blank' onclick=\"" . $this->onclick_javascript() . "\" href='" . $url . "' title='Share on Facebook'>Share on Facebook</a>"
	}

	public function google_plus_share_button() {
		$params = array(
			'url' => $this->_target_url);
		$url = $this->build_url('google_plus', $params);
		return "<a class='google-plus share-bar-btn' target='blank' onclick=\"" . $this->onclick_javascript() . "\" href='" . $url . "' title='Share on Google&#43;'>Share on Google&#43;</a>"
	}

	public function email_share_button() {
		$params = array(
			'subject' => $this->_target_title,
			'body' => $this->_target_url);
		return "<a class='email share-bar-btn' href='mailto:;' title='Share on Google&#43;'>Share on Google&#43;</a>";
	}

	public function build_share_bar() {
		$result = "<ul class='share-bar'>";
		foreach (array_keys($this->_endpoints) as $social_site) {
			$result .= "<li class='$social_site'>" . $this->{'build_'.$social_site.'_share_button'}() . "</li>";
		}
		$result .= "</ul>";
		return $result;
	}

	private function build_params($params = array()) {
		$result = array()
		foreach($params as $key => $val) {
			if (!is_null($val))
				$result[] = $key . '=' . urlencode($val);
		}
		if (!empty($result) > 0)
			$result = '?' . implode("&amp;", $result);

		return $result;
	}

	private function build_url($endpoint_name, $params = array()) {
		$result = null;
		if (array_key_exists($endpoint_name, $this->_endpoints)) {
			$result = $this->_endpoints[$endpoint_name] . $this->build_params($params);
		}
		return $result;
	}

	private function onclick_javascript() {
		$result = null;
		if ($this->_target_javascript)
			$result = "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;";

		return $result;
	}
}
