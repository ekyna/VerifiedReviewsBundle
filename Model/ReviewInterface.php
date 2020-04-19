<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Model;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\Comment;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\Product;
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
     * @return Product|null
     */
    public function getProduct(): ?Product;

    /**
     * @param Product $product
     *
     * @return $this|ReviewInterface
     */
    public function setProduct(Product $product): ReviewInterface;

    /**
     * Returns the reviewId.
     *
     * @return string|null
     */
    public function getReviewId(): ?string;

    /**
     * Sets the review id.
     *
     * @param string $id
     *
     * @return $this|ReviewInterface
     */
    public function setReviewId(string $id): ReviewInterface;

    /**
     * Returns the email.
     *
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * Sets the email.
     *
     * @param string $email
     *
     * @return $this|ReviewInterface
     */
    public function setEmail(string $email = null): ReviewInterface;

    /**
     * Returns the last name.
     *
     * @return string|null
     */
    public function getLastName(): ?string;

    /**
     * Sets the last name.
     *
     * @param string $name
     *
     * @return $this|ReviewInterface
     */
    public function setLastName(string $name = null): ReviewInterface;

    /**
     * Returns the first name.
     *
     * @return string|null
     */
    public function getFirstName(): ?string;

    /**
     * Sets the first name.
     *
     * @param string $name
     *
     * @return $this|ReviewInterface
     */
    public function setFirstName(string $name = null): ReviewInterface;

    /**
     * Returns the date.
     *
     * @return DateTime|null
     */
    public function getDate(): ?DateTime;

    /**
     * Sets the date.
     *
     * @param DateTime $date
     *
     * @return $this|ReviewInterface
     */
    public function setDate(DateTime $date): ReviewInterface;

    /**
     * Returns the content.
     *
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * Sets the content.
     *
     * @param string $content
     *
     * @return $this|ReviewInterface
     */
    public function setContent(string $content): ReviewInterface;

    /**
     * Returns the rate.
     *
     * @return int|null
     */
    public function getRate(): ?int;

    /**
     * Sets the rate.
     *
     * @param int $rate
     *
     * @return $this|ReviewInterface
     */
    public function setRate(int $rate): ReviewInterface;

    /**
     * Returns the order number.
     *
     * @return string|null
     */
    public function getOrderNumber(): ?string;

    /**
     * Sets the order number.
     *
     * @param string $number
     *
     * @return $this|ReviewInterface
     */
    public function setOrderNumber(string $number): ReviewInterface;

    /**
     * Returns the comments.
     *
     * @return Collection|Comment[]
     */
    public function getComments(): Collection;

    /**
     * Adds the comment.
     *
     * @param Comment $comment
     *
     * @return $this|ReviewInterface
     */
    public function addComment(Comment $comment): ReviewInterface;

    /**
     * Removes the comment.
     *
     * @param Comment $comment
     *
     * @return $this|ReviewInterface
     */
    public function removeComment(Comment $comment): ReviewInterface;
}
