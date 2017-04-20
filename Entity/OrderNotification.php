<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\Entity;

use DateTimeInterface;
use Ekyna\Bundle\CommerceBundle\Model\OrderInterface;

/**
 * Class OrderNotification
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class OrderNotification
{
    private ?int $id = null;
    private ?OrderInterface $order = null;
    private ?DateTimeInterface $notifiedAt;
    private bool $succeed = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    public function setOrder(?OrderInterface $order): OrderNotification
    {
        $this->order = $order;

        return $this;
    }

    public function getNotifiedAt(): ?DateTimeInterface
    {
        return $this->notifiedAt;
    }

    public function setNotifiedAt(?DateTimeInterface $date): OrderNotification
    {
        $this->notifiedAt = $date;

        return $this;
    }

    public function isSucceed(): bool
    {
        return $this->succeed;
    }

    public function setSucceed(bool $succeed): OrderNotification
    {
        $this->succeed = $succeed;

        return $this;
    }
}
