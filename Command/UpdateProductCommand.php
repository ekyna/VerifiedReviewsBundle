<?php

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
    /**
     * @var ProductUpdater
     */
    private $productUpdater;


    /**
     * Sets the product updater.
     *
     * @param ProductUpdater $updater
     */
    public function setProductUpdater(ProductUpdater $updater)
    {
        $this->productUpdater = $updater;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('ekyna_verified_reviews:update:product')
            ->setDescription('Update the verified reviews products.');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('<comment>Updating product list</comment> ... ');
        if ($this->productUpdater->updateProducts()) {
            $output->write('<info>success</info>');
        } else {
            $output->write('<error>failure</error>');
        }
    }
}
