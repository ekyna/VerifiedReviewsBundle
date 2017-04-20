<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\Model;

use DateTimeInterface;
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
    public function getProduct(): ?Product;

    public function setProduct(?Product $product): ReviewInterface;

    public function getReviewId(): ?string;

    public function setReviewId(?string $id): ReviewInterface;

    public function getEmail(): ?string;

    public function setEmail(?string $email): ReviewInterface;

    public function getLastName(): ?string;

    public function setLastName(?string $name): ReviewInterface;

    public function getFirstName(): ?string;

    public function setFirstName(?string $name): ReviewInterface;

    public function getDate(): ?DateTimeInterface;

    public function setDate(?DateTimeInterface $date): ReviewInterface;

    public function getContent(): ?string;

    public function setContent(?string $content): ReviewInterface;

    public function getRate(): ?int;

    public function setRate(?int $rate): ReviewInterface;

    public function getOrderNumber(): ?string;

    public function setOrderNumber(?string $number): ReviewInterface;

    /**
     * @return Collection<Comment>
     */
    public function getComments(): Collection;

    public function addComment(Comment $comment): ReviewInterface;

    public function removeComment(Comment $comment): ReviewInterface;
}
