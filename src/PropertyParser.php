<?php

namespace Rmasters\ReflectionCast;

class PropertyParser extends Parser
{
    public function parse($comment)
    {
        $lines = $this->getLines($comment);

        $lines = array_map(function ($line) {
            return $this->parseLine($line);
        }, $lines);

        $lines = array_filter($lines, function ($line) {
            return $line && $this->isVar($line);
        });

        $lines = array_map(function ($line) {
            return array_slice($line, 1);
        }, $lines);

        if (count($lines) > 0) {
            $property = new Property();

            foreach ($lines as $line) {
                if (count($line) >= 1) {
                    // @var {type} [...]
                    $property->setType($line[0]);
                }
            }

            return $property;
        }

        return false;
    }
}
