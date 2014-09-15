<?php

namespace Rmasters\ReflectionCast;

abstract class Parser
{
    abstract public function parse($comment);

    protected function getLines($comment)
    {
        $lines = explode(PHP_EOL, $comment);

        $lines = array_map(function ($line) {
            return rtrim(ltrim($line, ' */'));
        }, $lines);

        $lines = array_filter($lines, function ($line) {
            return strlen($line) > 0;
        });

        return $lines;
    }

    protected function parseLine($line)
    {
        if (preg_match('/^@([A-Za-z]+) /', $line, $matches)) {
            $type = $matches[1];
            $parts = explode(' ', substr($line, strlen($matches[0])));

            return array_merge([$type], $parts);
        }

        return false;
    }

    protected function isParam(array $line)
    {
        return count($line) >= 1 && $line[0] == 'param';
    }

    protected function isVar(array $line)
    {
        return count($line) >= 1 && $line[0] == 'var';
    }

    protected function hasExtra(array $line)
    {
        return count($line) > 1;
    }
}
