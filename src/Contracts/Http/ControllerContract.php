<?php

namespace Mf\Contracts\Http;

interface ControllerContract
{
    public function render($view, $layout = null) : void;

	public function content() : void;

	public function script() : void;
}