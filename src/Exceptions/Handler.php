<?php

namespace Mf\Exceptions;

class Handler
{
    public function render(\Throwable $th)
    {
        if (in_array('render', get_class_methods($th))) {
            $th->render();
        } else {
            if (defined('TESTING') && constant('TESTING')) {
                kDump($th);
            }
            dump($th);
        }
    }
}
