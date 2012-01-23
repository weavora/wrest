<?php

class WRestResponseTest extends CTestCase
{

	/**
	 * @var JsonResponse
	 */
	static $response = null;

	public function setUp()
	{
		static::$response = new JsonResponse();
	}

	public function testRestPesonseObj()
	{
		$response = WRestResponse::factory('json');
		$this->assertTrue($response instanceof WRestResponse);
		$this->assertTrue($response instanceof JsonResponse);

		$this->assertTrue(static::$response instanceof WRestResponse);

		//test default param for new obj
		$this->assertEmpty(static::$response->getBody());
		$this->assertEquals(static::$response->getStatus(), 200);

	}

	public function testStatuses()
	{
		$this->assertTrue(static::$response->setStatus(200) instanceof WRestResponse);
		
		static::$response->setStatus(200);
		$this->assertEquals(static::$response->getStatus(), 200);
		$this->assertEquals(static::$response->getStatusCodeMessage(200), "OK");

		static::$response->setStatus(400);
		$this->assertEquals(static::$response->getStatus(), 400);
		$this->assertEquals(static::$response->getStatusCodeMessage(400), "Bad Request");

		$this->assertEquals(static::$response->getStatusCodeMessage(401), "Unauthorized");
		$this->assertEquals(static::$response->getStatusCodeMessage(401, false), "You must be authorized to view this page.");

		$this->assertEquals(static::$response->getStatusCodeMessage(402), "Payment Required");
		$this->assertEquals(static::$response->getStatusCodeMessage(403), "Forbidden");
		$this->assertEquals(static::$response->getStatusCodeMessage(404), "Not Found");
		$this->assertEquals(static::$response->getStatusCodeMessage(500), "Internal Server Error");
		$this->assertEquals(static::$response->getStatusCodeMessage(501), "Not Implemented");


		$this->assertEquals(static::$response->getStatusCodeMessage(9999), ""); //test unexisting code
		
	}

	public function testGetErrorMessage(){
		$error = static::$response->getErrorMessage(500);
		$expectedValue = array(
			'code' => 500,
			'title' => 'Internal Server Error',
			'message' => 'The server encountered an error processing your request.',
		);

		$this->assertEquals($error, $expectedValue);
	}

	public function testGetHeaders(){
		$expectedValue = array(
			'HTTP/1.1 200 OK',
			'Content-type: '.static::$response->getContentType(),
		);
		static::$response->setStatus(200);
		$headers = static::$response->getHeaders();

		$this->assertEquals($headers, $expectedValue);
	}

	public function testGetContentType(){
		$expectedValue = "application/json";

		$this->assertEquals(static::$response->getContentType(), $expectedValue);
	}

	public function testGetBody(){
		$data = array(
			'data' => 'someData',
		);

		$expectedValue = json_encode($data);

		static::$response->setParams($data);
		$this->assertEquals($expectedValue, static::$response->getBody());
	}

}