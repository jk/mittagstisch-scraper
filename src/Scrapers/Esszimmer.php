<?php
namespace JK\Mittagstisch\Scrapers;

use Goutte\Client;
use JK\Mittagstisch\MenuItem;

class Esszimmer extends BaseRestaurant
{
    /** @var string Homepage */
    const HOMEPAGE = 'http://www.esszimmer-feinekost.de/';
    /** @var string Restaurant name */
    const NAME = 'Esszimmer Feinekost';
    const LATITUDE = 53.571950;
    const LONGITUDE = 9.958450;

    /**
     * Scrape the restaurant website
     * @return MenuItem[]
     */
    protected function scrape()
    {
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
}
