<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\Command;

use Ekyna\Bundle\VerifiedReviewsBundle\Service\ProductUpdater;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateProductCommand
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Command
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * Must be run once per day (from 3 am)
 */
class UpdateProductCommand extends Command
{
    protected static $defaultName = 'ekyna_verified_reviews:update:product';

    private ProductUpdater $productUpdater;

    public function setProductUpdater(ProductUpdater $updater)
    {
        $this->productUpdater = $updater;
    }

    protected function configure(): void
    {
        $this->setDescription('Update the verified reviews products.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write('<comment>Updating product list</comment> ... ');

        if ($this->productUpdater->updateProducts()) {
            $output->write('<info>success</info>');
        } else {
            $output->write('<error>failure</error>');
        }

        return Command::SUCCESS;
    }
}
