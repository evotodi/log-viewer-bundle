<?php

namespace Evotodi\LogViewerBundle\Model;

use SqlFormatter;

class LineView
{
    private $dateTime;
    private $type;
    private $level;
    private $message;
    private $json;

    public function __construct($line)
    {
        $dateLength = strlen('[yyyy-mm-dd hh:mm:ss] ');
        // borrowed parsing from here, might be able to borrow more!
        // https://gist.github.com/chriskonnertz/46423c15a38badf3133fda391448667e
        $this->dateTime = trim(substr($line, 0, $dateLength));
        $line = substr($line, $dateLength);
        $pos = strpos($line, ':');
        $this->level = strtolower(substr($this->type, $pos + 1));
        list($this->type, $this->level) = explode('.', substr($line, 0, $pos));
        $line = substr($line, $pos + 1);
        $jsonStart = strpos($line,'{');
        if ($jsonStart > 0) {
            $this->message = substr($line, 0, $jsonStart);
            $line = substr($line, $jsonStart);
            $jsonEnd = strrpos($line, '}');
            $json = substr($line, 0, $jsonEnd + 1);
            $this->json = static::json2Html($json) ;
        } else {
            $this->message = str_replace(' [] []', '', $line);
            if ($this->type === 'propel' || $this->type === "doctrine") {
                $this->message = SqlFormatter::format($this->message);
            }
            $this->json = false;
        }
    }

    public function toArray()
    {
        return [
            'dateTime' => $this->dateTime,
            'type' => $this->type,
            'level' => $this->level,
            'message' => $this->message,
            'json' => $this->json
        ];
    }


    /**
     * Format a flat JSON string to make it more human-readable
     *
     * @param string $json The original JSON string to process
     *        When the input is not a string it is assumed the input is RAW
     *        and should be converted to JSON first of all.
     * @return string Indented version of the original JSON string
     */
    private static function json2Html($json)
    {
        // original code: http://www.daveperrett.com/articles/2008/03/11/format-json-with-php/
        if (!is_string($json)) {
            if (phpversion() && phpversion() >= 5.4) {
                return json_encode($json, JSON_PRETTY_PRINT);
            }
            $json = json_encode($json);
        }
        $result      = '';
        $pos         = 0;               // indentation level
        $strLen      = strlen($json);
        $indentStr   = "&nbsp;&nbsp;&nbsp;&nbsp;";
        $newLine     = "<br>";
        $prevChar    = '';
        $outOfQuotes = true;

        for ($i = 0; $i < $strLen; $i++) {
            // Speedup: copy blocks of input which don't matter re string detection and formatting.
            $copyLen = strcspn($json, $outOfQuotes ? " \t\r\n\",:[{}]" : "\\\"", $i);
            if ($copyLen >= 1) {
                $copyStr = substr($json, $i, $copyLen);
                // Also reset the tracker for escapes: we won't be hitting any right now
                // and the next round is the first time an 'escape' character can be seen again at the input.
                $prevChar = '';
                $result .= $copyStr;
                $i += $copyLen - 1;      // correct for the for(;;) loop
                continue;
            }

            // Grab the next character in the string
            $char = substr($json, $i, 1);

            // Are we inside a quoted string encountering an escape sequence?
            if (!$outOfQuotes && $prevChar === '\\') {
                // Add the escaped character to the result string and ignore it for the string enter/exit detection:
                $result .= $char;
                $prevChar = '';
                continue;
            }
            // Are we entering/exiting a quoted string?
            if ($char === '"' && $prevChar !== '\\') {
                $outOfQuotes = !$outOfQuotes;
            }
            // If this character is the end of an element,
            // output a new line and indent the next line
            else if ($outOfQuotes && ($char === '}' || $char === ']')) {
                $result .= $newLine;
                $pos--;
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
            // eat all non-essential whitespace in the input as we do our own here and it would only mess up our process
            else if ($outOfQuotes && false !== strpos(" \t\r\n", $char)) {
                continue;
            }

            // Add the character to the result string
            $result .= $char;
            // always add a space after a field colon:
            if ($outOfQuotes && $char === ':') {
                $result .= ' ';
            }

            // If the last character was the beginning of an element,
            // output a new line and indent the next line
            else if ($outOfQuotes && ($char === ',' || $char === '{' || $char === '[')) {
                $result .= $newLine;
                if ($char === '{' || $char === '[') {
                    $pos++;
                }
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
            $prevChar = $char;
        }

        return $result;
    }
}
