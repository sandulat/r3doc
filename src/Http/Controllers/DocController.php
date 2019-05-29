<?php

declare(strict_types=1);

namespace Sandulat\R3doc\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Sandulat\R3doc\R3doc;

final class DocController extends Controller
{
    /**
     * Display the R3doc view.
     *
     * @return \Illuminate\View\View
     */
    public function __invoke(R3doc $r3doc): View
    {
        dd($r3doc);

        return view('r3doc::app');
    }
}
