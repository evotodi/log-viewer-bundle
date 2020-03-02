<?php
/**
 * Created by PhpStorm.
 * User: todd
 * Date: 02/11/17
 * Time: 8:59 PM
 */

namespace Evotodi\LogViewerBundle\Model;

use Greenskies\Collection;

class LogView
{
    public static function logToArray($log)
    {
        $lines = Collection::createFromString($log, LineView::class);
        $return = [];
        foreach ($lines as $line) {

            $return[] = $line->toArray();
        }
        return $return;
    }

}
