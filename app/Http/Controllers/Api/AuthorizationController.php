<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationRequest;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Overtrue\LaravelSocialite\Socialite;

class AuthorizationController extends Controller
{
    // 微信登录
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
        $token = auth('api')->login($user);
        return $this->responseWithToken($token)->setStatusCode(201);
    }


    public function store(AuthorizationRequest $request)
    {
        $username = $request->username;
        filter_var($username,FILTER_VALIDATE_EMAIL)?
            $credentials['email'] =$username:
            $credentials['phone'] =$username;

       $credentials['password'] =$request->password;;
        if (!$token = auth('api')->attempt($credentials)){
            throw new AuthorizationException('用户名或密码错误');
        }
       return  $this->responseWithToken($token)->setStatusCode(201);
    }

    public function responseWithToken($token)
    {
        return response()->json([
            'userinfo'=>auth('api')->user(),
            'access_token'=>$token,
            'token_type'=>'Bearer',
            'expires_in'=>\Auth::guard('api')->factory()->getTTL()*60
        ]);
    }
}
