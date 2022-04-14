<?php

namespace Evotodi\LogViewerBundle;

use Evotodi\LogViewerBundle\DependencyInjection\EvotodiLogViewerExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EvotodiLogViewerBundle extends Bundle
{
	public function getContainerExtension(): ?ExtensionInterface
	{
		if(null === $this->extension){
			$this->extension = new EvotodiLogViewerExtension();
		}

		return $this->extension;
	}

}
