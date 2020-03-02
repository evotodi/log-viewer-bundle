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
				->useAttributeAsKey('log_name')
				->arrayPrototype()
					->children()
						->scalarNode('path')->info("Use full path")->end()
						->scalarNode('name')->info("Pretty name to display else file name")->defaultNull()->end()
						->scalarNode('pattern')->info('See ddtraceweb/monolog-parser for patterns.')->defaultNull()->end()
						->scalarNode('pattern_name')->info('See ddtraceweb/monolog-parser')->defaultNull()->end()
					->end()
				->end()
			->end()
			->booleanNode('show_app_logs')->defaultTrue()->info('Show App logs in var/log')->end()
			;

		return $treeBuilder;
	}
}