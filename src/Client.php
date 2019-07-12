<?php

namespace Scrapo;

use http\Client\Request;

class Client {

    /**
     * @var Dom|null
     */
    protected $dom = null;

    /**
     * @var null|ParserInterface
     */
    protected $parser = null;

    public function __construct($parser = null) {
        $className = '\\Scrapo\\Parser\\Native';

        if($parser && class_exists($parser)) {

            $className = $parser;
        }

        $this->parser = new $className();
        $interface = reset(class_implements($this->parser));

        if(strcmp($interface, 'Scrapo\\ParserInterface') !== 0) {

            throw new \Exception('Parser does not implement ParserInterface');
        }
    }

    /**
     * @return null|string
     */
    public function getDom() {
        return $this->dom;
    }

    /**
     * @param null|string $dom
     * @return $this
     */
    public function setDom($dom) {
        $this->dom = $dom;

        return $this;
    }

    /**
     * @param string $html
     * @return Dom|string|null
     */
    public function setHtml(string $html) {

        $this->dom = $html;
        // $this->dom = file_get_contents(__DIR__ . '/../dom_example.html');

        $this->parse();

        return $this->dom;
    }

    /**
     * @param String $url
     * @param String $method
     * @param array $auth
     * @return null|string;
     * @throws
     */
    public function fetch(string $url, string $method = 'GET', Array $auth = []) {
        $client = new \GuzzleHttp\Client();
        $options = [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.14; rv:67.0) Gecko/20100101 Firefox/67.0'
            ]
        ];
        $dom = null;
        $request = new \GuzzleHttp\Psr7\Request($method, $url);

        if(count($auth) > 0) {

            $options['auth'] = $auth;
        }

        $response = $client->send($request, $options);
        // $response = $client->request($method, $url, $options);

        return $this->setHtml((string) $response->getBody());
    }

    /**
     * @param string $domPath
     * @return Node[]
     */
    public function select(string $domPath) {

        return $this->parser->getDom()->search($domPath);
    }

    /**
     * @return $this
     */
    protected function parse() {

        $this->parser->loadHtml($this->dom);

        return $this;
    }
}