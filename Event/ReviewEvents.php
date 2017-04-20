<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\Event;

/**
 * Class ReviewEvents
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Event
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class ReviewEvents
{
    public const INSERT = 'ekyna_verified_reviews.review.insert';
    public const UPDATE = 'ekyna_verified_reviews.review.update';
    public const DELETE = 'ekyna_verified_reviews.review.delete';

    /**
     * Disabled constructor.
     */
    private function __construct()
    {
    }
}
