<?php namespace Antoineaugusti\LaravelEasyrec;

require_once "LaravelEasyrecTestCase.php";

use Antoineaugusti\LaravelEasyrec\LaravelEasyrecTestCase;

class EasyrecTestRankings extends LaravelEasyrecTestCase {

	//
	// - mostViewedItems
	//
	public function testMostViewedItems()
	{
		$this->easyrec->mostViewedItems();

		// Test values in the request
		$queryParams = $this->easyrec->getQueryParams();
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");

		// Test the endpoint name
		$this->assertEquals('mostvieweditems', $this->easyrec->getEndpoint());
	}

	public function testMostViewedItemsNbResultsException()
	{
		// Giving a string instead of a number of results should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->mostViewedItems("not a number of results");
	}

	public function testMostViewedItemsTimeRangeException()
	{
		// Giving a not supported timerange should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->mostViewedItems(30, "not a valid timerange");
	}

	//
	// - mostBoughtItems
	//
	public function testMostBoughtItems()
	{
		$this->easyrec->mostBoughtItems();

		// Test values in the request
		$queryParams = $this->easyrec->getQueryParams();
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");

		// Test the endpoint name
		$this->assertEquals('mostboughtitems', $this->easyrec->getEndpoint());
	}

	public function testMostBoughtItemsNbResultsException()
	{
		// Giving a string instead of a number of results should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->mostBoughtItems("not a number of results");
	}

	public function testMostBoughtItemsTimeRangeException()
	{
		// Giving a not supported timerange should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->mostBoughtItems(30, "not a valid timerange");
	}

	//
	// - mostRatedItems
	//
	public function testMostRatedItems()
	{
		$this->easyrec->mostRatedItems();

		// Test values in the request
		$queryParams = $this->easyrec->getQueryParams();
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");

		// Test the endpoint name
		$this->assertEquals('mostrateditems', $this->easyrec->getEndpoint());
	}

	public function testMostRatedItemsNbResultsException()
	{
		// Giving a string instead of a number of results should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->mostRatedItems("not a number of results");
	}

	public function testMostRatedItemsTimeRangeException()
	{
		// Giving a not supported timerange should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->mostRatedItems(30, "not a valid timerange");
	}

	//
	// - bestRatedItems
	//
	public function testBestRatedItems()
	{
		$this->easyrec->bestRatedItems();

		// Test values in the request
		$queryParams = $this->easyrec->getQueryParams();
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");

		// Test the endpoint name
		$this->assertEquals('bestrateditems', $this->easyrec->getEndpoint());
	}

	public function testBestRatedItemsNbResultsException()
	{
		// Giving a string instead of a number of results should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->bestRatedItems("not a number of results");
	}

	public function testBestRatedItemsTimeRangeException()
	{
		// Giving a not supported timerange should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->bestRatedItems(30, "not a valid timerange");
	}

	//
	// - worstRatedItems
	//
	public function testWorstRatedItems()
	{
		$this->easyrec->worstRatedItems();

		// Test values in the request
		$queryParams = $this->easyrec->getQueryParams();
		$this->assertEquals($queryParams["apikey"], "mock-key");
		$this->assertEquals($queryParams["tenantid"], "mock-tenant");

		// Test the endpoint name
		$this->assertEquals('worstrateditems', $this->easyrec->getEndpoint());
	}

	public function testWorstRatedItemsNbResultsException()
	{
		// Giving a string instead of a number of results should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->worstRatedItems("not a number of results");
	}

	public function testWorstRatedItemsTimeRangeException()
	{
		// Giving a not supported timerange should give an exception
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->worstRatedItems(30, "not a valid timerange");
	}
}