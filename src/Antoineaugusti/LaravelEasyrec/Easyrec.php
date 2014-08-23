<?php namespace Antoineaugusti\LaravelEasyrec;

use GuzzleHttp\Client as HTTPClient;

class Easyrec {

	private $config;
	private $httpClient;
	private $queryParams;

	public function __construct($config)
	{	
		$this->config = $config;
		// Register Guzzle
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

		foreach (['userid', 'sessionid', 'itemid', 'itemdescription', 'itemurl', 'itemimageurl', 'actiontime', 'itemtype'] as $param)
			$this->setQueryParam($param, $$param);

		return $this->sendRequest('view');
	}

	public function buy($itemid, $itemdescription, $itemurl, $userid = null, $itemimageurl = null, $actiontime = null, $itemtype = null, $sessionid = null)
	{
		if (is_null($sessionid))
			$sessionid = session_id();

		foreach (['userid', 'sessionid', 'itemid', 'itemdescription', 'itemurl', 'itemimageurl', 'actiontime', 'itemtype'] as $param)
			$this->setQueryParam($param, $$param);

		return $this->sendRequest('buy');
	}

	public function rate($itemid, $ratingvalue, $itemdescription, $itemurl, $userid = null, $itemimageurl = null, $actiontime = null, $itemtype = null, $sessionid = null)
	{
		// Check that the $ratingvalue as got the expected format
		if (!is_numeric($ratingvalue) OR $ratingvalue > 10 OR $ratingvalue < 0)
			throw new \InvalidArgumentException("The rating value should be between 0 and 10.", 1);
			
		if (is_null($sessionid))
			$sessionid = session_id();

		foreach (['userid', 'ratingvalue', 'sessionid', 'itemid', 'itemdescription', 'itemurl', 'itemimageurl', 'actiontime', 'itemtype'] as $param)
			$this->setQueryParam($param, $$param);

		return $this->sendRequest('buy');
	}

	/*
	* RECOMMENDATIONS 
	* --------------------
	*/
	
	/**
	 * General method used to hit a recommendation endpoint of the API
	 * @param  string $endpoint The name of the API endpoint
	 * @param  string $itemid A required item ID to identify an item on your website. (e.g. "ID001")
	 * @param  mixed $userid If this parameter is provided items viewed by this user are suppressed.
	 * @param  int $numberOfResults An optional parameter to determine the number of results returned. Should be between 1 and 15.
	 * @param  string $itemtype An optional item type that denotes the type of the item (e.g. IMAGE, VIDEO, BOOK, etc.). If not supplied the default value ITEM will be used.
	 * @param  string $requesteditemtype An optional item type that denotes the type of the item (e.g. IMAGE, VIDEO, BOOK, etc.). If not supplied the default value ITEM will be used.
	 * @param  boolean $withProfile If this parameter is set to true the result contains an additional element 'profileData' with the item profile.
	 * @throws \InvalidArgumentException if the number of results is not a number or is negative
	 * @return array The decoded JSON response
	 */
	private function abstractRecommendationEndpoint($endpoint, $itemid, $userid = null, $numberOfResults = 10, $itemtype = null, $requesteditemtype = null, $withProfile = false)
	{
		// Check that $numberOfResults has got the expected format
		if (!is_numeric($numberOfResults) OR $numberOfResults < 0)
			throw new \InvalidArgumentException("The number of results should be at least 1.", 1);

		// Can't currently retrieve more than 15 results
		$numberOfResults = min($numberOfResults, 15);

		foreach (['itemid', 'userid', 'numberOfResults', 'itemtype', 'requesteditemtype', 'withProfile'] as $param)
			$this->setQueryParam($param, $$param);
		
		return $this->sendRequest('otherusersalsoviewed');
	}

	public function usersAlsoViewed($itemid, $userid = null, $numberOfResults = 10, $itemtype = null, $requesteditemtype = null, $withProfile = false)
	{	
		return $this->abstractRecommendationEndpoint('otherusersalsoviewed', $itemid, $userid, $numberOfResults, $itemtype, $requesteditemtype, $withProfile);
	}

	public function usersAlsoBought($itemid, $userid = null, $numberOfResults = 10, $itemtype = null, $requesteditemtype = null, $withProfile = false)
	{
		return $this->abstractRecommendationEndpoint('otherusersalsobought', $itemid, $userid, $numberOfResults, $itemtype, $requesteditemtype, $withProfile);
	}

	public function ratedGoodByOther($itemid, $userid = null, $numberOfResults = 10, $itemtype = null, $requesteditemtype = null, $withProfile = false)
	{
		return $this->abstractRecommendationEndpoint('itemsratedgoodbyotherusers', $itemid, $userid, $numberOfResults, $itemtype, $requesteditemtype, $withProfile);
	}

	/**
	 * Returns recommendation for a given user ID
	 * @param  mixed $userid A required anonymised id of a user. (e.g. "24EH1723322222A3")
	 * @param  integer $numberOfResults An optional parameter to determine the number of results returned. Should be between 1 and 15.
	 * @param  string $requesteditemtype An optional type of an item (e.g. IMAGE, VIDEO, BOOK, etc.) to filter the returned items.If not supplied items of all item types are returned.
	 * @param  string $actiontype Allows to define which actions of a user are considered when creating the personalized recommendation. Valid values are: VIEW, RATE, BUY.
	 * @param  boolean $withProfile If this parameter is set to true the result contains an additional element 'profileData' with the item profile.
	 * @return array The decoded JSON response
	 */
	public function recommendationsForUser($userid, $numberOfResults = 10, $requesteditemtype = null, $actiontype = "VIEW", $withProfile = false)
	{
		// Check that $numberOfResults has got the expected format
		if (!is_numeric($numberOfResults) OR $numberOfResults < 0)
			throw new \InvalidArgumentException("The number of results should be at least 1.", 1);

		// Can't currently retrieve more than 15 results
		$numberOfResults = min($numberOfResults, 15);

		foreach (['userid', 'numberOfResults', 'requesteditemtype', 'actiontype', 'withProfile'] as $param)
			$this->setQueryParam($param, $$param);
		
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
	 * @param string $param The name of the parameter to set
	 * @param mixed $value The value
	 */
	private function setQueryParam($key, $value)
	{
		// Do not set value if it was null because it was optional
		if (!is_null($value))
			$this->queryParams[$key] = $value;
	}
}