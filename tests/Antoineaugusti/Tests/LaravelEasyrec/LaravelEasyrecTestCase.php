<?php namespace Antoineaugusti\Tests\LaravelEasyrec;

use Antoineaugusti\LaravelEasyrec\Easyrec;
use GuzzleHttp\Client;
use GuzzleHttp\Adapter\MockAdapter;
use GuzzleHttp\Adapter\TransactionInterface;
use GuzzleHttp\Message\Response;
use \PHPUnit_Framework_TestCase;

abstract class LaravelEasyrecTestCase extends PHPUnit_Framework_TestCase {

	public $config;
	const ITEM_ID          = 1337;
	const USER_ID          = 69;
	const ITEM_DESCRIPTION = "mock-description";
	const ITEM_URL         = "mock-url";
	const RATING_NOTE      = 5;
	const SESSION_ID       = "mock-session";
	const CUSTOM_ACTION    = "mock-action";

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
}