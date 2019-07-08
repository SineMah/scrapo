<?php

namespace Scrapo;

interface ParserInterface {

    /**
     * @return Dom
     */
    public function getDom();

    public function loadHtml(string $html);
}