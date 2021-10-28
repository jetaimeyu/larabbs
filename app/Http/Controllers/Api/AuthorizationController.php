<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Overtrue\LaravelSocialite\Socialite;

class AuthorizationController extends Controller
{
    //
    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        $driver = \Socialite::create($type);
        try {
            if ($code= $request->code){
                $authUser = $driver->userFromCode($code);
            }else{
                if ($type=='wechat'){
                    $driver->withOprnid($request->openid);
                }
                $authUser = $driver->userFromToken($request->access_token);

            }

        }catch (\Exception $exception){
            throw new AuthorizationException('参数作物,未获取用户信息！');
        }
        if (!$authUser->getId()){
            throw new AuthorizationException('参数作物,未获取用户信息！');
        }
        switch ($type){
            case 'wechat':
                $unionId =$authUser->getRow()['unionid'] ?? null;
                if ($unionId){
                    $user= User::where('weixin_unionid', $unionId)->first();
                }else{
                    $user= User::where('weixin_openid', $authUser->getId())->first();
                }
                if (!$user){
                    $user = User::create([
                        'name' =>$authUser->getNickName(),
                        'avatar'=>$authUser->getAvatar(),
                        'weixin_openid'=>$authUser->getId(),
                        'weixin_uinionid'=>$unionId
                    ]);
                }
                break;
        }
        return response()->json(['token'=>$user->id]);

    }
}
