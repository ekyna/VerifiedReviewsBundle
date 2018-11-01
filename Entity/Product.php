<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ekyna\Bundle\ProductBundle\Model\ProductInterface;

/**
 * Class Product
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Product
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var ArrayCollection|ProductReview[]
     */
    private $productReviews;

    /**
     * @var ProductInterface
     */
    private $product;

    /**
     * @var int
     */
    private $nbReviews;

    /**
     * @var float
     */
    private $rate;

    /**
     * @var \DateTime
     */
    private $fetchedAt;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->productReviews = new ArrayCollection();
    }

    /**
     * Returns the id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the product reviews.
     *
     * @return ArrayCollection|ProductReview[]
     */
    public function getProductReviews()
    {
        return $this->productReviews;
    }

    /**
     * Adds the product review.
     *
     * @param ProductReview $productReview
     *
     * @return Product
     */
    public function addProductReview(ProductReview $productReview)
    {
        if (!$this->productReviews->contains($productReview)) {
            $this->productReviews->add($productReview);
            $productReview->setProduct($this);
        }

        return $this;
    }

    /**
     * Removes the product review.
     *
     * @param ProductReview $productReview
     *
     * @return Product
     */
    public function removeProductReview(ProductReview $productReview)
    {
        if ($this->productReviews->contains($productReview)) {
            $this->productReviews->removeElement($productReview);
            $productReview->setProduct(null);
        }

        return $this;
    }

    /**
     * Returns the product.
     *
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Sets the product.
     *
     * @param ProductInterface $product
     *
     * @return Product
     */
    public function setProduct(ProductInterface $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Returns the number of reviews.
     *
     * @return int
     */
    public function getNbReviews()
    {
        return $this->nbReviews;
    }

    /**
     * Sets the number of reviews.
     *
     * @param int $count
     *
     * @return Product
     */
    public function setNbReviews($count)
    {
        $this->nbReviews = $count;

        return $this;
    }

    /**
     * Returns the average rate.
     *
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Sets the average rate.
     *
     * @param float $rate
     *
     * @return Product
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Returns the "fetched at" date.
     *
     * @return \DateTime
     */
    public function getFetchedAt()
    {
        return $this->fetchedAt;
    }

    /**
     * Sets the "fetched at" date.
     *
     * @param \DateTime $date
     *
     * @return Product
     */
    public function setFetchedAt(\DateTime $date = null)
    {
        $this->fetchedAt = $date;

        return $this;
    }
}