<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Twig;

use Ekyna\Bundle\VerifiedReviewsBundle\Service\Renderer\ReviewRenderer;

/**
 * Class ReviewExtension
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Twig
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ReviewExtension extends \Twig_Extension
{
    /**
     * @var ReviewRenderer
     */
    private $renderer;


    /**
     * Constructor.
     *
     * @param ReviewRenderer $renderer
     */
    public function __construct(ReviewRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @inheritdoc
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'verified_reviews',
                [$this->renderer, 'renderReviews'],
                ['is_safe' => ['html']]
            ),
            new \Twig_SimpleFilter(
                'verified_reviews_product',
                [$this->renderer, 'renderProduct'],
                ['is_safe' => ['html']]
            ),
        ];
    }
}
