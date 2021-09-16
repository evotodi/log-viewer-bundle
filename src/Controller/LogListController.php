<?php

namespace Evotodi\LogViewerBundle\Controller;

use Evotodi\LogViewerBundle\Service\LogList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class LogListController extends AbstractController implements ServiceSubscriberInterface
{
	private LogList $logList;

	public function __construct(LogList $logList)
	{
		$this->logList = $logList;
	}

	/**
     * @noinspection PhpUnused
     */
    public function logListAction(): Response
    {
		$logs = $this->logList->getLogList();
        return $this->render('@EvotodiLogViewer/listView.html.twig', [
            'logs' => $logs
        ]);
    }
}
