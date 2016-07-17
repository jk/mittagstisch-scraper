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
        $crawler->filter('#tablepress-10 > tbody > tr')->each(
            function ($crawler) use (&$menu) {
                /** @var \Symfony\Component\DomCrawler\Crawler $crawler */
                $dayOfWeek = $crawler->children()->getNode(0)->textContent;
                $wholeMenueString = $crawler->children()->getNode(1)->textContent;
                $menues = preg_split("/oder/i", $wholeMenueString);
                foreach ($menues as $menuId => $menuText) {
                    $menuText = trim(preg_replace('/\s+/', ' ', $menuText));
                    if (preg_match('/(.*?) €(\d{1,2},\d{2})/mi', $menuText, $matches)) {
                        $menu[] = new MenuItem($dayOfWeek . ": " . $matches[1], $matches[2]);
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
