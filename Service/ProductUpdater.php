<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Ekyna\Bundle\ProductBundle\Model\ProductInterface;
use Ekyna\Bundle\ProductBundle\Repository\ProductRepositoryInterface;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\Product;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    protected $productProductRepository;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @var string
     */
    protected $websiteId;


    /**
     * Constructor.
     *
     * @param ProductRepositoryInterface $productProductRepository
     * @param ValidatorInterface         $validator
     * @param EntityManagerInterface     $manager
     * @param string                     $websiteId
     */
    public function __construct(
        ProductRepositoryInterface $productProductRepository,
        ValidatorInterface $validator,
        EntityManagerInterface $manager,
        string $websiteId = null
    ) {
        $this->productProductRepository = $productProductRepository;
        $this->validator = $validator;
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
            $res = $client->get(sprintf(static::URL, $path));
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (GuzzleException $e) {
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

        /** @var \Ekyna\Bundle\VerifiedReviewsBundle\Repository\ProductRepository $reviewProductRepository */
        $reviewProductRepository = $this->manager->getRepository(Product::class);

        $count = 0;
        foreach ($data as $datum) {
            if (!$productProduct = $this->findProduct(urldecode($datum['id_product']))) {
                continue;
            }

            $reviewProduct = $reviewProductRepository->findOneByProduct($productProduct);
            if (!$reviewProduct) {
                $reviewProduct = new Product();
                $reviewProduct->setProduct($productProduct);
            }

            if (!$this->updateProduct($reviewProduct, $datum)) {
                continue;
            }

            if (0 < $this->validator->validate($reviewProduct)->count()) {
                continue;
            }

            $this->manager->persist($reviewProduct);

            $count++;

            if ($count % 20 === 0) {
                $this->manager->flush();
            }
        }

        if ($count % 20 != 0) {
            $this->manager->flush();
        }

        $this->manager->clear();

        return true;
    }

    /**
     * Updates the review product.
     *
     * @param Product $product
     * @param array   $data
     *
     * @return bool Whether the review product as been updated.
     */
    protected function updateProduct(Product $product, array $data)
    {
        $changed = false;

        if ($product->getRate() != $data['rate']) {
            $product->setRate($data['rate']);
            $changed = true;
        }

        if ($product->getNbReviews() != $data['nb_reviews']) {
            $product->setNbReviews($data['nb_reviews']);
            $changed = true;
        }

        return $changed;
    }

    /**
     * Finds the product by verified reviews reference.
     *
     * @param string $idProduct
     *
     * @return ProductInterface|null
     */
    protected function findProduct($idProduct)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->productProductRepository->findOneBy([
            'reference' => $idProduct,
        ]);
    }
}
