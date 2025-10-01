<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetAuditVariables;
use App\Http\Middleware\SetTimezone;
use App\Http\Middleware\AuthorizeGpoa;
use App\Http\Middleware\AuthorizeEvent;
use App\Http\Middleware\AuthorizeAccomReport;
use App\Http\Middleware\AuthorizeIndex;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.index' => AuthorizeIndex::class,
            'auth.gpoa' => AuthorizeGpoa::class,
            'auth.event' => AuthorizeEvent::class,
            'auth.accom-report' => AuthorizeAccomReport::class,
        ]);
        $middleware->trustProxies(at: '*');
        $middleware->trustProxies(headers: Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO
        );
        $middleware->statefulApi();
        $middleware->redirectUsersTo('home.html');
        $middleware->redirectGuestsTo('/');
        $middleware->encryptcookies(except: ['timezone']);
        $middleware->web(append: [
            SetAuditVariables::class,
            SetTimezone::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
