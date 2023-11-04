<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;
// use Illuminate\Validation\ValidationException;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];
    protected $urlPass;
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
      $this->registerPolicies();
      VerifyEmail::toMailUsing(function ($notifiable, $url) {
        $url = explode('verify/',$url);
        $url = env('APP_URL').'api/email/verify/'.$url[1];

        $spaUrl = env('APP_FRONT_URL')."?verificarCorreo=".base64_encode($url);
        // dd($url);
         return (new MailMessage)->view('emails.verifyMail', ['url' => $spaUrl])
                                 ->subject('tickt.live - Verifica tu cuenta');
      });
    }
}
