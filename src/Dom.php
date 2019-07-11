<?php

namespace Scrapo;

class Dom {

    public $nodes = [];

    public function search($path) {
        $resultSet = [];
        $detailsPath = $this->parseNodePath($path);

        foreach(array_keys($this->nodes) as $index) {
            $details = $this->parseNodePath($index);

            if($this->compare($detailsPath, $details)) {

                $resultSet[] = $this->nodes[$index];
            }
        }

        return $resultSet;
    }

    protected function parseNodePath($path) {
        $segments = explode('>', $path);

        if((int) strpos($path, '>') > 0) {

            $path = end($segments);
        }

        return [
            'element' => $this->getCleanPart($path),
            'class' => $this->getClassPart($path),
            'id' => $this->getIdPart($path)
        ];
    }

    protected function getCleanPart($string) {
        $positions = [];
        $posHash = strpos($string, '#');
        $posDot = strpos($string, '.');

        if((int) $posDot > 0) {

            $positions[] = $posDot;
        }

        if((int) $posHash > 0) {

            $positions[] = $posHash;
        }

        sort($positions);

        if(count($positions) > 0) {

            $string = substr($string, 0, $positions[0]);
        }

        if($posHash === 0 || $posDot === 0) {

            $string = null;
        }

        return $string;
    }

    protected function getIdPart($string) {
        $id = null;
        $posHash = $posHash = strpos($string, '#');
        $posDot = strpos($string, '.');

        if($posHash !== false) {
            if((int) $posDot > 0) {

                $length = $posDot - $posHash;
            }else {

                $length = strlen($string) - $posHash;
            }

            $id = substr($string, $posHash, $length);
        }

        return $id;
    }

    protected function getClassPart($string) {
        $cnt = substr_count($string, '.');
        $pos = 0;
        $classes = [];

        for($i = 0; $i < $cnt; $i++) {
            $posDot = strpos($string, '.', $pos);
            $posEnd = strpos($string, '.', $posDot + 1);

            if($posEnd === false) {

                $length = strlen($string) - $posDot;
            }else {

                $length = $posEnd - $posDot;
            }

            $classes[] = substr($string, $posDot + 1, $length - 1);

            $pos = $posDot + 1;
        }

        return $classes;
    }

    protected function compare($searchDetails, $details) {
        $match = false;

        if(!is_null($searchDetails['element'])) {

            $match = $searchDetails['element'] === $details['element'];
        }

        if(!is_null($searchDetails['class'])) {

            $match = $searchDetails['class'] === $details['class'];
        }

        if(!is_null($searchDetails['id'])) {

            $match = $searchDetails['id'] === $details['id'];
        }

        return $match;
    }
}