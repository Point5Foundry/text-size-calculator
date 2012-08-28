<?php

namespace Pff\TextSizeCalculator;

interface RendererInterface
{
    public function getPages($font_file, $font_size, $text, $page_width, $page_height);
}