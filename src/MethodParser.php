<?php

namespace Rmasters\ReflectionCast;

class MethodParser extends Parser
{
    public function parse($comment)
    {
        $lines = $this->getLines($comment);

        $lines = array_map(function ($line) {
            return $this->parseLine($line);
        }, $lines);

        $lines = array_filter($lines, function ($line) {
            return $line && $this->isParam($line) && $this->hasExtra($line);
        });

        $lines = array_map(function ($line) {
            return array_slice($line, 1);
        }, $lines);

        if (count($lines) > 0) {
            $method = new Method();

            $index = 0;
            foreach ($lines as $line) {
                if (count($line) == 1) {
                    // @param {type}
                    $method->addParam($index++, $line[0]);
                } else if (count($line) >= 2) {
                    // @param {type} ${name} [...]
                    $method->addParam($index++, $line[0], ltrim($line[1], '$'));
                }
            }

            return $method;
        }

        return false;
    }
}
