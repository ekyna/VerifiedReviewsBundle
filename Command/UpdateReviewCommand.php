<?php

declare(strict_types=1);

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
    protected static $defaultName = 'ekyna_verified_reviews:update:review';

    private ReviewUpdater $reviewUpdater;

    public function setReviewUpdater(ReviewUpdater $updater)
    {
        $this->reviewUpdater = $updater;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Update the verified reviews.')
            ->addOption('full', 'f', InputOption::VALUE_NONE, 'Whether to update all reviews.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write('<comment>Updating reviews</comment> ... ');

        if ($this->reviewUpdater->updateReviews($input->getOption('full'))) {
            $output->write('<info>success</info>');
        } else {
            $output->write('<error>failure</error>');
        }

        return Command::SUCCESS;
    }
}
