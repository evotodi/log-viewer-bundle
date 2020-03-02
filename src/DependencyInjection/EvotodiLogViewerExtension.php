<?php

namespace Evotodi\LogViewerBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class EvotodiLogViewerExtension extends Extension
{

	/**
	 * @param array $configs
	 * @param ContainerBuilder $container
	 * @throws Exception
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		$loader->load('evo_log_viewer.xml');

		$configuration = $this->getConfiguration($configs, $container);
		$config = $this->processConfiguration($configuration, $configs);

//		$config['log_files'][] = 'test1';
//		$config['log_files'][] = 'test2';
//		dump($config);die;
		return $config;
	}

	public function getAlias()
	{
		return 'evo_log_viewer';
	}


}