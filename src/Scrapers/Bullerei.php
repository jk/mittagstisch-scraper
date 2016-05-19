<?php
namespace JK\Mittagstisch\Scrapers;

use Goutte\Client;
use JK\Mittagstisch\MenuItem;
use JK\Mittagstisch\Restaurant;


class Bullerei implements Restaurant
{
    /** @var string Homepage */
    const HOMEPAGE = 'http://bullerei.com/fuer-euch/mittagstisch';
    /** @var string Restaurant name */
    const NAME = 'Bullerei Deli';
    /** @var MenuItem[]|null Menu */
    protected $menu = null;

    /**
     * Scrape the restaurant website
     * @return MenuItem[]
     */
    protected function scrape() {
        $client = new Client();

        $crawler = $client->request('GET', self::HOMEPAGE);

        $menu = [];
        $crawler->filter('body > div.row.beitrag > div > div.karten.col-md-10.col-md-offset-1 > div')->each(
            function ($crawler) use (&$menu) {
                /** @var \Symfony\Component\DomCrawler\Crawler $crawler */
                $node = $crawler->getNode(0);

//                echo $node->textContent . PHP_EOL . PHP_EOL;
//                var_dump($node->textContent);

                if(preg_match('/(.*?) (\d{1,2},\d{2}) € $/mi', $node->textContent, $matches)) {
                    $menu[] = new MenuItem($matches[1], $matches[2]);
                }
//                var_dump($crawler);

//                $align = $node->getAttributeNode('align');
//
//                $td_node = $crawler->filter('td');
//
//                $label = $td_node->getNode(0)->textContent;
//                $price = $td_node->getNode(1)->textContent;
//
//                $menu[] = new MenuItem($label, $price);
            });

        return $menu;
    }

    /**
     * @inheritDoc
     */
    public function isValidMenuForToday()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getMenu()
    {
        if($this->menu === null) {
            $this->menu = $this->scrape();
        }

        return $this->menu;
    }

    /**
     * @inheritDoc
     */
    public function getHomepage()
    {
        return self::HOMEPAGE;
    }

    /**
     * @inheritDoc
     */
    public function getName() {
        return self::NAME;
    }
}
