<?php

class GdTest extends PHPUnit_Framework_TestCase
{

    public function testBasicFunctionality()
    {
        $gd = new \Pff\TextSizeCalculator\Renderer\Gd();

        $font = __DIR__.'/../../test-fodder/ArialNarrow.ttf';

        $font_size = 12;

        $height = $gd->getEstimatedHeight($font, $font_size, 'Hello', 1000);

        $this->assertEquals(12, $height);

        $font_size = 16;

        $height = $gd->getEstimatedHeight($font, $font_size, 'Hello', 1000);

        $this->assertEquals(15, $height);
    }

    public function testWordChunking()
    {
        $gd = new \Pff\TextSizeCalculator\Renderer\Gd();

        $font = __DIR__.'/../../test-fodder/ArialNarrow.ttf';

        $font_size = 12;

        $text = 'My very nice dog wants to say hello.

He is a nice dog, but sometimes he can be grumpy, so watch your fingers, because he bites.';

        $height = $gd->getEstimatedHeight($font, $font_size, $text, 400);

        $this->assertEquals(48, $height);

        $pages = $gd->getPages($font, $font_size, $text, 200, 100);

        $expected_pages = array(
            "My very nice dog wants to say\nhello.\n\nHe is a nice dog, but sometimes\nhe can be grumpy, so watch\nyour fingers, because he bites."
        );

        $this->assertEquals($expected_pages, $pages);


        $pages = $gd->getPages($font, $font_size, $text, 150, 50);

        $expected_pages = array(
            "My very nice dog wants\nto say hello.\n\nHe is a nice dog, but\nsometimes he can be",
            "grumpy, so watch your\nfingers, because he\nbites.",
        );

        $this->assertEquals($expected_pages, $pages);

    }
}