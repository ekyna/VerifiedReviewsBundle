<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Service\Renderer;

use Ekyna\Bundle\ProductBundle\Model\ProductInterface;
use Ekyna\Bundle\VerifiedReviewsBundle\Repository\ProductRepository;
use Ekyna\Bundle\VerifiedReviewsBundle\Repository\ReviewRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ReviewRenderer
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Service\Renderer
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ReviewRenderer
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ReviewRepository
     */
    private $reviewRepository;

    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var array
     */
    private $config;


    /**
     * Constructor.
     *
     * @param ProductRepository   $productRepository
     * @param ReviewRepository    $reviewRepository
     * @param NormalizerInterface $normalizer
     * @param TranslatorInterface $translator
     * @param EngineInterface     $templating
     * @param array               $config
     */
    public function __construct(
        ProductRepository $productRepository,
        ReviewRepository $reviewRepository,
        NormalizerInterface $normalizer,
        TranslatorInterface $translator,
        EngineInterface $templating,
        array $config = []
    ) {
        $this->productRepository = $productRepository;
        $this->reviewRepository = $reviewRepository;
        $this->normalizer = $normalizer;
        $this->translator = $translator;
        $this->templating = $templating;

        $this->config = array_replace([
            'columns' => 2,
            'rows'    => 8,
            'width'   => 100,
        ], $config);
    }

    /**
     * Renders the product reviews.
     *
     * @param ProductInterface $subject
     *
     * @return string
     */
    public function renderReviews(ProductInterface $subject)
    {
        $product = $this->productRepository->findOneByProduct($subject);

        $reviews = $this->fetchReviews($subject);

        $translations = [
            'info' => $this->translator->trans('ekyna_verified_reviews.review.info'),
            'rate' => $this->translator->trans('ekyna_verified_reviews.review.rate'),
        ];

        $config = array_replace($this->config, [
            'id'    => $subject->getId(),
            'trans' => $translations,
        ]);

        return $this->templating->render('@EkynaVerifiedReviews/reviews.html.twig', [
            'product' => $product,
            'config'  => $config,
            'reviews' => $reviews,
        ]);
    }

    /**
     * Fetches the reviws for the given page number.
     *
     * @param ProductInterface $product
     * @param int              $page
     *
     * @return array
     */
    public function fetchReviews(ProductInterface $product, int $page = 0)
    {
        $perPage = $this->config['columns'] * $this->config['rows'];

        $reviews = $this->reviewRepository->findByProduct($product, $perPage, $page * $perPage);

        return $this->normalizer->normalize($reviews, 'json', ['groups' => ['Front']]);
    }
}
