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
use Exception;
use RuntimeException;

class LineLogParser implements LogParserInterface
{

    protected array $pattern = [
        'default' => '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>[^\[\{].*[\]\}])/',
    ];


	/**
	 * @throws Exception
	 */
    public function parse(string $log, string $dateFormat, int $days = 1, string $pattern = 'default'): array
    {
        if (!is_string($log) || strlen($log) === 0) {
            return array();
        }

        preg_match($this->pattern[$pattern], $log, $data);

        if (!isset($data['date'])) {
            return array();
        }

        $date = DateTime::createFromFormat($dateFormat, $data['date']);

        $array = array(
            'date'    => $date,
            'logger'  => $data['logger'],
            'level'   => $data['level'],
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
