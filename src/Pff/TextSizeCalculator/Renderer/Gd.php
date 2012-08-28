<?php

namespace Pff\TextSizeCalculator\Renderer;

use Pff\TextSizeCalculator\RendererInterface;

class Gd implements RendererInterface
{
    public function getEstimatedHeight($font_file, $font_size, $text, $width)
    {
        $space_width_info = imageftbbox($font_size, 0, $font_file, 'M');

        $height = $space_width_info[1] - $space_width_info[7];

        $lines = $this->getBrokenUpTextArray($font_file, $font_size, $text, $width);
        return $height * count($lines);
    }

    public function getBrokenUpTextArray($font_file, $font_size, $text, $width)
    {
        $lines = array();
        $sets = explode("\n", $text);

        $space_width_info = imageftbbox($font_size, 0, $font_file, ' ');

        foreach($sets as $set)
        {
            $t = trim($set);
            $words = explode(' ', $t);
            $line = '';
            $line_width = 0;

            foreach($words as $word)
            {
                $new_line = trim($line . ' ' . $word);
                $new_line_info = imageftbbox($font_size, 0, $font_file, $new_line);
                $new_line_width = $new_line_info[2] - $new_line_info[0];

//                echo "Evaluating '$new_line' width: $new_line_width max_width: $width\n";

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

    public function getPages($font_file, $font_size, $text, $page_width, $page_height)
    {
        $space_width_info = imageftbbox($font_size, 0, $font_file, 'M');

        $height = $space_width_info[1] - $space_width_info[7];

        $lines_per_page = floor($page_height / $height);

        $lines = $this->getBrokenUpTextArray($font_file, $font_size, $text, $page_width);

        $line_count = count($lines);

        $pages = array();

        while(count($lines) > 0)
        {
            $chunk = array_splice($lines, 0, $lines_per_page);
            if ($chunk[0] == '')
                unset($chunk[0]);
            $chunk[] = $lines[0];
            $junk = array_splice($lines, 0, 1);

            $pages[] = trim(implode("\n", $chunk));
        }

        return $pages;
    }
}