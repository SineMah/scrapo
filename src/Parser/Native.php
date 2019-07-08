<?php

namespace Scrapo\Parser;

use Scrapo\Dom;
use Scrapo\Node;
use Scrapo\ParserInterface;

class Native implements ParserInterface {

    /**
     * @var Dom|null
     */
    protected $dom = null;

    public function __construct() {

        $this->dom = new Dom();

        libxml_use_internal_errors(true);
    }

    /**
     * @param String $html
     * @return $this;
     */
    public function loadHtml(string $html) {
        $dom = new \DOMDocument();

        $dom->encoding = 'UTF-8';

        $dom->loadHTML($html);

//        $this->dom = $dom;
        $this->iterate($dom);

        return $this;
    }

    /**
     * @param \DOMElement $node
     * @return Node
     */
    protected function getNodeProperties($node) {
        $nodeInstance = new Node();

        $nodeInstance->name = $node->nodeName;
        $nodeInstance->tag = $node->tagName;
        $nodeInstance->attributes = $node->attributes;
        $nodeInstance->textContent = $node->textContent;
        $nodeInstance->path = $node->getNodePath();
        $nodeInstance->line = $node->getLineNo();
        $nodeInstance->hasChildNodes = $node->hasChildNodes();
        $nodeInstance->hasAttributes = $node->hasAttributes();

        if(!$nodeInstance->textContent) {

            $nodeInstance->textContent = $node->nodeValue;
        }

        if($nodeInstance->hasAttributes) {

            $nodeInstance->id = $node->getAttribute('id');
            $nodeInstance->class = str_replace(' ', '.', $node->getAttribute('class'));
        }

        return $nodeInstance;
    }

    /**
     * @param $path
     * @return string
     */
    protected function buildPath($path) {

        return implode('>', $path);
    }

    /**
     * @param $dom
     * @param array $path
     */
    protected function iterate($dom, Array $path = []) {
        $scrapoNode = $this->getNodeProperties($dom);
        // $path = array_merge($path, [$scrapoNode->getPathChild()]);

        // var_dump($path, $scrapoNode->getPathChild(), $scrapoNode);

        // $this->dom->nodes[$this->buildPath($path)] = $scrapoNode;

        // var_dump($this->buildPath($path));

        if($scrapoNode->hasChildNodes) {

            foreach($dom->childNodes as $node) {
                $scrapoNode = $this->getNodeProperties($node);

                if($scrapoNode->name === '#text') {

                    continue;
                }

                if($scrapoNode->textContent) {
                    $path = array_merge($path, [$scrapoNode->getPathChild()]);

                    $this->dom->nodes[$this->buildPath($path)] = $scrapoNode;

                    if($scrapoNode->hasChildNodes) {

                        $this->iterate($node, $path);
                    }
                }
            }
        }
    }

    /**
     * @return Dom|null
     */
    public function getDom() {

        return $this->dom;
    }
}