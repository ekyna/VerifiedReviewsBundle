<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\Service\Renderer;

use Ekyna\Bundle\ProductBundle\Model\ProductInterface;
use Ekyna\Bundle\VerifiedReviewsBundle\Repository\ProductRepository;
use Ekyna\Bundle\VerifiedReviewsBundle\Repository\ReviewRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

use function array_replace;

/**
 * Class ReviewRenderer
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Service\Renderer
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ReviewRenderer
{
    private ProductRepository   $productRepository;
    private ReviewRepository    $reviewRepository;
    private NormalizerInterface $normalizer;
    private TranslatorInterface $translator;
    private Environment         $twig;
    private array               $config;

    public function __construct(
        ProductRepository   $productRepository,
        ReviewRepository    $reviewRepository,
        NormalizerInterface $normalizer,
        TranslatorInterface $translator,
        Environment         $twig,
        array               $config = []
    ) {
        $this->productRepository = $productRepository;
        $this->reviewRepository = $reviewRepository;
        $this->normalizer = $normalizer;
        $this->translator = $translator;
        $this->twig = $twig;

        $this->config = array_replace([
            'columns' => 2,
            'rows'    => 8,
            'width'   => 100,
        ], $config);
    }

    /**
     * Renders the product count and stars.
     */
    public function renderProduct(ProductInterface $subject, array $params = []): string
    {
        $product = $this->productRepository->findOneByProduct($subject);

        if (null === $product) {
            return '';
        }

        $params = array_replace([
            'tag'   => 'div',
            'class' => 'verified-reviews-product',
            'href'  => null,
        ], $params);

        if (!empty($params['href'])) {
            $params['tag'] = 'a';
        }

        $count = $this->translator->trans('count', ['{count}' => $product->getNbReviews()], 'EkynaVerifiedReviews');
        $rate = $this->translator->trans('review.rate', ['{rate}' => $product->getRate()], 'EkynaVerifiedReviews');

        $width = ($this->config['width'] / 5 * $product->getRate()) . 'px';

        $href = !empty($params['href']) ? ' href="' . $params['href'] . '"' : '';

        return '<' . $params['tag'] . $href . ' class="' . $params['class'] . '">' .
            $count .
            '<span class="verified-review-rate" title="' . $rate . '">' .
            '<i>' . $rate . '</i>' .
            '<i style="width: ' . $width . '"></i>' .
            '</span>' .
            '</' . $params['tag'] . '>';
    }

    /**
     * Renders the product reviews.
     */
    public function renderReviews(ProductInterface $subject): string
    {
        $product = $this->productRepository->findOneByProduct($subject);

        $reviews = $this->fetchReviews($subject);

        $translations = [
            'info' => $this->translator->trans('review.info', [], 'EkynaVerifiedReviews'),
            'anon' => $this->translator->trans('review.anon', [], 'EkynaVerifiedReviews'),
            'rate' => $this->translator->trans('review.rate', [], 'EkynaVerifiedReviews'),
        ];

        $config = array_replace($this->config, [
            'id'    => $subject->getId(),
            'trans' => $translations,
        ]);

        return $this->twig->render('@EkynaVerifiedReviews/reviews.html.twig', [
            'product' => $product,
            'config'  => $config,
            'reviews' => $reviews,
        ]);
    }

    /**
     * Fetches the reviews for the given page number.
     */
    public function fetchReviews(ProductInterface $product, int $page = 0): array
    {
        $perPage = $this->config['columns'] * $this->config['rows'];

        $reviews = $this->reviewRepository->findByProduct($product, $perPage, $page * $perPage);

        return $this->normalizer->normalize($reviews, 'json', ['groups' => ['Front']]);
    }
}
