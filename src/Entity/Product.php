<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $forumUrl;



    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="string", options={"default" : "RUB"})
     */
    private $currency;

    const DURATIONS_DAY = 0;
    const DURATIONS_WEEK = 1;
    const DURATIONS_MONTH = 2;


    /**
     * @ORM\Column(type="integer")
     */
    private $durations = self::DURATIONS_DAY;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     */
    private $category = null;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Key", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $keys;

    /**
     * @param $keys
     */
    public function __construct() {
        $this->keys = new ArrayCollection();
    }

    /**
     * @return Key|ArrayCollection|null
     */
    public function getKeys(): ArrayCollection|Key|null {
        return $this->keys;
    }



    /**
     * @return mixed
     */
    public function getForumUrl() {
        return $this->forumUrl;
    }

    /**
     * @param mixed $forumUrl
     */
    public function setForumUrl($forumUrl): void {
        $this->forumUrl = $forumUrl;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void {
        $this->description = $description;
    }

    public function getDurationTime (){
        switch ($this->durations) {
            case 1:
                return '1 day';
            case 2:
                return '1 week';
            case 3:
                return '1 month';
            default:
                return '1 day';
        }
    }

    /**
     * @return mixed
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency): void {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;

    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param $name
     * @return string
     */
    public function setName(string $name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @param $price
     * @return int
     */
    public function setPrice($price) {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getDurations() {
        return $this->durations;
    }

    /**
     * @param $durations
     * @return int
     */
    public function setDurations($durations) {
        $this->durations = $durations;
    }

    public function __toString() {
        return $this->name;
    }

}
