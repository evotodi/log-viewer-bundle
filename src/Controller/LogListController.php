<?php

namespace Evotodi\LogViewerBundle\Controller;

use Evotodi\LogViewerBundle\Model\LogList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LogListController extends AbstractController
{
    /**
     * @return Response
     */
    public function logListAction()
    {
        $kernel = $this->container->get('kernel');
        $logDir = $kernel->getLogDir();
        $logs = (new LogList())->getLogList($logDir);
        return $this->render('@EvotodiLogViewer/listView.html.twig', [
            'logs' => $logs
        ]);
    }

}
