<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Event;

/**
 * Class ReviewEvents
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Event
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ReviewEvents
{
    const INSERT = 'ekyna_verified_reviews.review.insert';
    const UPDATE = 'ekyna_verified_reviews.review.update';
    const DELETE = 'ekyna_verified_reviews.review.delete';


    /**
     * Disabled constructor.
     */
    private function __construct()
    {
    }
}
