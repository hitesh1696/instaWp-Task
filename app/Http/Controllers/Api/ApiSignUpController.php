<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiSignUpController extends Controller
{
    use ApiResponse;
    public function signup(Request $request)
    {
        try {
        $validated = $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);
        
            $user =  User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'wallet' => '0.00'
            ]);
            return $this->successResponse([
                'Your account is successfully created!!',
                'token' => $user->createToken('instawp')->plainTextToken,
            ]);
        } 
        catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        } 
        catch (\Exception $e) {
            return $this->errorResponse([
                'Something went wrong, Please try again later',
            ]);
        }
       
    }


    public function signin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::query()->where('email', $request->get('email'))->first();
        
        if (Auth::attempt($credentials)) {
            return $this->successResponse(
                'You have successfully logged in!',
                [
                    'user' => $user,
                    'token' => $user->createToken('instaWp')->plainTextToken,
                ]
            );
        }

        return $this->errorResponse(
            'Your provided credentials do not match in our records.',
        );
    }

    public function logout(Request $request)
    {
        $user = User::find($request->user_id);
        if(!$user)
        {
            return $this->errorResponse('User Not Found');
        }else{
            session()->invalidate();
            session()->regenerateToken();
            $user->tokens()->delete();
            return $this->successResponse(
                'You have successfully logged Out!',
            );
        }
    }
}