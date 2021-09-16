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
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): array
    {
		$loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		$loader->load('evo_log_viewer.xml');

		$configuration = $this->getConfiguration($configs, $container);
		$config = $this->processConfiguration($configuration, $configs);

		$definition = $container->getDefinition('evotodi_log_list.log_list_service');
		$definition->replaceArgument(1, $config['log_files']);
		$definition->replaceArgument(2, $config['show_app_logs']);
        $definition->replaceArgument(3, $config['app_pattern']);
        $definition->replaceArgument(4, $config['app_date_format']);

		return $config;
	}

	public function getAlias(): string
    {
		return 'evo_log_viewer';
	}


}
