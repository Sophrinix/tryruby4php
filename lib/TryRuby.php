<?php
/**
 * The TryRuby class provides a PHP4 and PHP5 OOP interface to interacting with
 * _why's popular TryRuby application, which is a sandboxed Ruby interpreter
 * accessible over HTTP.
 *
 * Each instance creates a new session in the TryRuby application, so tasks done
 * from any given session will remain on that session until it is expired or
 * reset (to reset simply send the 'exit' command).
 *
 * @author   Matthew Harris <shugotenshi@gmail.com>
 * @package  tryruby4php
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class TryRuby {
	/**
	 * URL to TryRuby interface. 
	 *
	 * @var     string
	 * @access  public
	 */
	var $url = 'http://tryruby.sophrinix.com/irb?cmd=%s';
	
	/**
	 * Session ID for current Try Ruby session.
	 *
	 * @var     string
	 * @access  public
	 */
	var $session;
	
	/**
	 * cURL handler.
	 *
	 * @var     resource
	 * @access  protected
	 */
	var $_curl;
	
	/**
	 * Initialize new TryRuby session.
	 */
	function TryRuby()
	{
		if (!extension_loaded('curl')) {
			// Make sure the cURL extension is available.
			trigger_error("The `curl' extension must be loaded for TryRuby to work", E_USER_ERROR);
		}
		else {
			// Initialize cURL and set some default options.
			$this->_curl = curl_init();
			curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, 1);
			
			// Initialize Interactive Ruby.
			$this->session = $this->send('!INIT!IRB!');
		}
	}
	
	/**
	 * Execute a command on the remote interactive session.  When the output is
	 * fetched and returned back to the caller, the prepending "=> " string is
	 * not stripped for you, however, the trailing single newline is.
	 *
	 * @param   string  $str  Command string
	 * @return  string        Command output
	 * @access  public
	 */
	function send($str)
	{
		// Set the current session ID if it's available.
		if ($this->session) {
			curl_setopt($this->_curl, CURLOPT_HTTPHEADER,
				array(sprintf("Cookie: _session_id=%s", $this->session))
			);
		}
		
		// Send command to Try Ruby interpreter.
		curl_setopt($this->_curl, CURLOPT_URL, sprintf($this->url, urlencode($str)));
		
		// Fetch output and trim trailing new line (only the last trailing one).
		$output = substr(curl_exec($this->_curl), 0, -1);
		
		return $output;
	}
}
?>