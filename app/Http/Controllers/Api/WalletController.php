<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class WalletController extends Controller
{
    use ApiResponse;
    public function addMoney(Request $request)
    {
        try {

            $request->validate([
                'amount' => 'required|numeric|min:3|max:100'
            ]);

            $user = Auth::user();
            $user->wallet += $request->amount;
            $user->save();

            return $this->successResponse('Money added successfully', ['wallet' => $user->wallet]);
        } 
        catch (ValidationException $e) {
            return $this->errorResponse(null, 
                 $e->errors(),
            );
        } 
        catch (\Exception $e) {
            return $this->errorResponse([
                'Unable to add money, Something went wrong, Please try again later',
            ]);
        }
    }

    public function buyCookie(Request $request)
    {
        try {

            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            $user = Auth::user();
            $totalCost = $request->quantity;

            if ($user->wallet < $totalCost) {
                return $this->errorResponse('Insufficient funds');
            }

            $user->wallet -= $totalCost;
            $user->save();

            return $this->successResponse('Cookie purchased successfully', ['wallet' => $user->wallet]);

        } 
        catch (ValidationException $e) {
            return $this->errorResponse(null, $e->errors(),
            );
        } 
        catch (\Exception $e) {
            return $this->errorResponse([
                'Unable to buy cookie, Please try again later',
            ]);
        }
    }
}
