<?php

use Antoineaugusti\LaravelEasyrec\Easyrec;

class EasryrecTest extends PHPUnit_Framework_TestCase {

	public $config;


	public function setUp()
	{
		$config = [
			'baseURL'    => 'mock-url',
			'apiVersion' => '1.0',
			'apiKey'     => 'mock-key',
			'tenantID'   => 'mock-tenant'
		];

		$this->easyrec = new Easyrec($config);
	}

	public function testBaseURL()
	{
		$this->assertEquals('mock-url/api/1.0/json/', $this->easyrec->getBaseURL());
	}
}