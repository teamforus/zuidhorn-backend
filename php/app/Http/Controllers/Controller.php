<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function _make($layout, $page, $data = [], $render = FALSE)
    {
        $data['view_layout'] = $layout;
        $data['view_page'] = $page;

        $view = view()->make("layouts.$layout.pages.$page", $data);

        return $render ? $view->render() : $view;
    }
}
