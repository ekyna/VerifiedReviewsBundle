<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Entity;

use Ekyna\Bundle\CommerceBundle\Model\OrderInterface;

/**
 * Class OrderNotification
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class OrderNotification
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var OrderInterface
     */
    private $order;

    /**
     * @var \DateTime
     */
    private $notifiedAt;

    /**
     * @var bool
     */
    private $succeed;


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
     * Returns the order.
     *
     * @return OrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Sets the order.
     *
     * @param OrderInterface $order
     *
     * @return OrderNotification
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Returns the "notified at" date.
     *
     * @return \DateTime
     */
    public function getNotifiedAt()
    {
        return $this->notifiedAt;
    }

    /**
     * Sets the "notified at" date.
     *
     * @param \DateTime $date
     *
     * @return OrderNotification
     */
    public function setNotifiedAt(\DateTime $date)
    {
        $this->notifiedAt = $date;

        return $this;
    }

    /**
     * Returns whether the notification succeed.
     *
     * @return bool
     */
    public function isSucceed()
    {
        return (bool)$this->succeed;
    }

    /**
     * Sets whether the notification succeed.
     *
     * @param bool $succeed
     *
     * @return OrderNotification
     */
    public function setSucceed(bool $succeed)
    {
        $this->succeed = $succeed;

        return $this;
    }
}