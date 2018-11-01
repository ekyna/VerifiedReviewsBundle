<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Command;

use Ekyna\Bundle\VerifiedReviewsBundle\Service\OrderNotifier;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class NotifyOrderCommand
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Command
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class NotifyOrderCommand extends Command
{
    /**
     * @var OrderNotifier
     */
    private $orderNotifier;

    /**
     * Sets the order notifier.
     *
     * @param OrderNotifier $notifier
     */
    public function setOrderNotifier(OrderNotifier $notifier)
    {
        $this->orderNotifier = $notifier;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('ekyna_verified_reviews:notify:order')
            ->setDescription('Notify orders to verified reviews API.');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment>Notifying orders</comment>\n");

        $this->orderNotifier->notify($output);
    }
}
