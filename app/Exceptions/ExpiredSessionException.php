<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Throwable

class ExpiredSessionException extends Exception
{
    public function render(Request $request, Throwable $e): Response
    {
        if ($e instanceof TokenMismatchException) {
            return redirect('/');
        }
        return parent::render($request, $e);
    }
}
