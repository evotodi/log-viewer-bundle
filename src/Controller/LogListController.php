<?php

namespace Evotodi\LogViewerBundle\Controller;

use Evotodi\LogViewerBundle\Model\LogList;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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
		    $logDir = $this->parameterBag->get('kernel.logs_dir');
            $logs = (new LogList())->getLogList($logDir);
	    }

        return $this->render('@EvotodiLogViewer/listView.html.twig', [
            'logs' => $logs
        ]);
    }

	public static function getSubscribedServices()
	{
		return [

		];
	}


}
