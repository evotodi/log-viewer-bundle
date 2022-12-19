<?php

/*
 * This file is part of the monolog-parser package.
 *
 * (c) Robert Gruendler <r.gruendler@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Evotodi\LogViewerBundle\Parser;

use DateTime;
use Evotodi\LogViewerBundle\Models\LogFile;
use Exception;
use RuntimeException;

class LineLogParser implements LogParserInterface
{

    protected array $pattern = [
        'default' => '/\[(?P<date>.*)\] (?P<channel>\w+).(?P<level>\w+): (?P<message>[^\[\{].*[\]\}])/',
    ];


	/**
	 * @throws Exception
	 */
    public function parse(string $logLine, LogFile $logFile, ?string $dateFormat, bool $useChannel, bool $useLevel, int $days = 1, string $pattern = 'default', bool $useCarbon = false): array
    {
        if (strlen($logLine) === 0) {
            return array();
        }
        preg_match($this->pattern[$pattern], $logLine, $data);
        if (!isset($data['date'])) {
            return array();
        }

        if ($useCarbon){
            if(is_null($dateFormat)){
                try {
                    $date = new \Carbon\Carbon($data['date']);
                } catch (\Carbon\Exceptions\InvalidFormatException) {
                    $date = false;
                }
            } else {
                try {
                    $date = \Carbon\Carbon::createFromFormat($dateFormat, $data['date']);
                } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                    throw new \Carbon\Exceptions\InvalidFormatException(sprintf("Invalid date_format in evo_log_viewer for logfile %s", $logFile->getPath()));
                }
            }
        }else{
            if (is_null($dateFormat)) {
                try {
                    $date = new DateTime($data['date']);
                } catch (Exception $e) {
                    $date = false;
                }
            }else{
                $date = DateTime::createFromFormat($dateFormat, $data['date']);
            }
        }

        $array = array(
            'date'    => $date,
            'channel'  => $useChannel ? $data['channel'] : '',
            'level'   => $useLevel ? $data['level'] : '',
            'message' => $data['message']
        );

        if (0 === $days) {
            return $array;
        }
        if (isset($date) && $date instanceof DateTime) {
            $d2 = new DateTime('now');

            if ($date->diff($d2)->days < $days) {
                return $array;
            } else {
                return [];
            }
        }
        return [];
    }

    /**
     * @throws RuntimeException
     */
    public function registerPattern(string $name, string $pattern)
    {
        if (!isset($this->pattern[$name])) {
            $this->pattern[$name] = $pattern;
        } else {
            throw new RuntimeException("Pattern $name already exists");
        }
    }
}
