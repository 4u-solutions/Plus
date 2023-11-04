<?php

namespace App\Exceptions;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
   public function report(Throwable $exception)
   {
       parent::report($exception);
   }


   public function render($request, Throwable $exception)
   {
       return parent::render($request, $exception);
   }
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $guard = Arr::get($exception->guards(), 0);
        // dd($guard);
       switch ($guard) {
         case 'admin':
           $login='admin.login';
           return redirect()->guest(route($login));
         break;
         case 'sanctum'  :
           $login='noautorizado';
           return response()->json(['error' => 'Unauthenticated.'], 401);
         break;
         default:
           $login='login';
           return response()->json(['error' => 'Unauthenticated.'], 401);
           break;
       }

    }
}
