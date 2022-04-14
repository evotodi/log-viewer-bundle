<?php

namespace Evotodi\LogViewerBundle\Controller;

use Evotodi\LogViewerBundle\Service\LogList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Twig\Environment;

class LogListController extends AbstractController implements ServiceSubscriberInterface
{
	private LogList $logList;
    private Environment $twig;

	public function __construct(LogList $logList, Environment $twig)
	{
		$this->logList = $logList;
        $this->twig = $twig;
    }

	/**
     * @noinspection PhpUnused
     */
    public function logListAction(): Response
    {
		$logs = $this->logList->getLogList();
        return new Response($this->twig->render('@EvotodiLogViewer/listView.html.twig', [
            'logs' => $logs
        ]));
    }
}
