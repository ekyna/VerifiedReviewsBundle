<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\Service\Serializer;

use Ekyna\Bundle\VerifiedReviewsBundle\Model\ReviewInterface;
use Ekyna\Component\Commerce\Common\Util\FormatterAwareTrait;
use Ekyna\Component\Commerce\Common\Util\FormatterFactory;
use Ekyna\Component\Resource\Bridge\Symfony\Serializer\ResourceNormalizer;
use Exception;

use function class_exists;
use function is_subclass_of;
use function mb_convert_case;
use function trim;

/**
 * Class ReviewNormalizer
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Service\Serializer
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ReviewNormalizer extends ResourceNormalizer
{
    use FormatterAwareTrait;

    public function __construct(FormatterFactory $formatterFactory)
    {
        $this->formatterFactory = $formatterFactory;
    }

    /**
     * @inheritdoc
     *
     * @param ReviewInterface $object
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $formatter = $this->getFormatter();

        if (self::contextHasGroup(['Default', 'Front', 'Review'], $context)) {
            $comments = [];
            foreach ($object->getComments() as $comment) {
                $comments[] = [
                    'date'     => $formatter->date($object->getDate()),
                    'customer' => $comment->isCustomer(),
                    'message'  => $comment->getMessage(),
                ];
            }

            $name = mb_convert_case(trim($object->getFirstName() . ' ' . $object->getLastName()), MB_CASE_TITLE, 'UTF-8');

            return [
                'id'       => $object->getId(),
                'name'     => $name,
                'date'     => $formatter->date($object->getDate()),
                'rate'     => $object->getRate(),
                'content'  => $object->getContent(),
                'comments' => $comments,
            ];
        }

        return parent::normalize($object, $format, $context);
    }

    /**
     * @inheritdoc
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        throw new Exception('Not yet implemented');
    }

    /**
     * @inheritdoc
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof ReviewInterface;
    }

    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return class_exists($type) && is_subclass_of($type, ReviewInterface::class);
    }
}
