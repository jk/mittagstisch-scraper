<?php
namespace JK\Mittagstisch\Scrapers;

use JK\Mittagstisch\MenuItem;
use JK\Mittagstisch\Restaurant;

abstract class BaseRestaurant implements Restaurant
{
    /** @var MenuItem[]|null Menu */
    protected $menu = null;

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
        return static::HOMEPAGE;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * @inheritDoc
     */
    public function getLongitude()
    {
        return static::LONGITUDE;
    }

    /**
     * @inheritDoc
     */
    public function getLatitude()
    {
        return static::LATITUDE;
    }
}
