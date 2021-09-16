<?php

namespace Evotodi\LogViewerBundle\Service;


use Evotodi\LogViewerBundle\Models\LogFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;

class LogList
{
	private ParameterBagInterface $parameterBag;
	private array $logFiles;
	private bool $useAppLogs;
    private ?string $appPattern;
    private ?string $appDateFormat;

	protected array $levels = [
		"debug" => "DEBUG",
        "info" => "INFO",
        "notice" => "NOTICE",
        "warning" => "WARNING",
        "error" => "ERROR",
        "alert" => "ALERT",
        "critical" => "CRITICAL",
        "emergency" => "EMERGENCY",
	];
	public function __construct(ParameterBagInterface $parameterBag, array $logFiles, bool $useAppLogs = false, ?string $appPattern = null, ?string $appDateFormat = null)
	{
		$this->parameterBag = $parameterBag;
		$this->logFiles = $logFiles;
		$this->useAppLogs = $useAppLogs;
        $this->appPattern = $appPattern;
        $this->appDateFormat = $appDateFormat;
        if(is_null($this->appDateFormat)){
            $this->appDateFormat = 'Y-m-d H:i:s';
        }
    }

    /**
     * @return LogFile[]
     */
	public function getLogList(): array
    {
	    $logs = [];
		$id = 0;

	    if($this->useAppLogs){
		    $finder = new Finder();
            /** @noinspection MissingService */
            $finder->files()->in($this->parameterBag->get('kernel.logs_dir'));

		    foreach ($finder as $file){
                $l = new LogFile();
                $l->setId($id);
                $l->setName($file->getFilename());
                $l->setPath($file->getRealPath());
                $l->setPattern($this->appPattern);
                $l->setDateFormat($this->appDateFormat);
                $l->setExists(true);
                $l->setLevels($this->levels);
                $l->setSize($file->getSize());
                $l->setMTime($file->getMTime());
                $logs[] = $l;
			    $id++;
		    }
	    }

	    foreach ($this->logFiles as $logFile){
            $stats = ['size' => 0, 'mtime' => 0];
	    	$exists = true;
	    	if(!file_exists($logFile['path'])){
	    		$exists = false;
		    }else{
                $stats = stat($logFile['path']);
            }
		    if(is_null($logFile['name'])){
			    $logFile['name'] = basename($logFile['path']);
		    }

            $l = new LogFile();
            $l->setId($id);
            $l->setName($logFile['name']);
            $l->setPath($logFile['path']);
            $l->setPattern($logFile['pattern']);
            $l->setDays($logFile['days']);
            $l->setDateFormat($logFile['date_format']);
            $l->setExists($exists);
            $l->setLevels($logFile['levels']);
            $l->setUseChannel($logFile['use_channel']);
            $l->setUseLevel($logFile['use_level']);
            $l->setSize($stats['size']);
            $l->setMTime($stats['mtime']);
            $logs[] = $l;
		    $id++;
	    }
        return $logs;
    }
}
