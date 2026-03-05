<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $content = implode(PHP_EOL, [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /superadmin',
            'Sitemap: '.route('public.sitemap'),
        ]);

        return response($content.PHP_EOL)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
