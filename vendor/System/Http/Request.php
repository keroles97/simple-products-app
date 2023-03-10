<?php

namespace System\Http;

class Request
{
	/**
	 * Url
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Base Url
	 *
	 * @var string
	 */
	private $baseUrl;

	/**
	 * Prepare url
	 *
	 * @return void
	 */
	public function prepareUrl()
	{
		$script = dirname($this->server('SCRIPT_NAME'));

		$requestUri = $this->server('REQUEST_URI');

		if (strpos($requestUri, '?') !== false) {
			list($requestUri, $queryString) = explode('?', $requestUri);
		}

		$this->url = rtrim(preg_replace('#^' . $script . '#', '', $requestUri), '/');

		if (!$this->url) {
			$this->url = '/';
		}

		$this->baseUrl = $this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST') . $script . '/';
	}

	/**
	 * Get Value from file_get_contents("php://input") by the given key
	 *
	 * @param string $key
	 * @param mixed $default
	 * 
	 * @return mixed
	 */
	public function fileGetContents($key, $default = null)
	{
		$row = file_get_contents("php://input");
		$data = json_decode($row, true);

		$value = array_get($data, $key, $default);

		if (is_array($value)) {
			$value = array_map('trim', $value);
		} else {
			$value = trim($value);
		}
		return $value;
	}

	/**
	 * Get Value from _GET by the given key
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		$value = array_get($_GET, $key, $default);

		if (is_array($value)) {
			$value = array_map('trim', $value);
		} else {
			$value = trim($value);
		}

		return $value;
	}

	/**
	 * Get Value from _POST by the given key
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function post($key, $default = null)
	{
		$value = array_get($_POST, $key, $default);
		if (is_array($value)) {
			$value = array_map('trim', $value);
		} else {
			$value = trim($value);
		}

		return $value;
	}

	/**
	 * Get the value for the given input name
	 *
	 * @param string $input
	 * @return mixed
	 */
	public function requestValue($input)
	{
		$value = $this->post($input, '');

		if ($value === '') {
			$value = $this->fileGetContents($input, '');
		}

		if ($value === '') {
			$value = $this->get($input);
		}

		return $value;
	}

	/**
	 * Set Value To _POST For the given key
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return mixed
	 */
	public function setPost($key, $value)
	{
		$_POST[$key] = $value;
	}

	/**
	 * Get passed values from request
	 *
	 * @return array
	 */
	public function requestInputs()
	{
		if (!empty($_POST)) return $_POST;

		if (!empty($_GET)) return $_GET;

		$row = file_get_contents("php://input");
		$data = json_decode($row, true);

		if (!empty($data)) return $data;

		return [];
	}

	/**
	 * Get Value from _SERVER by the given key
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function server($key, $default = null)
	{
		return array_get($_SERVER, $key, $default);
	}

	/**
	 * Get Current Request Method
	 *
	 * @return string
	 */
	public function method()
	{
		return $this->server('REQUEST_METHOD');
	}

	/**
	 * Get full url of the script
	 *
	 * @return string
	 */
	public function baseUrl()
	{
		return $this->baseUrl;
	}

	/**
	 * Get Only relative url (clean url)
	 *
	 * @return string
	 */
	public function url()
	{
		return $this->url;
	}
}
