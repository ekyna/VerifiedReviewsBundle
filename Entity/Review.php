<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ekyna\Bundle\VerifiedReviewsBundle\Model\ReviewInterface;

/**
 * Class Review
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Review implements ReviewInterface
{
    protected ?int               $id          = null;
    protected ?Product           $product     = null;
    protected ?string            $reviewId    = null; // product_review_id
    protected ?string            $email       = null;
    protected ?string            $lastName    = null; // lastname
    protected ?string            $firstName   = null; // firstname
    protected ?DateTimeInterface $date        = null; // review_date
    protected ?string            $content     = null; // review
    protected ?int               $rate        = null;
    protected ?string            $orderNumber = null; // order_ref
    /** @var Collection<Comment> */
    protected Collection $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): ReviewInterface
    {
        $this->product = $product;

        return $this;
    }

    public function getReviewId(): ?string
    {
        return $this->reviewId;
    }

    public function setReviewId(?string $id): ReviewInterface
    {
        $this->reviewId = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): ReviewInterface
    {
        $this->email = $email;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $name): ReviewInterface
    {
        $this->lastName = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $name): ReviewInterface
    {
        $this->firstName = $name;

        return $this;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?DateTimeInterface $date): ReviewInterface
    {
        $this->date = $date;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): ReviewInterface
    {
        $this->content = $content;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(?int $rate): ReviewInterface
    {
        $this->rate = $rate;

        return $this;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(?string $number): ReviewInterface
    {
        $this->orderNumber = $number;

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): ReviewInterface
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setReview($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): ReviewInterface
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            $comment->setReview(null);
        }

        return $this;
    }
}
