<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\Twig;

use Ekyna\Bundle\VerifiedReviewsBundle\Service\Renderer\ReviewRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class ReviewExtension
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Twig
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ReviewExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'verified_reviews',
                [ReviewRenderer::class, 'renderReviews'],
                ['is_safe' => ['html']]
            ),
            new TwigFilter(
                'verified_reviews_product',
                [ReviewRenderer::class, 'renderProduct'],
                ['is_safe' => ['html']]
            ),
        ];
    }
}
