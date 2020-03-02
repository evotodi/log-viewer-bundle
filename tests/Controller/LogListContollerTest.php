<?php

namespace Evotodi\LogViewerBundle\Tests\Controller;

use Evotodi\LogViewerBundle\Tests\LogViewerTestingKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class LogListContollerTest extends TestCase
{
	public function testLogList()
	{
		$kernel = new LogViewerTestingKernel();
		$client = new Client($kernel);

		$client->request('GET', '/logs');
		self::assertRegExp('/200|301/', strval($client->getResponse()->getStatusCode()));
	}
}


