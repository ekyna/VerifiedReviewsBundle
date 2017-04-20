<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\Controller;

use Ekyna\Bundle\ProductBundle\Model\ProductInterface;
use Ekyna\Bundle\ProductBundle\Repository\ProductRepositoryInterface;
use Ekyna\Bundle\VerifiedReviewsBundle\Service\Renderer\ReviewRenderer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ApiController
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Controller
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ApiController
{
    private ProductRepositoryInterface $productRepository;
    private ReviewRenderer $renderer;

    public function __construct(ProductRepositoryInterface $productRepository, ReviewRenderer $renderer)
    {
        $this->productRepository = $productRepository;
        $this->renderer = $renderer;
    }

    /**
     * Reviews actions.
     */
    public function reviews(Request $request): Response
    {
        $id = $request->attributes->getInt('productId');

        /** @var ProductInterface $product */
        if (null === $product = $this->productRepository->find($id)) {
            throw new NotFoundHttpException('Product not found.');
        }

        $page = $request->attributes->getInt('page');

        $reviews = $this->renderer->fetchReviews($product, $page);

        return new JsonResponse($reviews);
    }
}
