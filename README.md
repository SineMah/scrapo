# Scrapo

Scrapo is an easy to use screen scraping lib.
 
String logic is used over regular expressions. The default Parser uses `DOMDocument`. Feel free to use an own parser, which generates a bunch of `Scrapo\Node` objects.

## Usage

### Load DOM from URL
```php
client = new \Scrapo\Client();

$res = $client->fetch('https://example.com/');

$results = $client->select('a.imALink');
```

### Set HTML as string
```php
client = new \Scrapo\Client();

$client->setHtml('<div>...</div>');

$results = $client->select('a.imALink');
```

## Result
The result consists an array of `Scrapo\Node` objects. 

## Alternative Parser
Make sure your parser holds the the attribute `dom` which is basically `Scrapo\Dom`.
```php
class MyParser implements ParserInterface {
...
}

client = new \Scrapo\Client('MyParser');
```

