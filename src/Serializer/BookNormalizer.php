<?php

namespace App\Serializer;

use App\Entity\Book;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class BookNormalizer implements ContextAwareNormalizerInterface
{
    public function __construct(
        private ObjectNormalizer $normalizer,
        private UrlHelper $urlHelper
    ) {
    }

    public function normalize($book, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($book, $format, $context);

        if (!empty($book->getImage())) {
            $data['image'] = $this->urlHelper->getAbsoluteUrl('/storage/default/' . $book->getImage());
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Book;
    }
}
