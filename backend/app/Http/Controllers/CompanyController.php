<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Get all Companies Data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanies(Request $request){
        return Company::select('id','name')
                ->whereNull('deleted_at')
                ->orderBy('name')->paginate(10);
    }

     /**
     * Get Company detail.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function getCompanyDetail($id){
        if (Company::where('id', $id)->exists()) {
            $company = Company::where('id', $id)->get();
            
            return $company;
          } else {
            return response()->json([
              "message" => "Company not found"
            ], 404);
          }
    }

    /**
     * Get Company detail.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function insertCompany(Request $request){
        $user = auth()->user();
        $company = new Company;
        $company->name = $request->name;
        $company->company = $request->company;
        $company->user_id = $user->id;
        $company->save();

        return response()->json([
            "message" => "records inserted successfully",
            "data" => $company
          ], 200);
    }

     /**
     * Update Company detail.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function updateCompanies(Request $request, $id){
        if (Company::where('id', $id)->exists()) {
            $company = Company::find($id);
    
            $company->name = is_null($request->name) ? $company->name : $request->name;
            $company->company = is_null($request->company) ? $company->company : $request->company;
            $company->save();
    
            return response()->json([
              "message" => "records updated successfully",
              "data" => $company
            ], 200);
          } else {
            return response()->json([
              "message" => "Company not found"
            ], 404);
          }
    }

     /**
     * Delete Company.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function deleteCompany($id){
        if (Company::where('id', $id)->exists()) {
            $company = Company::find($id);
            $company->delete();
    
            return response()->json([
              "message" => "records deleted successfully"
            ], 200);
          } else {
            return response()->json([
              "message" => "Company not found"
            ], 404);
          }
    }
}
