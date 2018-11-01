<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Service\Serializer;

use Ekyna\Bundle\VerifiedReviewsBundle\Model\ReviewInterface;
use Ekyna\Component\Commerce\Common\Util\Formatter;
use Ekyna\Component\Resource\Serializer\AbstractResourceNormalizer;

/**
 * Class ReviewNormalizer
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Service\Serializer
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ReviewNormalizer extends AbstractResourceNormalizer
{
    /**
     * @var Formatter
     */
    private $formatter;


    /**
     * Constructor.
     *
     * @param Formatter $formatter
     */
    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @inheritdoc
     */
    public function normalize($resource, $format = null, array $context = [])
    {
        /** @var ReviewInterface $resource */

        if (in_array('Front', $context['groups'], true)) {
            $comments = [];
            foreach ($resource->getComments() as $comment) {
                $comments[] = [
                    'date'     => $this->formatter->date($resource->getDate()),
                    'customer' => $comment->isCustomer(),
                    'message'  => $comment->getMessage(),
                ];
            }

            $name = mb_convert_case($resource->getFirstName() . ' ' . $resource->getLastName(), MB_CASE_TITLE, 'UTF-8');

            return [
                'id'       => $resource->getId(),
                'name'     => $name,
                'date'     => $this->formatter->date($resource->getDate()),
                'rate'     => $resource->getRate(),
                'content'  => $resource->getContent(),
                'comments' => $comments,
            ];
        }

        return parent::normalize($resource, $format, $context);
    }

    /**
     * @inheritdoc
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        throw new \Exception('Not yet implemented');
    }

    /**
     * @inheritdoc
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof ReviewInterface;
    }

    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return class_exists($type) && is_subclass_of($type, ReviewInterface::class);
    }
}
