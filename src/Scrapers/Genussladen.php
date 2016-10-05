<?php
namespace JK\Mittagstisch\Scrapers;

use Goutte\Client;
use JK\Mittagstisch\MenuItem;
use JK\Mittagstisch\Restaurant;

class Genussladen implements Restaurant
{
    /** @var string Homepage */
    const HOMEPAGE = 'http://dergenussladen.de/tagesangebote/';
    /** @var string Restaurant name */
    const NAME = 'Der Genuss Laden';
    const LATITUDE = 50.363696;
    const LONGITUDE = 8.736149;
    /** @var MenuItem[]|null Menu */
    protected $menu = null;

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
        if ($this->menu === null) {
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
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @inheritDoc
     */
    public function getLongitude()
    {
        return self::LONGITUDE;
    }

    /**
     * @inheritDoc
     */
    public function getLatitude()
    {
        return self::LATITUDE;
    }
}
