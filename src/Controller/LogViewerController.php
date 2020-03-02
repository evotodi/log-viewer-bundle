<?php

namespace Evotodi\LogViewerBundle\Controller;

use Evotodi\LogViewerBundle\Model\LogView;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
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
        $log = urldecode($request->query->get('log'));
        $name = urldecode($request->query->get('name'));
        $delete = filter_var($request->query->get('delete'), FILTER_VALIDATE_BOOLEAN);

        dump($name);
        dump($log);

        if(!file_exists($log)){
	        throw new FileNotFoundException(sprintf("Log file \"%s\" was not found!", $log));
        }

        if($delete) {
            unlink($log);
            return $this->redirectToRoute('_log_viewer_list');
        }

        if (file_exists($log)) {
            $log = file_get_contents($log);
            $context['log'] = LogView::logToArray($log);
        } else {
            $context['noLog'] = true;
        }
        return $this->render('@EvotodiLogViewer/logView.html.twig', $context);
    }
}
