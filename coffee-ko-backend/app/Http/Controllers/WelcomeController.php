<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

class WelcomeController extends Controller
{
    public function index()
    {
        $allRoutes = collect(Route::getRoutes())->map(function ($route) {
            return [
                'method' => implode('|', $route->methods()),
                'uri'    => $route->uri(),
                'name'   => $route->getName(),
            ];
        })->filter(function ($route) {
            return !str_starts_with($route['uri'], '_');
        })->values();

        return view('welcome', ['routes' => $allRoutes]);
    }
}