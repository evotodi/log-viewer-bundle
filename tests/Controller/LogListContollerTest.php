<?php

namespace Evotodi\LogViewerBundle\Tests\Controller;

use Evotodi\LogViewerBundle\Tests\LogViewerTestingKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;


class LogListContollerTest extends TestCase
{
	public function testLogList()
	{
		$kernel = new LogViewerTestingKernel();
		$client = new KernelBrowser($kernel);

		$client->request('GET', '/logs');
		self::assertRegExp('/200|301/', strval($client->getResponse()->getStatusCode()));
	}
}


