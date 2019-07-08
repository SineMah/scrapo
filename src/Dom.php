<?php

namespace Scrapo;

class Dom {

    public $nodes = [];

    public function search($path) {
        $resultSet = [];

        foreach(array_keys($this->nodes) as $index) {
            $posTerm = strrpos($index, '>' . $path);
            $posLastChiffre = strrpos($index, '>');

            if($posTerm >= $posLastChiffre) {

                $resultSet[] = $this->nodes[$index];
            }
        }

        return $resultSet;
    }
}