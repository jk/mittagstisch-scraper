<?php
/**
 * Created by PhpStorm.
 * User: mmenzel
 * Date: 05.10.2016
 * Time: 12:23
 */


namespace JK\Mittagstisch\Scrapers;

use Goutte\Client;
use JK\Mittagstisch\MenuItem;
use JK\Mittagstisch\Restaurant;

class Cheers implements Restaurant
{
    /** @var string Homepage */
    const HOMEPAGE = 'http://cheersbadnauheim.de/mittagstisch-2/';
    /** @var string Restaurant name */
    const NAME = 'Cheers';
    const LATITUDE = 50.368085;
    const LONGITUDE = 8.747992;
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
        $lastMatchedDayOfTheWeek = null;

        $crawler->filter('#post-26 > div > p')->each(
            function ($crawler) use (&$menu, &$lastMatchedDayOfTheWeek) {
                /** @var \Symfony\Component\DomCrawler\Crawler $crawler */
                $menuString = $crawler->text();
                $startingStrings = [
                    'Montag',
                    'Dienstag',
                    'Mittwoch',
                    'Donnerstag',
                    'Freitag',
                    'Samstag',
                    'Sonntag',
                ];

                foreach ($startingStrings as $dayOfTheWeek) {
                    $matches = [];
                    if (preg_match("/$dayOfTheWeek(\s?\d{1,2}\.\d{1,2}\.?\s*|\s*)(.*?)(\d{1,2},\d{1,2})/i", $menuString, $matches)) {
                        $menu[] = new MenuItem($dayOfTheWeek . ": " . $matches[2], $matches[3]);
                        $lastMatchedDayOfTheWeek = $dayOfTheWeek;
                        break;
                    } elseif (preg_match("/\+\s*(.*?)(\d{1,2},\d{1,2})/i", $menuString, $matches)) {
                        $menu[] = new MenuItem($lastMatchedDayOfTheWeek . ": " . $matches[1], $matches[2]);
                        break;
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
