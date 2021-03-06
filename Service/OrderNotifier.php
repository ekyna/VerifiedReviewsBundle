<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Service;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Ekyna\Bundle\CommerceBundle\Model\OrderInterface;
use Ekyna\Bundle\ProductBundle\Model\ProductInterface;
use Ekyna\Bundle\ProductBundle\Model\ProductReferenceTypes;
use Ekyna\Bundle\ProductBundle\Model\ProductTypes;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\OrderNotification;
use Ekyna\Component\Commerce\Order\Model\OrderItemInterface;
use Ekyna\Component\Commerce\Shipment\Model\ShipmentStates;
use Ekyna\Bundle\CommerceBundle\Service\Subject\SubjectHelperInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class OrderNotifier
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Service
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class OrderNotifier
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @var SubjectHelperInterface
     */
    protected $subjectHelper;

    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var string
     */
    protected $orderClass;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var \Doctrine\ORM\Query
     */
    protected $findOrdersQuery;


    /**
     * Constructor.
     *
     * @param EntityManagerInterface $manager
     * @param SubjectHelperInterface $subjectHelper
     * @param CacheManager           $cacheManager
     * @param \Swift_Mailer          $mailer
     * @param string                 $orderClass
     * @param array                  $config
     */
    public function __construct(
        EntityManagerInterface $manager,
        SubjectHelperInterface $subjectHelper,
        CacheManager $cacheManager,
        \Swift_Mailer $mailer,
        string $orderClass,
        array $config = []
    ) {
        $this->manager       = $manager;
        $this->subjectHelper = $subjectHelper;
        $this->cacheManager  = $cacheManager;
        $this->mailer        = $mailer;
        $this->orderClass    = $orderClass;

        $this->config = array_replace([
            'enable'       => false,
            'delay'        => 7,
            'website_id'   => null,
            'secret_key'   => null,
            'debug'        => true,
            'report_email' => null,
        ], $config);
    }

    /**
     * Sends orders notifications.
     *
     * @param OutputInterface $output
     */
    public function notify(OutputInterface $output)
    {
        if (!$this->checkConfig($output)) {
            return;
        }

        $report = '';
        $limit  = $this->config['limit'];

        while (null !== $order = $this->findNextOrder()) {
            $name = $order->getNumber();
            $output->write(sprintf(
                '- %s %s ',
                $name,
                str_pad('.', 32 - mb_strlen($name), '.', STR_PAD_LEFT)
            ));

            if ($succeed = $this->notifyOrder($order)) {
                $output->writeln('<info>success</info>');
            } else {
                $output->writeln('<error>failure</error>');
            }

            $report .= sprintf(
                "[%s] %s (%s) : %s\n",
                $order->getId(),
                $order->getNumber(),
                $order->getEmail(),
                $succeed ? 'success' : 'failure'
            );

            $notification = new OrderNotification();
            $notification
                ->setOrder($order)
                ->setNotifiedAt(new \DateTime())
                ->setSucceed($succeed);

            $this->manager->persist($notification);
            $this->manager->flush();

            unset($order);
            unset($notification);

            $this->manager->clear();

            $limit--;
            if ($limit == 0) {
                break;
            }
        }

        if (!empty($report) && !empty($this->config['report_email'])) {
            $message = \Swift_Message::newInstance();
            $message
                ->setFrom($this->config['report_email'])
                ->setTo($this->config['report_email'])
                ->setSubject('Verified review report.')
                ->setBody($report);

            $this->mailer->send($message);
        }
    }

    /**
     * Sends order notification.
     *
     * @param OrderInterface $order
     *
     * @return bool
     */
    protected function notifyOrder(OrderInterface $order)
    {
        // TODO Configurable (per country)
        $url = "http://www.avis-verifies.com/index.php";
        // $url = "http://www.verified-reviews.com/index.php";
        // $url = "http://www.recensioni-verificate.com/index.php";
        // $url = "http://www.opinioes-verificadas.com/index.php";
        // $url = "http://www.recensioni-verificate.com/index.php";
        // $url = "http://www.echte-bewertungen.com/index.php";
        // $url = "http://www.opiniones-verificadas.com/index.php";

        $acceptedAt = $order->getAcceptedAt()->format('Y-m-d H:i:s');
        if ($this->config['debug']) {
            $email = 'support@ekyna.com';
            $delay = '0';
        } else {
            $email = $order->getEmail();
            $delay = (string)$this->config['delay'];
        }

        $data = [
            'query'      => 'pushCommandeSHA1',     // Required
            'order_ref'  => $order->getNumber(),    // Required - Reference order
            'email'      => $email,                 // Required - Client email
            'lastname'   => $order->getLastName(),  // Required -  Client lastname
            'firstname'  => $order->getFirstName(), // Required -  Client firstname
            'order_date' => $acceptedAt,            // Required - Format YYYY-MM-JJ HH:MM:SS
            'delay'      => $delay,                 // 0=Immediately / ‘n’ days between 1 and 30 days
            'PRODUCTS'   => [],
            'sign'       => '',
        ];

        foreach ($order->getItems() as $item) {
            $this->buildProducts($item, $data['PRODUCTS']);
        }

        $data['sign'] = SHA1(
            $data['query'] .
            $data['order_ref'] .
            $data['email'] .
            $data['lastname'] .
            $data['firstname'] .
            $data['order_date'] .
            $data['delay'] .
            $this->config['secret_key']
        );

        $encrypted = http_build_query(
            [
                'idWebsite' => $this->config['website_id'],
                'message'   => json_encode($data),
            ]
        );

        $post = [
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $encrypted,
            ],
        ];

        $context = stream_context_create($post);

        $result = file_get_contents($url . '?action=act_api_notification_sha1&type=json2', false, $context);

        $result = json_decode($result, true);

        return $result['return'] == 1;
    }

    /**
     * Builds the product and its children data.
     *
     * @param OrderItemInterface $item
     * @param array              $list
     */
    protected function buildProducts(OrderItemInterface $item, array &$list)
    {
        if ($item->isPrivate()) {
            return;
        }

        $product = $this->subjectHelper->resolve($item, false);
        if ($product instanceof ProductInterface) {
            if ($product->getType() === ProductTypes::TYPE_VARIANT) {
                $product = $product->getParent();
            }

            if (!empty($data = $this->buildProduct($product))) {
                $list[] = $data;
            }
        }

        foreach ($item->getChildren() as $child) {
            $this->buildProducts($child, $list);
        }
    }

    /**
     * Builds the product data.
     *
     * @param ProductInterface $product
     *
     * @return array|null
     */
    protected function buildProduct(ProductInterface $product)
    {
        $data = [
            'id_product'   => $product->getReference(), // Required - Product Id
            'name_product' => $product->getFullTitle(), // Required - Product Name
        ];

        $url = $this->subjectHelper->generatePublicUrl($product, false);
        if ($url) {
            $data['url_product'] = $url;
        }

        /** @var \Ekyna\Bundle\MediaBundle\Model\MediaInterface $image */
        if ($image = $product->getImages(true, 1)->first()) {
            $data['url_product_image'] = $this->cacheManager->getBrowserPath($image->getPath(), 'media_front');
        }

        foreach ($product->getReferences() as $reference) {
            switch ($reference->getType()) {
                case ProductReferenceTypes::TYPE_EAN_13:
                    $data['GTIN_EAN'] = $reference->getCode();
                    break;

                case ProductReferenceTypes::TYPE_MANUFACTURER:
                    $data['MPN'] = $reference->getCode();
                    break;
            }
            // 'GTIN_UPC'
            // 'GTIN_EAN'
            // 'GTIN_JAN'
            // 'GTIN_ISBN'
            // 'MPN'
        }

        $data['sku']        = $product->getReference();
        $data['brand_name'] = $product->getBrand()->getTitle();

        return $data;
    }

    /**
     * Returns the next order to notify.
     *
     * @return OrderInterface
     */
    protected function findNextOrder()
    {
        if (!$this->findOrdersQuery) {
            $ex = new Expr();

            $qb = $this->manager
                ->createQueryBuilder()
                ->from(OrderNotification::class, 'on')
                ->select('on')
                ->andWhere($ex->eq('IDENTITY(on.order)', 'o.id'));

            $this->findOrdersQuery = $this->manager
                ->createQueryBuilder()
                ->from($this->orderClass, 'o')
                ->select('o')
                ->andWhere($ex->gte('o.acceptedAt', ':date'))
                ->andWhere($ex->eq('o.shipmentState', ':state'))
                ->andWhere($ex->not($ex->exists($qb->getDQL())))
                ->orderBy('o.acceptedAt', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->useQueryCache(true);
        }

        $date = (new \DateTime('-1 month'))->setTime(0, 0, 0, 0);

        return $this->findOrdersQuery
            ->setParameter('date', $date, Types::DATE_MUTABLE)
            ->setParameter('state', ShipmentStates::STATE_COMPLETED)
            ->getOneOrNullResult();
    }

    /**
     * Checks the configuration.
     *
     * @param OutputInterface $output
     *
     * @return bool
     */
    protected function checkConfig(OutputInterface $output)
    {
        if (!$this->config['enable']) {
            $output->writeln("<error>Order notification is disabled</error>");

            return false;
        }

        if (empty($this->config['website_id'])) {
            $output->writeln("<error>Website id is not configured</error>");

            return false;
        }

        if (empty($this->config['secret_key'])) {
            $output->writeln("<error>Secret key is not configured</error>");

            return false;
        }

        if ($this->config['debug']) {
            $output->writeln("<comment>Debug mode: notification won't be sent.\n</comment>");
        }

        return true;
    }
}
