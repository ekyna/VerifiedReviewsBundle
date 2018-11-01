<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle;

use Ekyna\Bundle\ResourceBundle\AbstractBundle;

/**
 * Class EkynaVerifiedReviewsBundle
 * @package Ekyna\Bundle\VerifiedReviewsBundle
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class EkynaVerifiedReviewsBundle extends AbstractBundle
{
    /**
     * @inheritdoc
     */
    protected function getModelInterfaces()
    {
        return [
            Model\ReviewInterface::class => 'ekyna_verified_reviews.review.class',
        ];
    }
}
