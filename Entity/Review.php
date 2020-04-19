<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Entity;

use DateTime;
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
    /**
     * @var int
     */
    protected $id;

    /**
     * @var Product
     */
    protected $product;

    /**
     * (product_review_id)
     * @var string
     */
    protected $reviewId;

    /**
     * @var string
     */
    protected $email;

    /**
     * (lastname)
     * @var string
     */
    protected $lastName;

    /**
     * (firstname)
     * @var string
     */
    protected $firstName;

    /**
     * (review_date)
     * @var \DateTime
     */
    protected $date;

    /**
     * (review)
     * @var string
     */
    protected $content;

    /**
     * @var int
     */
    protected $rate;

    /**
     * (order_ref)
     * @var string
     */
    protected $orderNumber;

    /**
     * @var Collection|Comment[]
     */
    protected $comments;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     *
     * @return $this
     */
    public function setProduct(Product $product): ReviewInterface
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getReviewId(): ?string
    {
        return $this->reviewId;
    }

    /**
     * @inheritdoc
     */
    public function setReviewId(string $id): ReviewInterface
    {
        $this->reviewId = $id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function setEmail(string $email = null): ReviewInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @inheritdoc
     */
    public function setLastName(string $name = null): ReviewInterface
    {
        $this->lastName = $name;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @inheritdoc
     */
    public function setFirstName(string $name = null): ReviewInterface
    {
        $this->firstName = $name;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @inheritdoc
     */
    public function setDate(DateTime $date): ReviewInterface
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @inheritdoc
     */
    public function setContent(string $content): ReviewInterface
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRate(): ?int
    {
        return $this->rate;
    }

    /**
     * @inheritdoc
     */
    public function setRate(int $rate): ReviewInterface
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    /**
     * @inheritdoc
     */
    public function setOrderNumber(string $number): ReviewInterface
    {
        $this->orderNumber = $number;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @inheritdoc
     */
    public function addComment(Comment $comment): ReviewInterface
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setReview($this);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeComment(Comment $comment): ReviewInterface
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            $comment->setReview(null);
        }

        return $this;
    }
}
