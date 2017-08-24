<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function _make($layout, $page, $data = [], $render = FALSE)
    {
        $data['view_layout'] = $layout;
        $data['view_page'] = $page;
        $data['view_url'] = Request::url();

        $view = view()->make("layouts.$layout.pages.$page", $data);

        return $render ? $view->render() : $view;
    }
}
