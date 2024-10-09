<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\URL;
Use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function forgetPassword(Request $request){
        try{
            $user = User::where('email', $request->email)->get();

            if(count($user) > 0){
                $token = Str::random(40);
                $domain = URL::to('/');
                $url = $domain.'/auth/reset?token='.$token;

                $data['url'] = $url;
                $data['email'] = $request->email;
                $data['token'] = $token;
                $data['title'] = 'Смена пароля';
                $data['body'] = "Пожалуйста, нажмите ниже, чтобы сменить пароль";

                Mail::send('password',['data' => $data],function($message) use ($data){
                    $message->to($data['email'])->subject($data['title']);
                });

                $datetime = Carbon::now()->format('Y-m-d H:i:s');
                PasswordReset::updateOrCreate(
                    ['email' => $request->email],
                [
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => $datetime
                ]);

                return response()->json(['success' => true,'msg'=>'Please check your mail to reset your password!']);
            }
            else{
                return response()->json(['success' => false,'msg'=>'User not found!']);
            }
        }
        catch (\Exception $e){
            return response()->json(['success' => false,'msg'=>$e->getMessage()]);
        }
    }

    public function resetPasswordLoad(Request $request)
    {
        $resetData = PasswordReset::where('token', $request->token)->get();
        if (isset($request->token) && count($resetData) > 0)
        {
            $user = User::where('email',$resetData[0]['email'])->get();
            // return response([ compact('user'), 'message' => 'Success'], 200);
            return view('resetPassword', compact('user'));
        }
        else
        {
            // return response()->json(['message' => 'Failed']);
            return 'Failed';
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::find($request->id);
        $user->password =  Hash::make($request->password);
        $user->save();

        PasswordReset::where('email',$user->email)->delete();

        // $data = [
        //     'password' => $user->password,
        //     'email' => $user->email,
        //     'message' => 'Password changed successfully!'
        // ];

        // return $this->successResponse($data);
        return "<h1>Пароль успешно сменён</h1>";
    }
}