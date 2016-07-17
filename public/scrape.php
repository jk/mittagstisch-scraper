<?php
require __DIR__ . '/../vendor/autoload.php';

use JK\Mittagstisch\Restaurant;

/** @var Restaurant[] $restaurants */
$restaurants = [
    new JK\Mittagstisch\Scrapers\Esszimmer(),
    new JK\Mittagstisch\Scrapers\Bullerei(),
    new JK\Mittagstisch\Scrapers\Genussladen
];

function median(array $elements) {
    if (count($elements) == 0) {
        return 0;
    }

    if (count($elements) == 1) {
        return $elements[0];
    }

    sort($elements);
    $pivot = (count($elements) / 2);
    if(count($elements) % 2 == 1) {
        return $elements[$pivot - 1];
    } else {
        $lower_median = floor($pivot) - 1;
        $tmp = array_slice($elements, $lower_median, 2);

        return ($tmp[0] + $tmp[1]) / 2;
    }
}

function formatPrice($price, $currency_symbol = '€') {
    return number_format($price, 2, ',', '.') . ' ' . $currency_symbol;
}

echo '# Menu' . PHP_EOL;
foreach ($restaurants as $restaurant) {
    if ($restaurant->isValidMenuForToday() === false) {
        continue;
    }

    // Menu
    $prices = [];
    $menu = '';
    foreach ($restaurant->getMenu() as $menuItem) {
        $prices[] = $menuItem->getPrice();
        $menu .= "* " . $menuItem->getLabel() . ' (' . formatPrice($menuItem->getPrice()) . ')' . PHP_EOL;
    }

    // Price median
//    $price_median = median(array_slice($prices, 0, 4));
    $price_median = median($prices);

    // Headline
    $headline = '## [' . $restaurant->getName() . '](' . $restaurant->getHomepage() . ') (Median: ' . formatPrice($price_median) . ')' . PHP_EOL;

    echo $headline . $menu . PHP_EOL;
}

