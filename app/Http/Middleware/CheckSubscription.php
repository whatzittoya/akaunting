<?php

namespace App\Http\Middleware;

use App\Mail\WelcomeEmail;
use App\Notifications\Subscription\Expired;

use Closure;
use DateTime;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = user();
        // $user->roles()->attach([8]);
        // $user->roles()->detach([2]);
        if($user->hasRole('manager')){
            $role=$user->roles->where('name','manager')->first();
            $expiresAt=$role->pivot->expires_at;
            if($expiresAt !== null){
                $expiresDate = new DateTime($expiresAt);
                $currentDate = new DateTime();

                if ($expiresDate < $currentDate) {

                    $user->attachRole('manager-expired');
                    $user->detachRole('manager');

                    $user->notify(new Expired());

                } 

            }
        }

        //  $user->notify(new SubscriptionExpiredNotification());
        // $a=Notification::sendNow($user, new SubscriptionExpiredNotification());
        // $user->detachRole('temp');
        // $res=Mail::to('whosendall@gmail.com')->send(new WelcomeEmail());
        //  dd($a);
        return $next($request);

    }
}
