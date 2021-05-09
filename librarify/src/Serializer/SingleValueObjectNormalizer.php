<?php

namespace App\Serializer;

use App\Model\ValueObjectInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class SingleValueObjectNormalizer implements ContextAwareNormalizerInterface
{

    private ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($valueObject, string $format = null, array $context = [])
    {
        /** @var array $data */
        $data = $this->normalizer->normalize($valueObject, $format, $context);
        return $data['value'] ?? null;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof ValueObjectInterface;
    }
}
