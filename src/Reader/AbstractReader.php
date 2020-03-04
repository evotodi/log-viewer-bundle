<?php

/*
 * This file is part of the monolog-parser package.
 *
 * (c) Robert Gruendler <r.gruendler@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Evotodi\LogViewerBundle\Reader;

use Evotodi\LogViewerBundle\Parser\LineLogParser;

class AbstractReader
{
	/**
	 * @return LineLogParser
	 */
    protected function getDefaultParser()
    {
	    return new LineLogParser();
    }
}
