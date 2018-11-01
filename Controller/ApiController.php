<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Controller;

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
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ReviewRenderer
     */
    private $renderer;


    /**
     * Constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param ReviewRenderer             $renderer
     */
    public function __construct(ProductRepositoryInterface $productRepository, ReviewRenderer $renderer)
    {
        $this->productRepository = $productRepository;
        $this->renderer = $renderer;
    }

    /**
     * Reviews actions.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function reviews(Request $request)
    {
        $id = $request->attributes->get('productId');

        /** @var \Ekyna\Bundle\ProductBundle\Model\ProductInterface $product */
        if (!$product = $this->productRepository->find($id)) {
            throw new NotFoundHttpException("Product not found.");
        }

        $page = $request->attributes->get('page');

        $reviews = $this->renderer->fetchReviews($product, $page);

        return new JsonResponse($reviews);
    }
}
