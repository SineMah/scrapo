<?php

namespace Scrapo;

class Node {

    public $name = '';
    public $tag = '';
    public $class = '';
    public $id = '';
    public $attributes = '';
    public $value = '';
    public $textContent = '';
    public $path = [];
    public $line = '';
    public $hasChildNodes = '';
    public $hasAttributes = '';

    /**
     * @return string
     */
    public function getPathChild() {
        $path = [$this->name];

        if(strlen($this->id) > 0) {

            $path[] = '#' . $this->id;
        }

        if(strlen($this->class) > 0) {

            $path[] = '.' . $this->class;
        }

        return implode('', $path);
    }
}