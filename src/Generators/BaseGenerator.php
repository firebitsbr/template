<?php

namespace Railken\LaraOre\Template\Generators;

use Railken\LaraOre\Template\Template;
use Illuminate\Support\Facades\App; 
use Twig; 

class BaseGenerator implements GeneratorContract
{

    /**
     * Generate a view file
     *
     * @param string $html
     * @param string $url
     *
     * @return string
     */
    public function generateViewFile($html, $name)
    {
        $path = config('view.paths.0');

        $view = 'cache/'.$name.'-'.hash('sha1', $name);

        $filename = $path.'/'.$view.'.twig';

        !file_exists(dirname($filename)) && mkdir(dirname($filename), 0777, true);

        file_put_contents($filename, $html);

        return $filename;
    }
}