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
	/**
	 * @var LogList
	 */
	private $logList;

	public function __construct(LogList $logList)
	{
		$this->logList = $logList;
	}

	/**
     * @param Request $request
     * @return Response
     */
    public function logViewAction(Request $request)
    {
        $id = urldecode($request->query->get('id'));
        $delete = filter_var($request->query->get('delete'), FILTER_VALIDATE_BOOLEAN);
	    $logs = $this->logList->getLogList();

        if(!file_exists($logs[$id]['path'])){
	        throw new FileNotFoundException(sprintf("Log file \"%s\" was not found!", $logs[$id]['path']));
        }

        if($delete) {
            unlink($logs[$id]['path']);
            return $this->redirectToRoute('_log_viewer_list');
        }

        $reader = new LogReader($logs[$id]['path'], $logs[$id]['date_format'], $logs[$id]['days']);
        if(!is_null($logs[$id]['pattern'])){
        	$reader->getParser()->registerPattern('NewPattern', $logs[$id]['pattern']);
        	$reader->setPattern('NewPattern');
        }

	    $lines = [];
	    foreach ($reader as $line){
	    	try{
				$lines[] = [
					'dateTime' => $line['date'],
					'type' => $line['logger'],
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

        return $this->render('@EvotodiLogViewer/logView.html.twig', $context);
    }
}
