<?php

namespace App\Tests\Serializer;

use App\Entity\Book\Score;
use App\Model\Dto\Isbn\GetBookByIsbnResponse;
use App\Serializer\SingleValueObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SingleValueObjectNormalizerTest extends KernelTestCase
{
    public function testNormalizeValueObject()
    {
        self::bootKernel();
        $container = self::$container;
        $score = Score::create(5);
        /** @var SingleValueObjectNormalizer $normalizer */
        $normalizer = $container->get(SingleValueObjectNormalizer::class);
        $this->assertTrue($normalizer->supportsNormalization($score));
        $json = $normalizer->normalize($score);
        $this->assertEquals(5, $json);
    }

    public function testSkipNonValueObjects()
    {
        self::bootKernel();
        $container = self::$container;
        $object = new GetBookByIsbnResponse('', 0, '');
        /** @var SingleValueObjectNormalizer $normalizer */
        $normalizer = $container->get(SingleValueObjectNormalizer::class);
        $this->assertFalse($normalizer->supportsNormalization($object));
    }
}
