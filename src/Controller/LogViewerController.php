<?php

namespace Evotodi\LogViewerBundle\Controller;

use Evotodi\LogViewerBundle\Model\LogView;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LogViewerController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function logViewAction(Request $request)
    {
        $log = $request->query->get('log');
        $delete = filter_var($request->query->get('delete'), FILTER_VALIDATE_BOOLEAN);

        $logDir = $this->container->get('kernel')->getLogDir();
        $logfile = "$logDir/$log";

        // Check that the requested file is within the log directory:
        // we probe one character ahead to make sure we validate the full directory name
        // and not just directories that starts with the same substring
        $canonicalLogDir = realpath($logDir);
        $canonicalLogFile = realpath($logfile);
        if(substr($canonicalLogFile, 0, strlen($canonicalLogDir)+1) !== $canonicalLogDir.DIRECTORY_SEPARATOR){
            throw $this->createAccessDeniedException();
        }

        if($delete) {
            unlink($logfile);
            return $this->redirectToRoute('greenskies_weblogviewer_loglist_loglist');
        }

        if (file_exists($logfile)) {
            $log = file_get_contents($logfile);
            $context['log'] = LogView::logToArray($log);
        } else {
            $context['noLog'] = true;
        }
        return $this->render('@EvotodiLogViewer/logView.html.twig', $context);
    }
}
