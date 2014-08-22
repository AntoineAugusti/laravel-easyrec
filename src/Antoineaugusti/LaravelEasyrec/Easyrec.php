<?php namespace Antoineaugusti\LaravelEasyrec;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client as HTTPClient;

class Easyrec {

	private $config;
	private $httpClient;
	private $queryParams;

	public function __construct($config)
	{	
		$this->config = $config;
		$this->setHttpClient(new HTTPClient(['base_url' => $this->getBaseURL()]));
		
		// Set API key and tenantID
		$this->queryParams = [
			'apikey'   => $config['apiKey'],
			'tenantid' => $config['tenantID'],
		];
	}

	public function setHttpClient($object)
	{
		$this->httpClient = $object;
	}

	public function getBaseURL()
	{
		return $this->config['baseURL'].'/api/'.$this->config['apiVersion'].'/json/';
	}

	/*
	* ACTIONS 
	* --------------------
	*/
	public function view($itemid, $itemdescription, $itemurl, $userid = null, $itemimageurl = null, $actiontime = null, $itemtype = null, $sessionid = null)
	{
		if (is_null($sessionid))
			$sessionid = session_id();

		foreach (['userid', 'sessionid', 'itemid', 'itemdescription', 'itemurl', 'itemimageurl', 'actiontime', 'itemtype'] as $param) {
			// $this->setQueryParam($param);
			if (!is_null($$param))
			$this->queryParams[$param] = $$param;
		}

		return $this->sendRequest('view');
	}

	public function buy($itemid, $itemdescription, $itemurl, $userid = null, $itemimageurl = null, $actiontime = null, $itemtype = null, $sessionid = null)
	{
		if (is_null($sessionid))
			$sessionid = session_id();

		foreach (['userid', 'sessionid', 'itemid', 'itemdescription', 'itemurl', 'itemimageurl', 'actiontime', 'itemtype'] as $param)
			$this->setQueryParam($param);

		return $this->sendRequest('buy');
	}

	public function rate($itemid, $ratingvalue, $itemdescription, $itemurl, $userid = null, $itemimageurl = null, $actiontime = null, $itemtype = null, $sessionid = null)
	{
		if (is_null($sessionid))
			$sessionid = session_id();

		foreach (['userid', 'ratingvalue', 'sessionid', 'itemid', 'itemdescription', 'itemurl', 'itemimageurl', 'actiontime', 'itemtype'] as $param)
			$this->setQueryParam($param);

		return $this->sendRequest('buy');
	}

	/*
	* RECOMMENDATIONS 
	* --------------------
	*/
	public function alsoViewed($itemid, $userid = null, $numberOfResults = 10, $itemtype = null, $requesteditemtype = null, $withProfile = null)
	{
		foreach (['itemid', 'userid', 'numberOfResults', 'itemtype', 'requesteditemtype', 'withProfile'] as $param)
			$this->setQueryParam($param);
		
		return $this->sendRequest('otherusersalsoviewed');
	}

	public function getQueryParams()
	{
		return $this->queryParams;
	}

	/**
	 * Send a request to an API endpoint
	 * @param  string $endpoint The endpoint name
	 * @return array The decoded JSON array
	 */
	private function sendRequest($endpoint)
	{
		// Prepare the request
		$request = $this->httpClient->createRequest('GET', $endpoint, ['query' => $this->queryParams]);
		
		// Send the request
		$response = $this->httpClient->send($request);

		// Parse JSON and returns an array
		return $response->json();
	}

	/**
	 * Set a GET parameter
	 * @param string $param The name of the parameter to set. Must be the name of a PHP variable
	 */
	private function setQueryParam($param)
	{
		// Do not set value if it was null because it was optional
		if (!is_null($$param))
			$this->queryParams[$param] = $$param;
	}
}