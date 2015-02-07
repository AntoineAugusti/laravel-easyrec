<?php namespace Antoineaugusti\Tests\LaravelEasyrec;

use Illuminate\Support\Facades\Session;

class EasyrecTestActions extends LaravelEasyrecTestCase {
	
	//
	// - view
	//
	public function testView()
	{
		Session::shouldReceive('getId')->once()->andReturn(self::SESSION_ID);
		$this->easyrec->view(self::ITEM_ID, self::ITEM_DESCRIPTION, self::ITEM_URL);

		// Test required keys
		$requiredKeys = ['itemid', 'itemdescription', 'itemurl', 'sessionid'];
		$queryParams = $this->easyrec->getQueryParams();
		foreach ($requiredKeys as $key)
			$this->assertArrayHasKey($key, $queryParams);

		// Test values in the request
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");
		$this->assertEquals($queryParams["sessionid"], self::SESSION_ID);
		$this->assertEquals($queryParams["itemid"], self::ITEM_ID);
		$this->assertEquals($queryParams["itemdescription"], self::ITEM_DESCRIPTION);
		$this->assertEquals($queryParams["itemurl"], self::ITEM_URL);

		// Test the endpoint name
		$this->assertEquals('view', $this->easyrec->getEndpoint());
	}

	//
	// - buy
	//
	public function testBuy()
	{
		Session::shouldReceive('getId')->once()->andReturn(self::SESSION_ID);
		$this->easyrec->buy(self::ITEM_ID, self::ITEM_DESCRIPTION, self::ITEM_URL);

		// Test required keys
		$requiredKeys = ['itemid', 'itemdescription', 'itemurl', 'sessionid'];
		$queryParams = $this->easyrec->getQueryParams();
		foreach ($requiredKeys as $key)
			$this->assertArrayHasKey($key, $queryParams);

		// Test values in the request
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");
		$this->assertEquals($queryParams["sessionid"], self::SESSION_ID);
		$this->assertEquals($queryParams["itemid"], self::ITEM_ID);
		$this->assertEquals($queryParams["itemdescription"], self::ITEM_DESCRIPTION);
		$this->assertEquals($queryParams["itemurl"], self::ITEM_URL);

		// Test the endpoint name
		$this->assertEquals('buy', $this->easyrec->getEndpoint());
	}
	
	//
	// - rate
	//
	public function testRate()
	{
		Session::shouldReceive('getId')->once()->andReturn(self::SESSION_ID);
		$this->easyrec->rate(self::ITEM_ID, self::RATING_NOTE, self::ITEM_DESCRIPTION, self::ITEM_URL);

		// Test required keys
		$requiredKeys = ['itemid', 'ratingvalue', 'itemdescription', 'itemurl', 'sessionid'];
		$queryParams = $this->easyrec->getQueryParams();
		foreach ($requiredKeys as $key)
			$this->assertArrayHasKey($key, $queryParams);

		// Test values in the request
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");
		$this->assertEquals($queryParams["sessionid"], self::SESSION_ID);
		$this->assertEquals($queryParams["itemid"], self::ITEM_ID);
		$this->assertEquals($queryParams["ratingvalue"], self::RATING_NOTE);
		$this->assertEquals($queryParams["itemdescription"], self::ITEM_DESCRIPTION);
		$this->assertEquals($queryParams["itemurl"], self::ITEM_URL);

		// Test the endpoint name
		$this->assertEquals('rate', $this->easyrec->getEndpoint());
	}

	public function testRateException()
	{
		// Giving a string instead of a note should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->rate(self::ITEM_ID, "not a note", self::ITEM_DESCRIPTION, self::ITEM_URL);
	}
	
	//
	// - sendAction
	//
	public function testSendAction()
	{
		Session::shouldReceive('getId')->once()->andReturn(self::SESSION_ID);
		$this->easyrec->sendAction(self::ITEM_ID, self::ITEM_DESCRIPTION, self::ITEM_URL, self::CUSTOM_ACTION);

		// Test required keys
		$requiredKeys = ['itemid', 'itemdescription', 'itemurl', 'actiontype', 'sessionid'];
		$queryParams = $this->easyrec->getQueryParams();
		foreach ($requiredKeys as $key)
			$this->assertArrayHasKey($key, $queryParams);

		// Test values in the request
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");
		$this->assertEquals($queryParams["sessionid"], self::SESSION_ID);
		$this->assertEquals($queryParams["itemid"], self::ITEM_ID);
		$this->assertEquals($queryParams["itemdescription"], self::ITEM_DESCRIPTION);
		$this->assertEquals($queryParams["itemurl"], self::ITEM_URL);
		$this->assertEquals($queryParams["actiontype"], self::CUSTOM_ACTION);

		// Test the endpoint name
		$this->assertEquals('sendaction', $this->easyrec->getEndpoint());
	}
}