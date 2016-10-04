<?php
namespace JK\Mittagstisch;

interface Restaurant
{
    /**
     * Get the menu
     * @return MenuItem[]
     */
    public function getMenu();

    /**
     * It's possible that the restaurant has no valid menu for today (late menu publishing, closed, etc.).
     * Also if there was a problem while scraping you can opt out from displaying this restraurants menu.
     * @return bool True if restaurant has a menu for today, otherwise false
     */
    public function isValidMenuForToday();

    /**
     * Get the homepage of the restaurant
     * @return string Homepage
     */
    public function getHomepage();

    /**
     * Get the name of the restaurant
     * @return string Restaurant name
     */
    public function getName();

    /**
     * Get the longitude of the restaurant
     * @return float Restaurant Longitude
     */
    public function getLongitude();

    /**
     * Get the latitude of the restaurant
     * @return float Restaurant latitude
     */
    public function getLatitude();
}
