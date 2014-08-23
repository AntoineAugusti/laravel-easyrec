<?php namespace Antoineaugusti\LaravelEasyrec;

use GuzzleHttp\Client;
use GuzzleHttp\Adapter\MockAdapter;
use GuzzleHttp\Adapter\TransactionInterface;
use GuzzleHttp\Message\Response;
use \PHPUnit_Framework_TestCase;

// Overwrite the session_id function from PHP
function session_id()
{
	return 42;
}

class EasryrecTest extends PHPUnit_Framework_TestCase {

	public $config;
	const ITEM_ID = 1337;
	const ITEM_DESCRIPTION = "mock-description";
	const ITEM_URL = "mock-url";
	const RATING_NOTE = 5;


	public function setUp()
	{
		$config = [
			'baseURL'    => 'mock-url',
			'apiVersion' => '1.0',
			'apiKey'     => 'mock-key',
			'tenantID'   => 'mock-tenant'
		];

		$this->easyrec = new Easyrec($config);

		// Always return a 200 OK response
		$mockAdapter = new MockAdapter(function (TransactionInterface $trans) {
			$request = $trans->getRequest();
			return new Response(200);
		});

		// Replace the HTTP client
		$client = new Client(['adapter' => $mockAdapter, 'base_url' => $this->easyrec->getBaseURL()]);
		$this->easyrec->setHttpClient($client);
	}

	public function testBaseURL()
	{
		$this->assertEquals('mock-url/api/1.0/json/', $this->easyrec->getBaseURL());
	}

	/*
	* ACTIONS 
	* --------------------
	*/
	public function testView()
	{
		$this->easyrec->view(self::ITEM_ID, self::ITEM_DESCRIPTION, self::ITEM_URL);

		// Test required keys
		$requiredKeys = ['itemid', 'itemdescription', 'itemurl', 'sessionid'];
		$queryParams = $this->easyrec->getQueryParams();
		foreach ($requiredKeys as $key)
			$this->assertArrayHasKey($key, $queryParams);

		// Test values in the request
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");
		$this->assertEquals($queryParams["sessionid"], 42);
		$this->assertEquals($queryParams["itemid"], self::ITEM_ID);
		$this->assertEquals($queryParams["itemdescription"], self::ITEM_DESCRIPTION);
		$this->assertEquals($queryParams["itemurl"], self::ITEM_URL);
	}

	public function testBuy()
	{
		$this->easyrec->buy(self::ITEM_ID, self::ITEM_DESCRIPTION, self::ITEM_URL);

		// Test required keys
		$requiredKeys = ['itemid', 'itemdescription', 'itemurl', 'sessionid'];
		$queryParams = $this->easyrec->getQueryParams();
		foreach ($requiredKeys as $key)
			$this->assertArrayHasKey($key, $queryParams);

		// Test values in the request
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");
		$this->assertEquals($queryParams["sessionid"], 42);
		$this->assertEquals($queryParams["itemid"], self::ITEM_ID);
		$this->assertEquals($queryParams["itemdescription"], self::ITEM_DESCRIPTION);
		$this->assertEquals($queryParams["itemurl"], self::ITEM_URL);
	}

	public function testRate()
	{
		$this->easyrec->rate(self::ITEM_ID, self::RATING_NOTE, self::ITEM_DESCRIPTION, self::ITEM_URL);

		// Test required keys
		$requiredKeys = ['itemid', 'ratingvalue', 'itemdescription', 'itemurl', 'sessionid'];
		$queryParams = $this->easyrec->getQueryParams();
		foreach ($requiredKeys as $key)
			$this->assertArrayHasKey($key, $queryParams);

		// Test values in the request
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");
		$this->assertEquals($queryParams["sessionid"], 42);
		$this->assertEquals($queryParams["itemid"], self::ITEM_ID);
		$this->assertEquals($queryParams["ratingvalue"], self::RATING_NOTE);
		$this->assertEquals($queryParams["itemdescription"], self::ITEM_DESCRIPTION);
		$this->assertEquals($queryParams["itemurl"], self::ITEM_URL);
	}

    public function testRateException()
	{
		// Giving a string instead of a note should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->rate(self::ITEM_ID, "not a note", self::ITEM_DESCRIPTION, self::ITEM_URL);
	}
}