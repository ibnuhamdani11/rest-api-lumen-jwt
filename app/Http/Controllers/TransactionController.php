<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Balance;

class TransactionController extends Controller
{
    public function transaction(Request $request)
    {
        $this->validate($request, [
            'trx_id'    => 'required|string|unique:transaction', // If trx_id exists, decline & rollback transaction
            'amount'    => 'required',
            'user_id'   => 'required'
        ]);   

        if($request->amount <=  0.00000001){
            return response()->json(['status'=>'failed','message'=>'Amount is same or less than 0.00000001 !','code'=>400]);
            die();
        }
        // find balance by user_id from table balance,
        $checkBalance = Balance::where("id", $request->user_id)->first();
        // if($checkBalance){
            // if balance.amount_available < input.amount, decline (insufficient)
        if($checkBalance->amount_available < $request->amount){
            return response()->json(['status'=>'failed','message'=>'Amount available is less than amount!','code'=>400]);
            die();
        }
        // Insert table transaction
        $result = Transaction::create($request->all());
        // } 
        if($result){
            // update balance.amount_available, (balance.amount_available - input.amount)
            $dataUpdate = array(
                "amount_available" => $checkBalance->amount_available - $request->amount
            );
            $dataWillUpdate = Balance::where("user_id", $request->user_id);
            $dataWillUpdate->update($dataUpdate);
            return response()->json(['status'=>'success', 
                                        'data' => ['trx_id' => $request->trx_id, 'amount' => $request->amount],
                                        'message'=>'Transaction successfull created','code'=>200]);
        }
// 4. 
// 5. add sleep/delay 30 seconds
// 6. 
// 7. 
// 8. Return data transaction & balance by user_id. Trim (not rounding) to 6
// digit decimal and not scientific e notation (ex: 3.456e11)
    }
} 