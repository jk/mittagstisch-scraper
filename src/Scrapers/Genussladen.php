<?php
namespace JK\Mittagstisch\Scrapers;

use Goutte\Client;
use JK\Mittagstisch\MenuItem;

class Genussladen extends BaseRestaurant
{
    /** @var string Homepage */
    const HOMEPAGE = 'http://dergenussladen.de/tagesangebote/';
    /** @var string Restaurant name */
    const NAME = 'Der Genuss Laden';
    const LATITUDE = 50.363696;
    const LONGITUDE = 8.736149;

    /**
     * Scrape the restaurant website
     * @return MenuItem[]
     */
    protected function scrape()
    {
        $client = new Client();

        $crawler = $client->request('GET', self::HOMEPAGE);

        $menu = [];
        $crawler->filter('#tablepress-10 > tbody > tr')->each(
            function ($crawler) use (&$menu) {
                /** @var \Symfony\Component\DomCrawler\Crawler $crawler */
                $dayOfWeek = $crawler->children()->getNode(0)->textContent;
                $wholeMenueString = $crawler->children()->getNode(1)->textContent;
                $menues = preg_split("/oder/i", $wholeMenueString);
                foreach ($menues as $menuId => $menuText) {
                    $menuText = trim(preg_replace('/\s+/', ' ', $menuText));
                    /**
                     * Matches
                     * Pasta mit Gemüse und Tomatensauce€8,90
                     * Pasta mit Gemüse und Tomatensauce €8,90
                     * Vorspeisensalat Pasta mit Gemüse und Tomatensauce €8,90
                     * Vorspeisensalat, Pasta mit Gemüse und Tomatensauce €8,90
                     * Vorspeisensalat, Pasta mit Gemüse und Tomatensauce € 8,90
                     * Vorspeisensalat, Pasta mit Gemüse und Tomatensauce €  8,90
                     * Vorspeisensalat, Pasta mit Gemüse und Tomatensauce 8,90€
                     * Vorspeisensalat, Pasta mit Gemüse und Tomatensauce 8,90 €
                     * Vorspeisensalat, Pasta mit Gemüse und Tomatensauce 8,90  €
                     */
                    if (preg_match('/(.*?)(\s*)((€\s*)(\d{1,2},\d{2})|(\d{1,2},\d{2})(\s*€))/mi', $menuText, $matches)) {
                        $menu[] = new MenuItem($dayOfWeek . ": " . $matches[1], $matches[3]);
                    }
                }
            });

        return $menu;
    }
}
