<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\EventListener;

use Ekyna\Bundle\VerifiedReviewsBundle\Event\ReviewEvents;
use Ekyna\Bundle\VerifiedReviewsBundle\Model\ReviewInterface;
use Ekyna\Component\Commerce\Customer\Loyalty\LoyaltyUpdater;
use Ekyna\Component\Commerce\Customer\Model\CustomerInterface;
use Ekyna\Component\Commerce\Features;
use Ekyna\Component\Commerce\Order\Repository\OrderRepositoryInterface;
use Ekyna\Component\Resource\Event\ResourceEventInterface;
use Ekyna\Component\Resource\Exception\InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LoyaltyEventSubscriber
 * @package Ekyna\Bundle\VerifiedReviewsBundle\EventListener
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class LoyaltyEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var Features
     */
    private $features;

    /**
     * @var LoyaltyUpdater
     */
    private $updater;


    /**
     * Constructor.
     *
     * @param OrderRepositoryInterface $orderRepository
     * @param Features                 $features
     * @param LoyaltyUpdater           $updater
     */
    public function __construct(OrderRepositoryInterface $orderRepository, Features $features, LoyaltyUpdater $updater)
    {
        $this->orderRepository = $orderRepository;
        $this->features = $features;
        $this->updater = $updater;
    }

    /**
     * Review insert event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onReviewInsert(ResourceEventInterface $event): void
    {
        $review = $this->getReviewFromEvent($event);

        if (null === $customer = $this->findReviewCustomer($review)) {
            return;
        }

        if (!$customer->getCustomerGroup()->isLoyalty()) {
            return;
        }

        $points = (int)$this->features->getConfig(Features::LOYALTY)['credit']['review'];

        if (0 >= $points) {
            return;
        }

        $this->updater->add($customer, $points, 'Review #' . substr($review->getReviewId(), 0, 8));
    }

    /**
     * Finds the review customer.
     *
     * @param ReviewInterface $review
     *
     * @return CustomerInterface|null
     */
    private function findReviewCustomer(ReviewInterface $review): ?CustomerInterface
    {
        if (null === $number = $review->getOrderNumber()) {
            return null;
        }

        if (null === $order = $this->orderRepository->findOneByNumber($number)) {
            return null;
        }

        return $order->getCustomer();
    }

    /**
     * Returns the review from the event.
     *
     * @param ResourceEventInterface $event
     *
     * @return ReviewInterface
     */
    private function getReviewFromEvent(ResourceEventInterface $event): ReviewInterface
    {
        $resource = $event->getResource();

        if (!$resource instanceof ReviewInterface) {
            throw new InvalidArgumentException('Expected instance of ' . ReviewInterface::class);
        }

        return $resource;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            ReviewEvents::INSERT => [['onReviewInsert', 0]],
        ];
    }
}
