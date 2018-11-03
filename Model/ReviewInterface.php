<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Model;

use Ekyna\Bundle\VerifiedReviewsBundle\Entity\Comment;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\ProductReview;
use Ekyna\Component\Resource\Model\ResourceInterface;

/**
 * Interface ReviewsInterface
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Model
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ReviewInterface extends ResourceInterface
{
    /**
     * Returns the product reviews.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection|ProductReview[]
     */
    public function getProductReviews();

    /**
     * Adds the product review.
     *
     * @param ProductReview $productReview
     *
     * @return $this|ReviewInterface
     */
    public function addProductReview(ProductReview $productReview);

    /**
     * Removes the product review.
     *
     * @param ProductReview $productReview
     *
     * @return $this|ReviewInterface
     */
    public function removeProductReview(ProductReview $productReview);

    /**
     * Returns the reviewId.
     *
     * @return string
     */
    public function getReviewId();

    /**
     * Sets the review id.
     *
     * @param string $id
     *
     * @return $this|ReviewInterface
     */
    public function setReviewId(string $id);

    /**
     * Returns the email.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Sets the email.
     *
     * @param string $email
     *
     * @return $this|ReviewInterface
     */
    public function setEmail(string $email = null);

    /**
     * Returns the last name.
     *
     * @return string
     */
    public function getLastName();

    /**
     * Sets the last name.
     *
     * @param string $name
     *
     * @return $this|ReviewInterface
     */
    public function setLastName(string $name = null);

    /**
     * Returns the first name.
     *
     * @return string
     */
    public function getFirstName();

    /**
     * Sets the first name.
     *
     * @param string $name
     *
     * @return $this|ReviewInterface
     */
    public function setFirstName(string $name = null);

    /**
     * Returns the date.
     *
     * @return \DateTime
     */
    public function getDate();

    /**
     * Sets the date.
     *
     * @param \DateTime $date
     *
     * @return $this|ReviewInterface
     */
    public function setDate(\DateTime $date);

    /**
     * Returns the content.
     *
     * @return string
     */
    public function getContent();

    /**
     * Sets the content.
     *
     * @param string $content
     *
     * @return $this|ReviewInterface
     */
    public function setContent(string $content);

    /**
     * Returns the rate.
     *
     * @return int
     */
    public function getRate();

    /**
     * Sets the rate.
     *
     * @param int $rate
     *
     * @return $this|ReviewInterface
     */
    public function setRate(int $rate);

    /**
     * Returns the order number.
     *
     * @return string
     */
    public function getOrderNumber();

    /**
     * Sets the order number.
     *
     * @param string $number
     *
     * @return $this|ReviewInterface
     */
    public function setOrderNumber(string $number);

    /**
     * Returns the comments.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection|Comment[]
     */
    public function getComments();

    /**
     * Adds the comment.
     *
     * @param Comment $comment
     *
     * @return $this|ReviewInterface
     */
    public function addComment(Comment $comment);

    /**
     * Removes the comment.
     *
     * @param Comment $comment
     *
     * @return $this|ReviewInterface
     */
    public function removeComment(Comment $comment);
}
