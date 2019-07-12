<?php

namespace PhpNuts;

use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{

    public function testGetterSetter()
    {
        $person = new Person();
        $person->setFirstName('Bob');
        $this->assertEquals('Bob', $person->getFirstName());
    }


    public function testRss()
    {

        $rssUri = 'https://www.thelotter.com/rss.xml';
        // file_get_contents() requires allow_url_fopen
        // if this does not work try cURL or fsockopen()
        $xml = file_get_contents($rssUri);

        $dom = new \DOMDocument( "1.0", "UTF-8" );
        $dom->loadXML($xml);

        $ids = array(25,60,22,12,153,99,24,130,20,11,113,146,121,190,179,17,18,143,100,141,116,204,105,167,132,14,1,21,165,166,156,174,88,151,193,152,161,162,187,195,86,144,206,6,142,189,203,131,158,129,196,119,160,159,16,15,172,124,77,23,139,140,97,185,115,181,28,29,8,202,30,31,104,78,34,36,103,75,5,180,38,170,182,107,198,200,138,43,44);

        foreach ($ids as $id) {
            $xpath = new \DOMXpath($dom);
            $results = $xpath->query("//*[@lottery_id='{$id}']");
            if ($results instanceof \DOMNodeList) {

                /** @var \DOMElement $node */
                $node = $results->item(0);

                /** @var \DOMElement $nextDrawNode */
                $nextDraw = $node->getElementsByTagName('next_draw_date')->item(0)->textContent;

                echo "{$id}: " . $nextDraw;
            }
        }

    }

}