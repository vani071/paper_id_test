<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Get all Transaction Data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactions(Request $request){
        return DB::table('transactions')
                ->select('transactions.id as id', 
                'transactions.name as trx_name',
                'users.name as created_by',
                'company.company_name as company_name',
                'transactions.type as trx_type',
                'transactions.amount as trx_amount',
                'transactions.status as trx_status',
                )
                ->leftJoin('users','users.id','=','transactions.user_id')
                ->leftJoin('company','company.id','=','transactions.company_id')
                ->whereNull('transactions.deleted_at')
                ->orderByDesc('transactions.updated_at')->paginate(10);
    }

     /**
     * Get Transaction detail.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionDetail($id){
        
        $company = [];

        if (Transaction::where('id', $id)->exists()) {
            $trx = Transaction::where('id', $id)->get();
            if(!is_null($trx[0]->company_id)) $company = Company::find($trx[0]->company_id);

            return response()->json([
                "trx"=> $trx[0],
                "company"=> $company
            ]);

          } else {
            return response()->json([
              "message" => "Transaction not found"
            ], 404);
          }
    }

    /**
     * Get Transaction detail.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function insertTransaction(Request $request){
        $user = auth()->user();
        $trx = new Transaction;
        $trx->name = $request->name;
        $trx->company_id =  $request->company_id;
        $trx->user_id = $user->id;
        $trx->amount =  $request->amount;
        $trx->status =  $request->status;
        $trx->comment =  $request->comment;
        $trx->type =  $request->type;

        $trx->save();

        return response()->json([
            "message" => "records inserted successfully",
            "data" => $trx
          ], 200);
    }

     /**
     * Update Transaction detail.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function updateTransactions(Request $request, $id){
        if (Transaction::where('id', $id)->exists()) {
            $trx = Transaction::find($id);
    
            $trx->status = is_null($request->status) ? $trx->status : $request->status;
            $trx->comment = is_null($request->comment) ? $trx->comment : $request->comment;
            $trx->save();
    
            return response()->json([
              "message" => "records updated successfully",
              "data" => $trx
            ], 200);
          } else {
            return response()->json([
              "message" => "Transaction not found"
            ], 404);
          }
    }

     /**
     * Delete Transaction.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function deleteTransaction($id){
        if (Transaction::where('id', $id)->exists()) {
            $trx = Transaction::find($id);
            $trx->delete();
    
            return response()->json([
              "message" => "records deleted successfully"
            ], 200);
          } else {
            return response()->json([
              "message" => "Transaction not found"
            ], 404);
          }
    }
}