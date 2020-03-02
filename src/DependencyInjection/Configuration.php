<?php

namespace Evotodi\LogViewerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder('framework');
		$rootNode = $treeBuilder->getRootNode();

		$rootNode->children()
			->arrayNode('log_files')
				->info('List of log files to show')
				->beforeNormalization()->ifString()->then(function ($v) { return [$v]; })->end()
				->prototype('scalar')->end()
			->end()
			->booleanNode('show_app_logs')->defaultTrue()->info('Whether to show logs in var/log')->end()
			;

		return $treeBuilder;
	}
}