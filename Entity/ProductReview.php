<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Entity;

/**
 * Class ProductReview
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ProductReview
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $productReviewId;

    /**
     * @var Product
     */
    private $product;

    /**
     * @var Review
     */
    private $review;


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
     * Sets the id.
     *
     * @param int $id
     *
     * @return ProductReview
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Returns the product review id.
     *
     * @return string
     */
    public function getProductReviewId()
    {
        return $this->productReviewId;
    }

    /**
     * Sets the product review id.
     *
     * @param string $id
     *
     * @return ProductReview
     */
    public function setProductReviewId($id)
    {
        $this->productReviewId = $id;

        return $this;
    }

    /**
     * Returns the product.
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Sets the product.
     *
     * @param Product $product
     *
     * @return ProductReview
     */
    public function setProduct(Product $product = null)
    {
        if ($this->product !== $product) {
            if ($this->product) {
                $this->product->removeProductReview($this);
            }

            $this->product = $product;

            if ($this->product) {
                $this->product->addProductReview($this);
            }
        }

        return $this;
    }

    /**
     * Returns the review.
     *
     * @return Review
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Sets the review.
     *
     * @param Review $review
     *
     * @return ProductReview
     */
    public function setReview(Review $review = null)
    {
        if ($this->review !== $review) {
            if ($this->review) {
                $this->review->removeProductReview($this);
            }

            $this->review = $review;

            if ($this->review) {
                $this->review->addProductReview($this);
            }
        }

        return $this;
    }
}