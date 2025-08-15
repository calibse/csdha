<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use WeasyPrint\Facade as WeasyPrint;
use Illuminate\Contracts\Support\Renderable;

class PdfController extends Controller
{
    public function create() {
        /*
        return view('pdf.test', [
            'data' => 'hello world'
        ]);
        */
        $service = WeasyPrint::prepareSource(new class implements Renderable {
            public function render(): string
            {
                return view('pdf.test', [
                    'data' => 'hello world'
                ]);
            }
        });
        $output = $service->build();

        return $output->stream('document.pdf');
    }
}
