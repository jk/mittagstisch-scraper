<?php
namespace JK\Mittagstisch\Scrapers;

use Goutte\Client;
use JK\Mittagstisch\MenuItem;
use JK\Mittagstisch\Restaurant;


class Esszimmer implements Restaurant
{
    /** @var string Homepage */
    const HOMEPAGE = 'http://www.esszimmer-feinekost.de/';
    /** @var string Restaurant name */
    const NAME = 'Esszimmer Feinekost';
    /** @var MenuItem[]|null Menu */
    protected $menu = null;

    /**
     * Scrape the restaurant website
     * @return MenuItem[]
     */
    protected function scrape() {
        $client = new Client();

        $crawler = $client->request('GET', self::HOMEPAGE);
        $link = $crawler->selectLink('TAGESKARTE')->link();
        $crawler = $client->click($link);

        $menu = [];
        $found_center_aligns = 0;
        $crawler->filter('#karte > div.dbraun > div > table > tbody > tr')->each(
            function ($crawler) use (&$menu, &$found_center_aligns) {
            if ($found_center_aligns == 2) {
                return;
            }

            /** @var \Symfony\Component\DomCrawler\Crawler $crawler */
            $node = $crawler->getNode(0);

            $align = $node->getAttributeNode('align');
            if (@$align->value == 'center') {
                $found_center_aligns += 1;

                return;
            }

            if ($found_center_aligns != 1) {
                return;
            }

            $td_node = $crawler->filter('td');

            $label = $td_node->getNode(0)->textContent;
            $price = $td_node->getNode(1)->textContent;

            $menu[] = new MenuItem($label, $price);
        });

        return $menu;
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

    /**
     * @inheritDoc
     */
    public function isValidMenuForToday()
    {
        return true;
    }
}
