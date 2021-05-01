<?php

namespace Mf\Abstracts\Http\Controllers;

use Mf\Contracts\Http\ControllerContract;

abstract class Controller implements ControllerContract
{
    protected $view;

    abstract protected function getViewsPath(): string;

    abstract protected function getLayoutsPath(): string;

    public function __construct()
    {
        $this->boot();
    }

    protected function boot(): void
    {
        $this->view = new \stdClass();
    }

    public function render($view, $layout = null): void
    {

        $this->view->page = str_replace('.', '/', $view);
        if (is_null($layout) || !file_exists(src("{$this->getViewsPath()}$layout.phtml"))) {
            require_once src("{$this->getLayoutsPath()}default.phtml");
        } else {
            require_once src("{$this->getLayoutsPath()}$layout.phtml");
        }
    }

    public function content(): void
    {
        require_once src("{$this->getViewsPath()}{$this->view->page}.phtml");
    }

    public function script(): void
    {
        require_once src("{$this->getViewsPath()}{$this->view->page}.js.phtml");
    }
}
