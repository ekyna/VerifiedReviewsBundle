<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ekyna\Bundle\ProductBundle\Model\ProductInterface;

/**
 * Class Product
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Product
{
    private ?int               $id        = null;
    private ?ProductInterface  $product   = null;
    private ?int               $nbReviews = null;
    private ?float             $rate      = null;
    private ?DateTimeInterface $fetchedAt = null;
    /** @var Collection<Review> */
    private Collection $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): Product
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): Product
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            $review->setProduct(null);
        }

        return $this;
    }

    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    public function setProduct(?ProductInterface $product): Product
    {
        $this->product = $product;

        return $this;
    }

    public function getNbReviews(): ?int
    {
        return $this->nbReviews;
    }

    public function setNbReviews(?int $count): Product
    {
        $this->nbReviews = $count;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): Product
    {
        $this->rate = $rate;

        return $this;
    }

    public function getFetchedAt(): ?DateTimeInterface
    {
        return $this->fetchedAt;
    }

    public function setFetchedAt(?DateTimeInterface $date): Product
    {
        $this->fetchedAt = $date;

        return $this;
    }
}
