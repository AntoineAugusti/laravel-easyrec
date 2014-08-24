<?php namespace Antoineaugusti\LaravelEasyrec;

require_once "LaravelEasyrecTestCase.php";

use Antoineaugusti\LaravelEasyrec\LaravelEasyrecTestCase;

class EasyrecTestRecommendations extends LaravelEasyrecTestCase {

	//
	// - usersAlsoViewed
	//
	public function testUsersAlsoViewed()
	{
		$this->easyrec->usersAlsoViewed(self::ITEM_ID);

		// Test required keys
		$requiredKeys = ['itemid'];
		$queryParams = $this->easyrec->getQueryParams();
		foreach ($requiredKeys as $key)
			$this->assertArrayHasKey($key, $queryParams);

		// Test values in the request
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");
		$this->assertEquals($queryParams["itemid"], self::ITEM_ID);

		// Test the endpoint name
		$this->assertEquals('otherusersalsoviewed', $this->easyrec->getEndpoint());
	}

	public function testUsersAlsoViewedException()
	{
		// Giving a string instead of a number of results should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->usersAlsoViewed(self::ITEM_ID, null, "not a number of results");
	}

	//
	// - usersAlsoBought
	//
	public function testUsersAlsoBought()
	{
		$this->easyrec->usersAlsoBought(self::ITEM_ID);

		// Test required keys
		$requiredKeys = ['itemid'];
		$queryParams = $this->easyrec->getQueryParams();
		foreach ($requiredKeys as $key)
			$this->assertArrayHasKey($key, $queryParams);

		// Test values in the request
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");
		$this->assertEquals($queryParams["itemid"], self::ITEM_ID);

		// Test the endpoint name
		$this->assertEquals('otherusersalsobought', $this->easyrec->getEndpoint());
	}

	public function testUsersAlsoBoughtException()
	{
		// Giving a string instead of a number of results should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->usersAlsoBought(self::ITEM_ID, null, "not a number of results");
	}

	//
	// - ratedGoodByOther
	//
	public function testRatedGoodByOther()
	{
		$this->easyrec->ratedGoodByOther(self::ITEM_ID);

		// Test required keys
		$requiredKeys = ['itemid'];
		$queryParams = $this->easyrec->getQueryParams();
		foreach ($requiredKeys as $key)
			$this->assertArrayHasKey($key, $queryParams);

		// Test values in the request
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");
		$this->assertEquals($queryParams["itemid"], self::ITEM_ID);

		// Test the endpoint name
		$this->assertEquals('itemsratedgoodbyotherusers', $this->easyrec->getEndpoint());
	}

	public function testRatedGoodByOtherException()
	{
		// Giving a string instead of a number of results should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->ratedGoodByOther(self::ITEM_ID, null, "not a number of results");
	}

	//
	// - recommendationsForUser
	//
	public function testRecommendationsForUser()
	{
		$this->easyrec->recommendationsForUser(self::USER_ID);

		// Test required keys
		$requiredKeys = ['userid'];
		$queryParams = $this->easyrec->getQueryParams();
		foreach ($requiredKeys as $key)
			$this->assertArrayHasKey($key, $queryParams);

		// Test values in the request
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");
		$this->assertEquals($queryParams["userid"], self::USER_ID);

		// Test the endpoint name
		$this->assertEquals('recommendationsforuser', $this->easyrec->getEndpoint());
	}

	public function testRecommendationsForUserException()
	{
		// Giving a string instead of a number of results should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->recommendationsForUser(self::USER_ID, "not a number of results");
	}

	//
	// - actionHistoryForUser
	//
	public function testActionHistoryForUser()
	{
		$this->easyrec->actionHistoryForUser(self::USER_ID);

		// Test required keys
		$requiredKeys = ['userid'];
		$queryParams = $this->easyrec->getQueryParams();
		foreach ($requiredKeys as $key)
			$this->assertArrayHasKey($key, $queryParams);

		// Test values in the request
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");
		$this->assertEquals($queryParams["userid"], self::USER_ID);

		// Test the endpoint name
		$this->assertEquals('actionhistoryforuser', $this->easyrec->getEndpoint());
	}

	public function testActionHistoryForUserException()
	{
		// Giving a string instead of a number of results should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->actionHistoryForUser(self::USER_ID, "not a number of results");
	}
}