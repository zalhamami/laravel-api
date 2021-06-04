<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;

class AuthController extends ApiController
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * AuthController constructor.
     * @param UserRepository $userRepo
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        $user->assignRole(Role::USER);
        $user->sendEmailVerificationNotification();

        return $this->successResponse([
            'message' => 'Successfully created user!'
        ], 201);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = $this->userRepo->getByField('email', $request['email']);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)) {
            return $this->errorResponse('User not found', 401);
        }

        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            return $this->errorResponse('Please verify your email first before login.', 400);
        }

        $response = $this->createAccessToken($user);
        return $this->singleData($response);
    }

    /**
     * @param User $user
     * @return array
     */
    private function createAccessToken(User $user)
    {
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->successResponse([
            'message' => 'Successfully logged out'
        ]);
    }
}
