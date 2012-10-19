<?php

class GdTest extends PHPUnit_Framework_TestCase
{

    public function testBasicFunctionality()
    {
        $gd = new \Pff\TextSizeCalculator\Renderer\Gd();

        $font = __DIR__.'/../../test-fodder/ArialNarrow.ttf';

        $font_size = 12;

        $height = $gd->getEstimatedHeight($font, $font_size, 'MHello', 1000);

        $this->assertLessThanOrEqual(12, $height);

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

        $this->assertLessThanOrEqual(48, $height);

        $pages = $gd->getPages($font, $font_size, $text, 200, 100);

        $this->assertContentIsInPages($text, $pages);
    }

    public function testMoreWordChunking()
    {
        $gd = new \Pff\TextSizeCalculator\Renderer\Gd();

        $font = __DIR__.'/../../test-fodder/ArialNarrow.ttf';

        $font_size = 12;

        $text = 'My very nice dog wants to say hello.

He is a nice dog, but sometimes he can be grumpy, so watch your fingers, because he bites.';

        $pages = $gd->getPages($font, $font_size, $text, 150, 50);

        $expected_pages = array(
            "My very nice dog wants\nto say hello.\n\nHe is a nice dog, but",
            "sometimes he can be\ngrumpy, so watch your\nfingers, because he\nbites.",
        );

        $this->assertContentIsInPages($text, $pages);
    }

    private function assertContentIsInPages($content, $pages)
    {
        foreach($pages as $page)
        {
            $lines = explode("\n", $page);
            foreach($lines as $line)
            {
                if (trim($line) == '')
                    continue;
                if (strpos($content, $line) !== 0)
                {
                    $message = 'Provided content:'."\n\n".var_export($content, true)."\n\n does not match the paginated representation:\n\n".var_export($pages, true);
                    $message .= "\n\nSpecifically we expected to find:\n".trim($line)."\n\nBut found: ".var_export($content, true);
                    throw new PHPUnit_Framework_ExpectationFailedException(
                        trim($message)
                    );
                }
                $content = trim(substr($content, strlen($line)));
            }
        }
    }
}
