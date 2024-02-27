<?php
namespace App\Http\Controllers;

use App\Models\Auth\User;
use App\Models\Auth\UserInvitation;
use App\Notifications\Auth\Invitation;
use Illuminate\Http\Request;
use App\Notifications\ExampleNotification; //harus diisi
use App\Notifications\Subscription\Expired;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Notification; //harus diisi
class HomeController extends Controller
{
    public function test (){
        $user = user();
        $data = "Ini adalah contoh data";
        //dibawah ini merupakan
        //contoh mengirimkan notifikasi ke semua user
        // Notification::send($user, new Expired());
          $user->notify(new Expired());
          return 123;
        $invitation = UserInvitation::create([
            'user_id' => $user->id,
            'token' => (string) Str::uuid(),
            'created_by' => $user->id,
            'created_from' => 'ok',
        ]);
        //  return (new Invitation($invitation))->toMail($user);
        $notification = new Invitation($invitation);
        // $user->notify($notification);
        Notification::send($user, $notification);
        return $user;
        return response()->json([
            'message' => 'Notifikasi berhasil dikirim'
        ]);
    }
}