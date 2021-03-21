<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    /**
     * Get all Transaction Data.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    protected $sortFieldConst = ['trx_name','id','created_by','company_name','trx_amount','trx_type','trx_status'];
    protected $keyword = '';

    public function getTransactions(Request $request){
        
        $sortField = 'transactions.updated_at';
        $sortType = 'desc';
        $pageSize = 10;
        $status ='';
        $type = '';
        
        if (!is_null($request->sortField) && !in_array($request->sortField,$this->sortFieldConst)) {
            return response()->json(["message"=>"sortField not valid"], 422);
        }

        $sortField = is_null($request->sortField)?$sortField:$request->sortField;
        $sortType = is_null($request->sortType)?$sortType:$request->sortType;
        $pageSize = is_null($request->pageSize)?$pageSize:$request->pageSize;
        $status = is_null($request->status)?$status:$request->status;
        $type = is_null($request->type)?$type:$request->type;
        $this->keyword = is_null($request->keyword)?$this->keyword:$request->keyword;

        switch($sortField){
          case 'id':
            $sortField = 'transactions.id';
            break;
          case 'trx_name':
            $sortField = 'transactions.name';
            break;
          case 'created_by':
            $sortField = 'users.name';
            break;
          case 'company_name':
            $sortField = 'company.company_name';
            break;
          case 'trx_type':
            $sortField = 'transactions.type';
            break;
          case 'company_name':
            $sortField = 'company.company_name';
            break;
        }


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
                ->where('transactions.status','like','%'.$status.'%')
                ->where('transactions.type','like','%'.$type.'%')
                ->where(function($query){
                  return $query
                  ->where('transactions.name','like','%'.$this->keyword.'%')
                  ->orWhere('transactions.id', 'like', '%'.$this->keyword.'%')
                  ->orWhere('users.name', 'like', '%'.$this->keyword.'%')
                  ->orWhere('company.company_name', 'like', '%'.$this->keyword.'%');
                })
                ->orderBy($sortField,$sortType)->paginate($pageSize);
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