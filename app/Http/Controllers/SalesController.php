<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class SalesController extends Controller
{
    public function store(Request $request)
    {
        try{
            $sale = new Sales;

            $sale->dispatchMonth = $request->dispatchMonth;
            $sale->salesRefNo = $request->salesRefNo;
            $sale->customer = $request->customer;
            $sale->file = $request->file;
            $sale->salesDetail = $request->salesDetail;
            $sale->duplicateRefId= $request->duplicateRefId;
            $sale->salesFile = $request->salesFile;

            $sale->save();
            $response = [
                "message" => "details Added Sucessfully!",
                "status" => 200
            ];
            $status = 200;          


        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
                "status" => 406
            ];            
            $status = 406;         

        }catch(QueryException $e){
            $response = [
                "message" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406;             
        }

        return response($response,$status);
    }


    public function update(Request $request,$id)
    {
        try{
            $sale = Sales::find($id);

            if(!$sale){
                throw new Exception("sales Not found");
            }

            $sale->dispatchMonth = $request->dispatchMonth;
            $sale->salesRefNo = $request->salesRefNo;
            $sale->customer = $request->customer;
            $sale->file = $request->file;
            $sale->salesDetail = $request->salesDetail;
            $sale->duplicateRefId= $request->duplicateRefId;
            $sale->salesFile = $request->salesFile;

            $sale->save();

            $response = [       
               "message" =>'details Updated Successfully', 
               "status" => 200
            ];
            $status = 200;  


            }catch(Exception $e){ 
               $response = [
                   "message"=>$e->getMessage(),
                   "status" => 406
                ];            
               $status = 200;

            }catch(QueryException $e){
               $response = [
                   "message" => $e->errorInfo,
                   "status" => 406
                ];
               $status = 406; 
            }

            return response($response,$status);
    } 


    public function destroy($id)
    { 
        try{
            $sale = Sales::find($id);

            if(!$sale){
                throw new Exception("sale not found");

            }else{
                $sale->delete();
                $response = [          
                    "message" => "sales Deleted Sucessfully!",
                    "status" => 200
                ];
                $status = 200;     
            }

        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
                "status" => 406
            ];            
            $status = 406;

        }catch(QueryException $e){
            $response = [
                "message" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406; 
        }

        return response($response,$status);
    } 


    public function showData()
    {
        try{    
            $sale = DB::table('sales')->get();
            if(!$sale){
                throw new Exception("customer not found");
            }
            $response=[
              "message" => "sales List",
              "data" => $sale

            ];
            $status = 200; 
            
        }catch(Exception $e){
            $response = [
              "message"=>$e->getMessage(),
              "status" => 406
              ];            
            $status = 406;

        }catch(QueryException $e){
            $response = [
              "message" => $e->errorInfo,
              "status" => 406
            ];
            $status = 406; 
        }

        return response($response,$status); 
    }
}
