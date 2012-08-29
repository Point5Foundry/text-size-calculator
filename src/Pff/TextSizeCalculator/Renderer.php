<?php

namespace Pff\TextSizeCalculator;

abstract class Renderer
{
    /**
     * @abstract
     * @param $font_file string The path to the font file
     * @param $font_size int The font size in points
     * @param $text string The text
     * @return int The width of the rendered text in px
     */
    abstract public function getTextWidth($font_file, $font_size, $text);

    /**
     * @abstract
     * @param $font_file string The path to the font file
     * @param $font_size int The font size in points
     * @param $text string The text
     * @return int The height of the rendered text in px
     */
    abstract public function getTextHeight($font_file, $font_size, $text);

    /**
     * @param $font_file string The path to the font file
     * @param $font_size int The font size in points
     * @param $text string The text
     * @param $width int The desired maximum width in px
     * @return int The height of the rendered text in px
     */
    public function getEstimatedHeight($font_file, $font_size, $text, $width)
    {
        $height = $this->getTextHeight($font_file, $font_size, 'M');

        $lines = $this->getBrokenUpTextArray($font_file, $font_size, $text, $width);
        return $height * count($lines);
    }

    /**
     * @param $font_file string The path to the font file
     * @param $font_size int The font size in points
     * @param $text string The text
     * @param $width int The desired maximum width in px
     * @return string[] An array of lines of text conforming to the width provided
     */
    public function getLines($font_file, $font_size, $text, $width)
    {
        $lines = array();
        $sets = explode("\n", $text);

        foreach($sets as $set)
        {
            $t = trim($set);
            $words = explode(' ', $t);
            $line = '';
            $line_width = 0;

            foreach($words as $word)
            {
                $new_line = trim($line . ' ' . $word);
                $new_line_width = $this->getTextWidth($font_file, $font_size, $new_line);

                if ($new_line_width > $width)
                {
                    $lines[] = trim($line);
                    $line = $word;
                } else {
                    $line .= ' '.$word;
                }
            }
            $lines[] = trim($line);
        }

        $count = count($lines) - 1;
        if ($lines[$count] == '')
            unset($lines[$count]);

        return $lines;
    }

    /**
     * @param $font_file string The path to the font file
     * @param $font_size int The font size in points
     * @param $text string The text
     * @param $page_width int The desired maximum width for a page in px
     * @param $page_height int The desired maximum height for a page in px
     * @return string[] An array of text conforming to the width provided
     */
    public function getPages($font_file, $font_size, $text, $page_width, $page_height)
    {
        $height = $this->getTextHeight($font_file, $font_size, 'M');

        $lines_per_page = floor($page_height / $height);

        $lines = $this->getLines($font_file, $font_size, $text, $page_width);

        $line_count = count($lines);

        $pages = array();

        while(count($lines) > 0)
        {
            $chunk = array_splice($lines, 0, $lines_per_page);

            if ($chunk[0] == '')
                unset($chunk[0]);

            if (isset($line_count[0]))
                $chunk[] = $lines[0];

            $junk = array_splice($lines, 0, 1);

            $pages[] = trim(implode("\n", $chunk));
        }

        return $pages;
    }
}