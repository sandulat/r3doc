<?php

declare(strict_types=1);

namespace Sandulat\R3doc\Http\Controllers;

use Illuminate\View\View;
use Sandulat\R3doc\R3doc;
use Illuminate\Routing\Controller;

final class DocController extends Controller
{
    /**
     * Display the R3doc view.
     *
     * @return \Illuminate\View\View
     */
    public function __invoke(R3doc $r3doc): View
    {
        return view('r3doc::app')->with([
            'routes' => $r3doc->getJsonRoutes(),
        ]);
    }
}
