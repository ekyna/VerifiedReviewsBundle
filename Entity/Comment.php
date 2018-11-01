<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Entity;

/**
 * Class Comment
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Comment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Review
     */
    private $review;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var bool
     */
    private $customer;

    /**
     * (comment)
     * @var string
     */
    private $message;


    /**
     * Returns the id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns the review.
     *
     * @return Review
     */
    public function getReview(): ?Review
    {
        return $this->review;
    }

    /**
     * Sets the review.
     *
     * @param Review $review
     *
     * @return Comment
     */
    public function setReview(Review $review = null): Comment
    {
        if ($this->review !== $review) {
            if ($this->review) {
                $this->review->removeComment($this);
            }

            $this->review = $review;

            if ($review) {
                $review->addComment($this);
            }
        }

        return $this;
    }

    /**
     * Returns the date.
     *
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * Sets the date.
     *
     * @param \DateTime $date
     *
     * @return Comment
     */
    public function setDate(\DateTime $date): Comment
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Returns the customer.
     *
     * @return bool
     */
    public function isCustomer()
    {
        return (bool)$this->customer;
    }

    /**
     * Sets the customer.
     *
     * @param bool $customer
     *
     * @return Comment
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Returns the message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Sets the message.
     *
     * @param string $message
     *
     * @return Comment
     */
    public function setMessage(string $message): Comment
    {
        $this->message = $message;

        return $this;
    }
}
