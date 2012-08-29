<?php

namespace Pff\TextSizeCalculator\Renderer;

use Pff\TextSizeCalculator\Renderer;

class Gd extends Renderer
{
    /**
     * @param $font_file string The path to the font file
     * @param $font_size int The font size in points
     * @param $text string The text
     * @return int The width of the rendered text in px
     */
    public function getTextWidth($font_file, $font_size, $text)
    {
        $info = imageftbbox($font_size, 0, $font_file, $text);
        return $info[2] - $info[0];
    }

    /**
     * @param $font_file string The path to the font file
     * @param $font_size int The font size in points
     * @param $text string The text
     * @return int The height of the rendered text in px
     */
    public function getTextHeight($font_file, $font_size, $text)
    {
        $info = imageftbbox($font_size, 0, $font_file, 'M');

        return $info[1] - $info[7];
    }
}