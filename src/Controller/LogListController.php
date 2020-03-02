<?php

namespace Evotodi\LogViewerBundle\Controller;

use Evotodi\LogViewerBundle\Model\LogList;
use Psr\Container\ContainerInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class LogListController extends AbstractController implements ServiceSubscriberInterface
{
	private $parameterBag;
	private $logFiles;
	private $useAppLogs;

	public function __construct(ParameterBagInterface $parameterBag, array $logFiles, bool $useAppLogs = false)
	{
		$this->parameterBag = $parameterBag;
		$this->logFiles = $logFiles;
		$this->useAppLogs = $useAppLogs;
	}

	/**
	 * @return Response
	 */
    public function logListAction()
    {
    	dump($this->logFiles);
    	dump($this->useAppLogs);

    	$logs = [];

    	if($this->useAppLogs){
		    $finder = new Finder();
		    $finder->files()->in($this->parameterBag->get('kernel.logs_dir'));
		    foreach ($finder as $file){
			    $logs[] = ['name' => $file->getFilename(), 'path' => $file->getRealPath(), 'pattern' => null];
		    }
	    }

    	foreach ($this->logFiles as $logFile){
    		if(is_null($logFile['name'])){
    			$logFile['name'] = basename($logFile['path']);
		    }
    		$logs[] = ['name' => $logFile['name'], 'path' => $logFile['path'], 'pattern' => $logFile['pattern']];
	    }

        return $this->render('@EvotodiLogViewer/listView.html.twig', [
            'logs' => $logs
        ]);
    }
}
