<?php

declare(strict_types=1);

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
    protected static $defaultName = 'ekyna_verified_reviews:notify:order';

    private OrderNotifier $orderNotifier;

    public function setOrderNotifier(OrderNotifier $notifier)
    {
        $this->orderNotifier = $notifier;
    }

    protected function configure(): void
    {
        $this->setDescription('Notify orders to verified reviews API.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<comment>Notifying orders</comment>\n');

        $this->orderNotifier->notify($output);

        return Command::SUCCESS;
    }
}
