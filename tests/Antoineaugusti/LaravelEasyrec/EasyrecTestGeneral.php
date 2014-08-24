<?php namespace Antoineaugusti\LaravelEasyrec;

require_once "LaravelEasyrecTestCase.php";

use Antoineaugusti\LaravelEasyrec\LaravelEasyrecTestCase;

class EasryrecTestGeneral extends LaravelEasyrecTestCase {

	public function testBaseURL()
	{
		$this->assertEquals('mock-url/api/1.0/json/', $this->easyrec->getBaseURL());
	}

	public function testResponseHasError()
	{
		$response = ['dummy' => 'value'];
		$this->easyrec->setResponse($response);
		$this->assertFalse($this->easyrec->responseHasError());
		
		$response = ['error' => ['@code' => 42, '@message' => 'mock-message']];
		$this->easyrec->setResponse($response);
		$this->assertTrue($this->easyrec->responseHasError());
	}

	public function testRetrieveFirstErrorFromResponse()
	{
		// With only one error
		$firstError = ['@code' => 1, '@message' => 'mock-message'];
		$response = ['error' => $firstError];
		$this->easyrec->setResponse($response);
		$retrievedError = $this->easyrec->retrieveFirstErrorFromResponse();
		
		$this->assertArrayHasKey('@code', $retrievedError);
		$this->assertArrayHasKey('@message', $retrievedError);

		$this->assertEquals($firstError['@code'], $retrievedError['@code']);
		$this->assertEquals($firstError['@message'], $retrievedError['@message']);

		// With multiple errors
		$firstError = ['@code' => 1, '@message' => 'mock-message'];
		$secondError = ['@code' => 2, '@message' => 'mock-message-2'];
		$response = ['error' => [$firstError, $secondError]];
		$this->easyrec->setResponse($response);
		$retrievedError = $this->easyrec->retrieveFirstErrorFromResponse();

		$this->assertArrayHasKey('@code', $retrievedError);
		$this->assertArrayHasKey('@message', $retrievedError);

		$this->assertEquals($firstError['@code'], $retrievedError['@code']);
		$this->assertEquals($firstError['@message'], $retrievedError['@message']);
	}

	public function testRetrieveFirstErrorFromResponseException()
	{
		// Should not be able to retrieve an error from a response
		// if the response didn't contain an error
		$response = ['dummy' => 'value'];
		$this->easyrec->setResponse($response);
		
		$this->setExpectedException('InvalidArgumentException');
		$this->easyrec->retrieveFirstErrorFromResponse();
	}
}