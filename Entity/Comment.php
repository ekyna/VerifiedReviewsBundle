<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\Entity;

use DateTimeInterface;

/**
 * Class Comment
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Comment
{
    private ?int               $id       = null;
    private ?Review            $review   = null;
    private ?DateTimeInterface $date     = null;
    private bool               $customer = true;
    private ?string            $message  = null; // comment

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReview(): ?Review
    {
        return $this->review;
    }

    public function setReview(?Review $review): Comment
    {
        if ($this->review === $review) {
            return $this;
        }

        if ($this->review) {
            $this->review->removeComment($this);
        }

        $this->review = $review;

        if ($this->review) {
            $this->review->addComment($this);
        }

        return $this;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?DateTimeInterface $date): Comment
    {
        $this->date = $date;

        return $this;
    }

    public function isCustomer(): bool
    {
        return $this->customer;
    }

    public function setCustomer(bool $customer): Comment
    {
        $this->customer = $customer;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): Comment
    {
        $this->message = $message;

        return $this;
    }
}
