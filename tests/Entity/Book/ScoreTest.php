<?php

namespace App\Tests\Entity\Book;

use App\Entity\Book\Score;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ScoreTest extends TestCase
{
    public function testCreateScore()
    {
        $score = Score::create(5);
        $this->assertEquals(5, $score->getValue());
        $score = Score::create(0);
        $this->assertEquals(0, $score->getValue());
        $score = Score::create(null);
        $this->assertEquals(null, $score->getValue());
    }

    public function testThrowInvalidArgumentExceptionIfInvalid()
    {
        $catched = false;
        try {
            Score::create(-1);
        } catch (InvalidArgumentException $exception) {
            $catched = true;
        }
        $this->assertTrue($catched);
    
        $catched = false;
        try {
            Score::create(6);
        } catch (InvalidArgumentException $exception) {
            $catched = true;
        }
        $this->assertTrue($catched);
    }
}
