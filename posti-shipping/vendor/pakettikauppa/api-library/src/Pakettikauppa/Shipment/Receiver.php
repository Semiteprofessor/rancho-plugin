<?php

namespace Pakettikauppa\Shipment;

/**
 * Class Receiver
 * @package Pakettikauppa\Shipment
 */
class Receiver
{
    /**
     * @var string
     */
    public $name1;
    /**
     * @var string
     */
    public $name2;
    /**
     * @var string
     */
    public $addr1;
    /**
     * @var string
     */
    public $addr2;
    /**
     * @var string
     */
    public $addr3;
    /**
     * @var string
     */
    public $postcode;
    /**
     * @var string
     */
    public $city;
    /**
     * @var string
     */
    public $country;
    /**
     * @var string
     */
    public $phone;
    /**
     * @var string
     */
    public $vatcode;
    /**
     * @var string
     */
    public $email;

    /**
     * @return string
     */
    public function getName1()
    {
        return $this->name1;
    }

    /**
     * @param string $name1
     */
    public function setName1($name1)
    {
        $this->name1 = trim($name1);
    }

    /**
     * @return string
     */
    public function getName2()
    {
        return $this->name2;
    }

    /**
     * @param string $name2
     */
    public function setName2($name2)
    {
        $this->name2 = trim($name2);
    }

    /**
     * @return string
     */
    public function getAddr1()
    {
        return $this->addr1;
    }

    /**
     * @param string $addr1
     */
    public function setAddr1($addr1)
    {
        $this->addr1 = trim($addr1);
    }

    /**
     * @return string
     */
    public function getAddr2()
    {
        return $this->addr2;
    }

    /**
     * @param string $addr2
     */
    public function setAddr2($addr2)
    {
        $this->addr2 = trim($addr2);
    }

    /**
     * @return string
     */
    public function getAddr3()
    {
        return $this->addr3;
    }

    /**
     * @param string $addr3
     */
    public function setAddr3($addr3)
    {
        $this->addr3 = trim($addr3);
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = trim($postcode);
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = trim($city);
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = trim($country);
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = trim($phone);
    }

    /**
     * @return string
     */
    public function getVatcode()
    {
        return $this->vatcode;
    }

    /**
     * @param string $vatcode
     */
    public function setVatcode($vatcode)
    {
        $this->vatcode = trim($vatcode);
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = trim($email);
    }


}