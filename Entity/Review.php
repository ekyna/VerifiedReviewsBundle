<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @var ArrayCollection|ProductReview[]
     */
    protected $productReviews;

    /**
     * (review_id)
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
     * @var ArrayCollection|Comment[]
     */
    protected $comments;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->productReviews = new ArrayCollection();
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
    public function getProductReviews()
    {
        return $this->productReviews;
    }

    /**
     * @inheritdoc
     */
    public function addProductReview(ProductReview $productReview)
    {
        if (!$this->productReviews->contains($productReview)) {
            $this->productReviews->add($productReview);
            $productReview->setReview($this);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeProductReview(ProductReview $productReview)
    {
        if ($this->productReviews->contains($productReview)) {
            $this->productReviews->removeElement($productReview);
            $productReview->setReview(null);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getReviewId()
    {
        return $this->reviewId;
    }

    /**
     * @inheritdoc
     */
    public function setReviewId(string $id)
    {
        $this->reviewId = $id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function setEmail(string $email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @inheritdoc
     */
    public function setLastName(string $name = null)
    {
        $this->lastName = $name;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @inheritdoc
     */
    public function setFirstName(string $name = null)
    {
        $this->firstName = $name;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @inheritdoc
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @inheritdoc
     */
    public function setContent(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @inheritdoc
     */
    public function setRate(int $rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @inheritdoc
     */
    public function setOrderNumber(string $number)
    {
        $this->orderNumber = $number;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @inheritdoc
     */
    public function addComment(Comment $comment)
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
    public function removeComment(Comment $comment)
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            $comment->setReview(null);
        }

        return $this;
    }
}
