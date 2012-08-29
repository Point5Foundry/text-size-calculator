Text Size Calculator
====================

I needed a simple tool to help me determine how many pages particular content would spread across.

Building a PDF, we had a set content area where the text had to fit, but the number of pages didn't matter.


Example
-------

    $gd = new \Pff\TextSizeCalculator\Renderer\Gd();
    $pages = $gd->getPages($font, $font_size, $text, 200, 100);

Caveats
-------

The sizes passed and used are ... hit and miss. Font size will be point w/ GD2.

$page_width and $page_height are in pixels.

It's nowhere near perfect, but I hope this saves someone else some pain.