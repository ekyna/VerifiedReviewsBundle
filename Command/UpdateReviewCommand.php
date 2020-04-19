<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Command;

use Ekyna\Bundle\VerifiedReviewsBundle\Service\ReviewUpdater;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateReviewCommand
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Command
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class UpdateReviewCommand extends Command
{
    /**
     * @var ReviewUpdater
     */
    private $reviewUpdater;


    /**
     * Sets the review updater.
     *
     * @param ReviewUpdater $updater
     */
    public function setReviewUpdater(ReviewUpdater $updater)
    {
        $this->reviewUpdater = $updater;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('ekyna_verified_reviews:update:review')
            ->setDescription('Update the verified reviews.')
            ->addOption('full', 'f', InputOption::VALUE_NONE, 'Whether to update all reviews.');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('<comment>Updating reviews</comment> ... ');
        if ($this->reviewUpdater->updateReviews($input->getOption('full'))) {
            $output->write('<info>success</info>');
        } else {
            $output->write('<error>failure</error>');
        }
    }
}
