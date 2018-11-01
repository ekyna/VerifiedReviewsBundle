<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Ekyna\Bundle\ProductBundle\Model\ProductInterface;
use Ekyna\Bundle\ProductBundle\Repository\ProductRepositoryInterface;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\Product;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class ProductUpdater
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Service
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ProductUpdater
{
    const URL = 'https://cl.avis-verifies.com/fr/cache/%s/AWS/PRODUCT_API/AVERAGE/all_products.json';

    /**
     * @var ProductRepositoryInterface
     */
    private $productProductRepository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var string
     */
    private $websiteId;


    /**
     * Constructor.
     *
     * @param ProductRepositoryInterface $productProductRepository
     * @param EntityManagerInterface     $manager
     * @param string                     $websiteId
     */
    public function __construct(
        ProductRepositoryInterface $productProductRepository,
        EntityManagerInterface $manager,
        string $websiteId
    ) {
        $this->productProductRepository = $productProductRepository;
        $this->manager = $manager;
        $this->websiteId = $websiteId;
    }

    /**
     * Updates the review product list.
     *
     * @return bool Whether it succeed.
     */
    public function updateProducts()
    {
        if (empty($this->websiteId)) {
            return false;
        }

        $client = new Client();

        $path = implode('/', str_split(substr($this->websiteId, 0, 3))) . '/' . $this->websiteId;

        try {
            $res = $client->request('GET', sprintf(static::URL, $path));
        } catch (GuzzleException $e) {
            return false;
        }

        // Abort if request did not succeed
        if (!in_array($res->getStatusCode(), [200, 304])) {
            return false;
        }

        $data = json_decode($res->getBody(), true);

        if (empty($data)) {
            return true;
        }

        $reviewProductRepository = $this->manager->getRepository(Product::class);

        $count = 0;
        foreach ($data as $datum) {
            /** @var ProductInterface $productProduct */
            $productProduct = $this->productProductRepository->findOneBy([
                'reference' => $datum['id_product'],
            ]);
            if (!$productProduct) {
                continue;
            }

            /** @var Product $reviewProduct */
            $reviewProduct = $reviewProductRepository->findOneBy([
                'product' => $productProduct,
            ]);
            if (!$reviewProduct) {
                $reviewProduct = new Product();
                $reviewProduct->setProduct($productProduct);
            }

            $reviewProduct
                ->setRate($datum['rate'])
                ->setNbReviews($datum['nb_reviews']);

            $this->manager->persist($reviewProduct);

            $count++;

            if ($count % 20 === 0) {
                $this->manager->flush();
            }
        }

        if ($count % 20 != 0) {
            $this->manager->flush();
        }

        return true;
    }
}
