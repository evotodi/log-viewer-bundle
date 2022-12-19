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

use ArrayAccess;
use Countable;
use Evotodi\LogViewerBundle\Models\LogFile;
use Evotodi\LogViewerBundle\Parser\LineLogParser;
use Evotodi\LogViewerBundle\Parser\LogParserInterface;
use Exception;
use Iterator;
use RuntimeException;
use SplFileObject;

class LogReader extends AbstractReader implements Iterator, ArrayAccess, Countable
{
    protected LogFile $logFile;
    protected SplFileObject $file;
    protected int $lineCount;
    protected LogParserInterface $parser;

    public int $days;
    public string $pattern;
	public ?string $dateFormat;
    public bool $useChannel;
    public bool $useLevel;
    public bool $useCarbon;


    public function __construct(LogFile $logFile, string $pattern = 'default')
    {
        $this->logFile = $logFile;
        $this->file = new SplFileObject($logFile->getPath(), 'r');
        $i          = 0;
        while (!$this->file->eof()) {
            $this->file->current();
            $this->file->next();
            $i++;
        }

        $this->days = $logFile->getDays();
        $this->pattern = $pattern;
		$this->dateFormat = $logFile->getDateFormat();
        $this->useChannel = $logFile->isUseChannel();
        $this->useLevel = $logFile->isUseLevel();
        $this->useCarbon = $logFile->useCarbon();

        $this->lineCount = $i;
        $this->parser = $this->getDefaultParser();
    }

    public function getParser(): LogParserInterface|LineLogParser
    {
        /** @noinspection PhpUnnecessaryLocalVariableInspection */
        $p =  & $this->parser;
        return $p;
    }
    public function setPattern(string $pattern = 'default' )
    {
        $this->pattern = $pattern;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return $this->lineCount < $offset;
    }

	/**
	 * {@inheritdoc}
	 * @throws Exception
	 */
    public function offsetGet($offset): mixed
    {
        $key = $this->file->key();
        $this->file->seek($offset);
        $log = $this->current();
        $this->file->seek($key);
        $this->file->current();

        return $log;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        throw new RuntimeException("LogReader is read-only.");
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        throw new RuntimeException("LogReader is read-only.");
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->file->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        $this->file->next();
    }

	/**
	 * {@inheritdoc}
	 * @throws Exception
	 */
    public function current(): mixed
    {
        return $this->parser->parse($this->file->current(), $this->logFile, $this->dateFormat, $this->useChannel, $this->useLevel, $this->days, $this->pattern, $this->useCarbon);
    }

    /**
     * {@inheritdoc}
     */
    public function key(): int
    {
        return $this->file->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return $this->file->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return $this->lineCount;
    }
}
