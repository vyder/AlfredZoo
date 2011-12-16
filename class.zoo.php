<?php

/*

ZooPHP, a PHP wrapper for Zootool API written by Adam Hopkinson

http://adamhopkinson.co.uk/zoophp
http://zootool.com

Copyright (c) 2010, Adam Hopkinson
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

- Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
- Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
- Neither the name of the Zootool or Adam Hopkinson nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/

/*	Changelog
*	1.0 	2010-04-12	Initial public release
*	1.01	2010-04-13	Bastian fixed a bug in the ZooPHP::addItem method
*/

/*
*	ZooPHP - a wrapper for Zootool
*	@author Adam Hopkinson - http://adamhopkinson.co.uk/zoophp
*	@version 1.01
*	@copyright 2010, Adam Hopkinson
*/
class ZooPHP {
	
	var $key,		// the API key
		$secret,	// the API secret
		$base,		// the base url of the Zootool API
		$output,	// output format - array, object or json
		$username, 	// username, used for authenticated requests
		$password, 	// password, used for authenticated requests
		$version;	// the ZooPHP version number
	

	/*
	*	Constructs the class
	*	param string $key Zootool API key
	*	param string $secret Zootool API secret
	*/
	function __construct($key = null, $secret = null) {
		
		$this->version = 1.01;
		
		$this->key = urlencode($key);
		$this->secret = urlencode($secret);
		$this->base = 'http://zootool.com/api';
		$this->format = 'json';
		
	}
	
	/*
	*	Sets authorisation details for the API
	*	param string $username Zootool username
	*	param string $password Zootool password
	*	return boolean
	*/
	function setAuth($username, $password) {
		
		if(trim($username) == '' || trim($password) == '') {
			return false;
		}
		
		$this->username = $username;
		$this->password = sha1($password);
		
	}
	
	/*
	*	Fetches the url, using curl if available
	*	param string $url URL to fetch
	*	param boolean $authenticate Use authentication
	*	return string
	*/
	function fetch($url, $authenticate = false) {
		
		// an authenticated request requires a username & password and curl
		if($authenticate && (!isset($this->username) || !isset($this->password) || !function_exists('curl_init'))) {
			print "Not authenticated\n";
			return false;
		}
		
		if(function_exists('curl_init')) {
			$ch = curl_init();
		
			if($authenticate) { $url .= '&login=true'; }
			curl_setopt($ch, CURLOPT_URL, $url);

			if($authenticate) {
				print "Adding authentication stuff too";
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
				curl_setopt($ch, CURLOPT_USERPWD, strtolower($this->username) . ':' . $this->password);
			}
			
			curl_setopt($ch, CURLOPT_USERAGENT, 'ZooPHP v' + $this->version);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$result = curl_exec($ch);
		
			$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
			curl_close($ch);
			
			// print "$status - $ch\n";
			trigger_error($status . ' - ' . $url);
			
			if($status < 200 || $status >= 300) {
				return false;
			}
		
			return $result;

		} else {
			$result = file_get_contents($url);
			if($result === false) {
				return false;
			}
			return $result;
		}
	}
	
	/*
	*	Sets the output format of the data
	*	param string $output Output format (json|array|object)
	*	return boolean
	*/
	function setFormat($output) {
		
		if(in_array($output, array('json', 'array', 'object'))) {
			$this->output = $output;
		} else {
			return false;
		}
		
	}
	
	/*
	*	Takes a JSON result and converts it to $this->output
	*	param string $data Data to convert
	*	return string
	*/
	function decode($data) {
		
		switch($this->output) {
			case 'json':
				return $data;
			break;
			case 'array':
				return json_decode($data, true);
			break;
			case 'object':
				return json_decode($data);
			break;
		}
		
		if($this->output == 'json') {
			return $data;
		}
		
		return $data;
	}
		
	/*
	*	Get user details
	*	param string $username Username to return info about
	*	return string
	*/
	function getUser($username) {
		
		if($username == null || trim($username) == "") {
			return false;
		}
		
		$username = urlencode($username);
		
		$url = $this->base . '/users/info/' . '?apikey=' . $this->key . '&username=' . $username;
		$data = $this->fetch($url);
		
		if($data === false) {
			return false;
		}
				
		return $this->decode($data);
		
	}
	
	/*
	*	Gets a list of friends of the specified user
	*	param string $username Username to return info about
	*	return string
	*/
	function getUserFriends($username) {
		
		if($username == null || trim($username) == "") {
			return false;
		}
		
		$username = urlencode($username);
		
		$url = $this->base . '/users/friends/' . '?apikey=' . $this->key . '&username=' . $username;
		$data = $this->fetch($url);
		
		if($data === false) {
			return false;
		}
				
		return $this->decode($data);
		
	}
	
	/*
	*	Gets a list of followers of the specified user
	*	param string $username Username to return info about
	*	return string
	*/
	function getUserFollowers($username) {
		
		if($username == null || trim($username) == "") {
			return false;
		}
		
		$username = urlencode($username);
		
		$url = $this->base . '/users/followers/' . '?apikey=' . $this->key . '&username=' . $username;
		$data = $this->fetch($url);
		
		if($data === false) {
			return false;
		}
				
		return $this->decode($data);
		
	}
	
	/*
	*	Gets a list of items saved by the specified user
	*	param string $username Username to return info about
	*	param bool $include_private Setting this to true will return private items, if the user is authenticated
	*	return string
	*/
	function getUserItems($username, $include_private = false) {
		
		if($username == null || trim($username) == "") {
			return false;
		}
		
		$username = urlencode($username);
		
		$url = $this->base . '/users/items/' . '?apikey=' . $this->key . '&username=' . $username;
		if($include_private) {
			$url .= '&login=true';
		}
		$data = $this->fetch($url, $include_private);
		
		if($data === false) {
			return false;
		}
				
		return $this->decode($data);
		
	}
	
	/*
	*	Gets a list of the latest items saved across Zootool
	*	return string
	*/
	function getLatestItems() {
		
		$url = $this->base . '/users/items/' . '?apikey=' . $this->key;

		$data = $this->fetch($url);
		
		if($data === false) {
			return false;
		}
		
		return $this->decode($data);
		
	}
	
	/*
	*	Gets details of a specific item
	*	param string $uid Unique ID of the item to return info about
	*	return string
	*/
	function getItem($uid) {
		
		if($uid == null) {
			return false;
		}
		
		$uid = urlencode($uid);

		$url = $this->base . '/items/info/' . '?apikey=' . $this->key . '&uid=' . $uid;
		$data = $this->fetch($url);

		if($data === false) {
			return false;
		}

		return $this->decode($data);

	}

	/*
	*	Gets a list of popular items across Zootool
	*	return string
	*/
	function getPopularItems() {
		
		$url = $this->base . '/items/popular/' . '?apikey=' . $this->key;
		$data = $this->fetch($url);

		if($data === false) {
			return false;
		}

		return $this->decode($data);

	}
		
	/*
	*	Adds an item to the authenticated account
	*	param string $href URL of the item
	*	param string $title Title of the item
	*	param string $tags Tags describing the item (comma-seperated)
	*	param string $description Description of the item
	*	param string $referer Referer of the item
	*	param string $public Is the item public (y/n)?	
	*	return string
	*/
	function addItem($href, $title, $tags = null, $description = null, $referer = null, $public = null) {
		
		$url = $this->base . '/add/?apikey=' . $this->key;
		$url .= '&url=' . urlencode($href);
		$url .= '&title=' . urlencode($title);
		$url .= ($tags != null) ? '&tags=' . urlencode($tags) : '';
		$url .= ($description != null) ? '&description=' . urlencode($description) : '';
		$url .= ($referer != null) ? '&referer=' . urlencode($referer) : '';
		$url .= ($public == 'y' || $public == 'n') ? '&public=' . $public : '';
		
		$data = $this->fetch($url, true);
		
		if($data === false) {
			return false;
		}
		
		return $this->decode($data);
		
	}

}

?>