<?php
namespace JK\Mittagstisch;

class MenuItem
{
    protected $label;
    protected $price;

    function __construct($label, $price)
    {
        $this->setLabel($label);
        $this->setPrice($price);
    }

    protected function sanitizeInput($input) {
        $string = str_replace(chr(194), '', $input);
        $string = str_replace(chr(160), '', $string);
        return trim($string);
//        return str_replace('�', '', $input);

        return $input;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $this->sanitizeInput($label);
    }

    /**
     * @param string|float $price
     */
    public function setPrice($price)
    {
        $this->price = floatval(str_replace(',', '.', $this->sanitizeInput($price)));
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    public function __toString()
    {
        return $this->getLabel() . ' ' . $this->getPrice();
    }
}
