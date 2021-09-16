<?php

namespace Evotodi\LogViewerBundle\Controller;

use Evotodi\LogViewerBundle\Reader\LogReader;
use Evotodi\LogViewerBundle\Service\LogList;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LogViewerController extends AbstractController
{
	private LogList $logList;

	public function __construct(LogList $logList)
	{
		$this->logList = $logList;
	}

	/**
     * @param Request $request
     * @return Response
     * @noinspection PhpUnused
     */
    public function logViewAction(Request $request): Response
    {
        $id = urldecode($request->query->get('id'));
        $delete = filter_var($request->query->get('delete'), FILTER_VALIDATE_BOOLEAN);
	    $logs = $this->logList->getLogList();
		$context = [];

        if(!file_exists($logs[$id]->getPath())){
	        throw new FileNotFoundException(sprintf("Log file \"%s\" was not found!", $logs[$id]['path']));
        }

        if($delete) {
            unlink($logs[$id]->getPath());
            return $this->redirectToRoute('_log_viewer_list');
        }

        $reader = new LogReader($logs[$id]);

        if(!is_null($logs[$id]->getPattern())){
        	$reader->getParser()->registerPattern('NewPattern', $logs[$id]->getPattern());
        	$reader->setPattern('NewPattern');
        }

	    $lines = [];
	    foreach ($reader as $line){
	    	try{
				$lines[] = [
					'dateTime' => $line['date'],
					'channel' => $line['channel'],
					'level' => $line['level'],
					'message' => $line['message'],
				];
		    }catch (Exception $e){
	    		continue;
		    }

	    }

	    if(!empty($lines)){
	    	$context['log'] = $lines;
	    }else{
	    	$context['noLog'] = true;
	    }

	    $context['levels'] = $logs[$id]->getLevels();
        $context['use_channel'] = $logs[$id]->isUseChannel();
        $context['use_level'] = $logs[$id]->isUseLevel();

        return $this->render('@EvotodiLogViewer/logView.html.twig', $context);
    }
}
