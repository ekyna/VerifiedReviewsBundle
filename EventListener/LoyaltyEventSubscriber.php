<?php

declare(strict_types=1);

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
    private OrderRepositoryInterface $orderRepository;
    private Features                 $features;
    private LoyaltyUpdater           $updater;

    public function __construct(OrderRepositoryInterface $orderRepository, Features $features, LoyaltyUpdater $updater)
    {
        $this->orderRepository = $orderRepository;
        $this->features = $features;
        $this->updater = $updater;
    }

    public function onReviewInsert(ResourceEventInterface $event): void
    {
        $review = $this->getReviewFromEvent($event);

        if (null === $customer = $this->findReviewCustomer($review)) {
            return;
        }

        if (!$customer->getCustomerGroup()->isLoyalty()) {
            return;
        }

        $points = (int)$this->features->getConfig(Features::LOYALTY . '.credit.review');

        if (0 >= $points) {
            return;
        }

        $this->updater->add($customer, $points, 'Review #' . substr($review->getReviewId(), 0, 8));
    }

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

    private function getReviewFromEvent(ResourceEventInterface $event): ReviewInterface
    {
        $resource = $event->getResource();

        if (!$resource instanceof ReviewInterface) {
            throw new InvalidArgumentException('Expected instance of ' . ReviewInterface::class);
        }

        return $resource;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ReviewEvents::INSERT => ['onReviewInsert', 0],
        ];
    }
}
