<?php

namespace Evotodi\LogViewerBundle\Tests;

use Evotodi\LogViewerBundle\EvotodiLogViewerBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouteCollectionBuilder;

class LogViewerTestingKernel extends Kernel
{
	use MicroKernelTrait;

	public function __construct()
	{
		parent::__construct('test', true);
	}

	public function registerBundles()
	{
		return [
			new EvotodiLogViewerBundle(),
			new FrameworkBundle(),
		];
	}

	protected function configureRoutes(RouteCollectionBuilder $routes)
	{
		$routes->import(__DIR__.'/../src/Resources/config/routes.xml', '/logs');
	}

	protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
	{
		$c->loadFromExtension('framework', [
			'secret' => 'FO0'
		]);
	}

	public function getCacheDir()
	{
		return __DIR__.'/cache/'.spl_object_hash($this);
	}
}