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

use Evotodi\LogViewerBundle\Models\LogFile;

interface LogParserInterface
{
    public function parse(string $logLine, LogFile $logFile, ?string $dateFormat, bool $useChannel, bool $useLevel, int $days, string $pattern, bool $useCarbon): mixed;
}
