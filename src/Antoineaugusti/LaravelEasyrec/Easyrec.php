<?php namespace Antoineaugusti\LaravelEasyrec;

use GuzzleHttp\Client as HTTPClient;
use Illuminate\Support\Facades\Session;
use Antoineaugusti\LaravelEasyrec\Exceptions\EasyrecException;

class Easyrec {

	private $config;
	private $endpoint;
	private $httpClient;
	private $queryParams;
	private $response;

	public function __construct($config)
	{	
		$this->config = $config;
		$this->endpoint = null;
		$this->response = null;
		
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
			$sessionid = Session::getId();

		foreach (['itemid', 'itemdescription', 'itemurl', 'userid', 'itemimageurl', 'actiontime', 'itemtype', 'sessionid'] as $param)
			$this->setQueryParam($param, $$param);

		// Set the endpoint name and send the request
		$this->setEndpoint('view');

		return $this->sendRequest();
	}

	public function buy($itemid, $itemdescription, $itemurl, $userid = null, $itemimageurl = null, $actiontime = null, $itemtype = null, $sessionid = null)
	{
		if (is_null($sessionid))
			$sessionid = Session::getId();

		foreach (['itemid', 'itemdescription', 'itemurl', 'userid', 'itemimageurl', 'actiontime', 'itemtype', 'sessionid'] as $param)
			$this->setQueryParam($param, $$param);

		// Set the endpoint name and send the request
		$this->setEndpoint('buy');

		return $this->sendRequest();
	}

	public function rate($itemid, $ratingvalue, $itemdescription, $itemurl, $userid = null, $itemimageurl = null, $actiontime = null, $itemtype = null, $sessionid = null)
	{
		// Check that the $ratingvalue as got the expected format
		if (!is_numeric($ratingvalue) OR $ratingvalue > 10 OR $ratingvalue < 1)
			throw new \InvalidArgumentException("The rating value should be between 1 and 10.", 1);
			
		if (is_null($sessionid))
			$sessionid = Session::getId();

		foreach (['userid', 'ratingvalue', 'sessionid', 'itemid', 'itemdescription', 'itemurl', 'itemimageurl', 'actiontime', 'itemtype'] as $param)
			$this->setQueryParam($param, $$param);

		// Set the endpoint name and send the request
		$this->setEndpoint('rate');

		return $this->sendRequest();
	}

	public function sendAction($itemid, $itemdescription, $itemurl, $actiontype, $actionvalue = null, $userid = null, $itemimageurl = null, $actiontime = null, $itemtype = null, $sessionid = null)
	{			
		if (is_null($sessionid))
			$sessionid = Session::getId();

		foreach (['itemid', 'itemdescription', 'itemurl', 'actiontype', 'actionvalue', 'userid', 'itemimageurl', 'actiontime', 'itemtype', 'sessionid'] as $param)
			$this->setQueryParam($param, $$param);

		// Set the endpoint name and send the request
		$this->setEndpoint('sendaction');

		return $this->sendRequest();
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
		
		// Set the endpoint name and send the request
		$this->setEndpoint($endpoint);

		return $this->sendRequest();
	}

	/**
	 * @see abstractRecommendationEndpoint
	 */
	public function usersAlsoViewed($itemid, $userid = null, $numberOfResults = 10, $itemtype = null, $requesteditemtype = null, $withProfile = false)
	{	
		return $this->abstractRecommendationEndpoint('otherusersalsoviewed', $itemid, $userid, $numberOfResults, $itemtype, $requesteditemtype, $withProfile);
	}

	/**
	 * @see abstractRecommendationEndpoint
	 */
	public function usersAlsoBought($itemid, $userid = null, $numberOfResults = 10, $itemtype = null, $requesteditemtype = null, $withProfile = false)
	{
		return $this->abstractRecommendationEndpoint('otherusersalsobought', $itemid, $userid, $numberOfResults, $itemtype, $requesteditemtype, $withProfile);
	}

	/**
	 * @see abstractRecommendationEndpoint
	 */
	public function ratedGoodByOther($itemid, $userid = null, $numberOfResults = 10, $itemtype = null, $requesteditemtype = null, $withProfile = false)
	{
		return $this->abstractRecommendationEndpoint('itemsratedgoodbyotherusers', $itemid, $userid, $numberOfResults, $itemtype, $requesteditemtype, $withProfile);
	}

	/**
	 * Returns recommendation for a given user ID
	 * @param  mixed   $userid 				A required anonymised id of a user. (e.g. "24EH1723322222A3")
	 * @param  integer $numberOfResults 	An optional parameter to determine the number of results returned. Should be between 1 and 15.
	 * @param  string  $requesteditemtype 	An optional type of an item (e.g. IMAGE, VIDEO, BOOK, etc.) to filter the returned items.If not supplied items of all item types are returned.
	 * @param  string  $actiontype 			Allows to define which actions of a user are considered when creating the personalized recommendation. Valid values are: VIEW, RATE, BUY.
	 * @param  boolean $withProfile 		If this parameter is set to true the result contains an additional element 'profileData' with the item profile.
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
		
		// Set the endpoint name and send the request
		$this->setEndpoint('recommendationsforuser');
		
		return $this->sendRequest();
	}

	/**
	 * Returns the last actions performed by a user
	 * @param  mixed   $userid 				A required anonymised id of a user. (e.g. "24EH1723322222A3")
	 * @param  integer $numberOfResults 	An optional parameter to determine the number of results returned. Should be between 1 and 15.
	 * @param  string  $requesteditemtype 	An optional type of an item (e.g. IMAGE, VIDEO, BOOK, etc.) to filter the returned items.If not supplied items of all item types are returned.
	 * @param  string  $actiontype 			Allows to define which actions of a user are considered when creating the personalized recommendation. Valid values are: VIEW, RATE, BUY.
	 * @return array The decoded JSON response
	 */
	public function actionHistoryForUser($userid, $numberOfResults = 10, $requesteditemtype = null, $actiontype = null)
	{
		// Check that $numberOfResults has got the expected format
		if (!is_numeric($numberOfResults) OR $numberOfResults < 0)
			throw new \InvalidArgumentException("The number of results should be at least 1.", 1);

		// Can't currently retrieve more than 15 results
		$numberOfResults = min($numberOfResults, 15);

		foreach (['userid', 'numberOfResults', 'requesteditemtype', 'actiontype'] as $param)
			$this->setQueryParam($param, $$param);
		
		// Set the endpoint name and send the request
		$this->setEndpoint('actionhistoryforuser');
		
		return $this->sendRequest();
	}

	/*
	* RANKINGS 
	* --------------------
	*/

	/**
	 * Call a community endpoint of the API
	 * @param  string  $endpoint          The name of the API endpoint
	 * @param  integer $numberOfResults   An optional parameter to determine the number of results returned. Must be between 1 and 50.
	 * @param  string  $timeRange         An optional parameter to determine the time range. This parameter may be set to one of the following values: DAY, WEEK, MONTH, ALL.
	 * @param  string  $requesteditemtype An optional item type that denotes the type of the item (e.g. IMAGE, VIDEO, BOOK, etc.). If not supplied the default value ITEM will be used.
	 * @param  boolean $withProfile       If this parameter is set to true the result contains an additional element 'profileData' with the item profile.
	 * @throws \InvalidArgumentException If the numberOfResults is negative or is not a number
	 * @throws \InvalidArgumentException If timeRange is not in the supported values: DAY, WEEK, MONTH, ALL
	 * @return array The JSON decoded response
	 */
	private function abstractCommunityEndpoint($endpoint, $numberOfResults = 30, $timeRange = 'ALL', $requesteditemtype = null, $withProfile = false)
	{
		// Check that $numberOfResults has got the expected format
		if (!is_numeric($numberOfResults) OR $numberOfResults < 0)
			throw new \InvalidArgumentException("The number of results should be at least 1.", 1);

		// Can't currently retrieve more than 50 results
		$numberOfResults = min($numberOfResults, 50);

		// Check that $timeRange has got the expected format
		if (!in_array($timeRange, ['DAY', 'WEEK', 'MONTH', 'ALL']))
			throw new \InvalidArgumentException("Invalid value for timeRange. Allowed values are DAY, WEEK, MONTH, ALL.", 1);

		foreach (['numberOfResults', 'timeRange', 'requesteditemtype', 'withProfile'] as $param)
			$this->setQueryParam($param, $$param);
		
		// Set the endpoint name and send the request
		$this->setEndpoint($endpoint);

		return $this->sendRequest();
	}

	/**
	 * @see abstractCommunityEndpoint
	 */
	public function mostViewedItems($numberOfResults = 30, $timeRange = 'ALL', $requesteditemtype = null, $withProfile = false)
	{
		return $this->abstractCommunityEndpoint('mostvieweditems', $numberOfResults, $timeRange, $requesteditemtype, $withProfile);
	}

	/**
	 * @see abstractCommunityEndpoint
	 */
	public function mostBoughtItems($numberOfResults = 30, $timeRange = 'ALL', $requesteditemtype = null, $withProfile = false)
	{
		return $this->abstractCommunityEndpoint('mostboughtitems', $numberOfResults, $timeRange, $requesteditemtype, $withProfile);
	}

	/**
	 * @see abstractCommunityEndpoint
	 */
	public function mostRatedItems($numberOfResults = 30, $timeRange = 'ALL', $requesteditemtype = null, $withProfile = false)
	{
		return $this->abstractCommunityEndpoint('mostrateditems', $numberOfResults, $timeRange, $requesteditemtype, $withProfile);
	}

	/**
	 * @see abstractCommunityEndpoint
	 */
	public function bestRatedItems($numberOfResults = 30, $timeRange = 'ALL', $requesteditemtype = null, $withProfile = false)
	{
		return $this->abstractCommunityEndpoint('bestrateditems', $numberOfResults, $timeRange, $requesteditemtype, $withProfile);
	}

	/**
	 * @see abstractCommunityEndpoint
	 */
	public function worstRatedItems($numberOfResults = 30, $timeRange = 'ALL', $requesteditemtype = null, $withProfile = false)
	{
		return $this->abstractCommunityEndpoint('worstrateditems', $numberOfResults, $timeRange, $requesteditemtype, $withProfile);
	}

	/**
	 * Returns the query parameters for the GET request
	 * @return array The key value parameters
	 */
	public function getQueryParams()
	{
		return $this->queryParams;
	}

	/**
	 * Set the endpoint name of the API
	 * @param string $endpoint The endpoint name
	 */
	public function setEndpoint($endpoint)
	{
		$this->endpoint = $endpoint;
	}

	/**
	 * Returns the endpoint name
	 * @return string The endpoint name
	 */
	public function getEndpoint()
	{
		return $this->endpoint;
	}

	/**
	 * Returns true if an endpoint list items
	 * @return boolean
	 */
	public function doesEndpointListItems()
	{
		return in_array($this->getEndpoint(), ['otherusersalsoviewed', 'otherusersalsobought', 'itemsratedgoodbyotherusers', 'recommendationsforuser', 'mostvieweditems', 'mostboughtitems', 'mostrateditems', 'bestrateditems', 'worstrateditems', 'actionhistoryforuser']);
	}

	/**
	 * Set the response given by the API
	 * @param array $response
	 */
	public function setResponse($response)
	{
		$this->response = $response;
	}

	/**
	 * Determine if the response given by the API has got an error
	 * @return boolean
	 */
	public function responseHasError()
	{
		return (!is_null($this->response) AND array_key_exists('error', $this->response));
	}

	/**
	 * Retrieve only the first response if we had an error in the response
	 * @return array An array with key '@code' and '@message' describing the first error
	 */
	public function retrieveFirstErrorFromResponse()
	{
		if (!$this->responseHasError())
			throw new \InvalidArgumentException("Response hasn't got an error");

		$errors = $this->response['error'];
			
		// Multiple errors?
		if (array_key_exists(0, $errors))
			// Retrieve only the first error
			$error = $errors[0];
		else
			$error = $errors;

		return $error;
	}

	/**
	 * Send a request to an API endpoint
	 * @return array The decoded JSON array
	 */
	private function sendRequest()
	{
		$endpoint = $this->getEndpoint();
		if (is_null($endpoint))
			throw new \InvalidArgumentException("Endpoint name was not set.", 1);
			
		// Prepare the request
		$request = $this->httpClient->createRequest('GET', $endpoint, ['query' => $this->queryParams]);
		
		// Send the request
		$response = $this->httpClient->send($request);

		// Parse JSON and returns an array
		$this->setResponse($result = $response->json());

		// Check if we had an error
		if ($this->responseHasError()) {

			$error = $this->retrieveFirstErrorFromResponse();

			throw new EasyrecException($error['@message'], $error['@code']);
		}

		// Add a key to the array with a list of all items' ID
		if ($this->doesEndpointListItems()) {
			
			// Check that we have got the expected array
			if (!is_null($result) AND array_key_exists('recommendeditems', $result)) {			
				
				$ids = [];
				foreach ($result['recommendeditems'] as $items) {
					foreach ($items as $item) {
						$ids[] = intval($item['id']);
					}
				}

				$result['listids'] = $ids;
			}
		}

		return $result;
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