<?php

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\CssSelector\CssSelector;

class Crawler
{
    protected $_url;
    protected $_depth;
    protected $_seen = array();
    protected $_host;
    protected $_client;
    protected $_sitemap;

    public function __construct($url, $depth = 3)
    {
        $this->_url = $url;
        $this->_depth = $depth;
        $parsed = parse_url($url);
        $this->_host = $parsed['host'];
    }//__construct()

    public function run()
    {
        // check and set base uri
        if($this->isValid($this->_url,1)) {
            if(!$this->_client) {
                $this->_client = new Client(array('base_uri' => $this->_url));
            }
        }

        if($this->hasSitemap()) {
            $this->getContentFromSitemap();
        } else {
            $this->crawlPage($this->_url, $this->_depth);
        }

        return $this->_seen;
    }//run()

    public function crawlPage($url, $depth)
    {
        if(!$this->isValid($url,$depth)) {
           return;
        }

        // add to the seen URL
        $this->_seen[$url] = true;
        // get content an return code
        list($content, $httpCode) = $this->getContent($url);

        // $this->_printResult($url, $depth, $httpCode);
        // process links
        
        if($httpCode == 200)
            $this->processLinks($content, $url, $depth);
    }//crawlPage()

    protected function isValid($url, $depth)
    {
        // if the URL doesn't belongs to the domain of the original URL given,
        // if the depth is 0 and if the URL was already crawled, return false
        if(strpos($url, $this->_host) === false ||
            $depth === 0 ||
            isset($this->_seen[$url])) 
            {
            return false;
        }

        return true;
    }//isValid()

    protected function hasSitemap() {
        $response = $this->_client->request('GET', '/robots.txt', ['http_errors' => false]);

        if($response->getStatusCode() === 200) {
            // search for the sitemap.xml
            $pattern = '/(http|https):.*/i';
            preg_match($pattern,(string)$response->getBody(),$matches);

            if(!$matches)
                return false;

            // retrieve the sitemap
            $mapResponse = $this->_client->request('GET',$matches[0],['http_errors' => false]);
            if($mapResponse->getStatusCode() !== 200)
                return false;

            // check for errors
            libxml_use_internal_errors(true);
            $doc = simplexml_load_string((string)$mapResponse->getBody());

            if(!$doc) 
                return false;
            else{
                $this->_sitemap = (string)$mapResponse->getBody();
                return true;
            }
        }
        
        return false;
    }//hasSitemap()

    protected function getContentFromSitemap()
    {
        CssSelector::disableHtmlExtension();
        $crawler = new \DOMDocument();
        $crawler->loadXml($this->_sitemap);
        // select all tags 'loc' that holds the URL
        $locs = $crawler->getElementsByTagName('loc');

        foreach($locs as $element) {
            $this->_seen[$element->nodeValue] = true;
        }
    }//getConentFromSitemap()

    protected function getContent($url)
    {
        try {
            $response = $this->_client->request('GET', $url, ['http_errors' => false]);
        } catch (RequestException $e) {
            echo $e->getRequest();
            var_dump($this->_seen);
            if ($e->hasResponse()) {
                echo $e->getResponse();
            }
        }

        return array((string)$response->getBody(),$response->getStatusCode());
    }//getContent()

    protected function _printResult($url, $depth, $httpcode)
    {
        ob_end_flush();
        $currentDepth = $this->_depth - $depth;
        $count = count($this->_seen);
        echo "N::$count,CODE::$httpcode,DEPTH::$currentDepth URL::$url <br>";
        ob_start();
        flush();
    }

    protected function processLinks($content, $url, $depth)
    {
        $crawler = new DomCrawler($content);
        $anchors = $crawler->filter('a');

        foreach($anchors as $element) {
            $href = $element->getAttribute('href');

            if (0 !== strpos($href, 'http')) {
                $path = '/' . ltrim($href, '/');
                if (extension_loaded('http')) {
                    $href = http_build_url($url, array('path' => $path));
                } else {
                    $parts = parse_url($url);
                    $href = $parts['scheme'] . '://';
                    if (isset($parts['user']) && isset($parts['pass'])) {
                        $href .= $parts['user'] . ':' . $parts['pass'] . '@';
                    }
                    $href .= $parts['host'];
                    if (isset($parts['port'])) {
                        $href .= ':' . $parts['port'];
                    }
                    $href .= $path;
                }
            }

            $this->crawlPage($href,$depth - 1);
        }
    }//processLinks()
    
}//Crawler

