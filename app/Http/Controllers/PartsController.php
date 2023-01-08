<?php

namespace App\Http\Controllers;

use App\Models\Parts;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class PartsController extends Controller
{
    public function store(Request $request)
    {
        try{
            $part = new Parts;

            $part->customerName = $request->customerName;
            $part->partId = $request->partId;
            $part->partName = $request->partName;
            $part->partDescription = $request->partDescription;
            $part->file = $request->file;
            $part->sdCode= $request->sdCode;
            $part->units = $request->units;
            $part->bundleQty = $request->bundleQty;
            $part->threshold = $request->threshold;

            $part->save();
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
            $part = Parts::find($id);

            if(!$part){
                throw new Exception("part Data Not found");
            }

            $part->customerName = $request->customerName;
            $part->partId = $request->partId;
            $part->partName = $request->partName;
            $part->partDescription = $request->partDescription;
            $part->file = $request->file;
            $part->sdCode= $request->sdCode;
            $part->units = $request->units;
            $part->bundleQty = $request->bundleQty;
            $part->threshold = $request->threshold;

            $part->save();

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
            $part = Parts::find($id);

            if(!$part){
                throw new Exception("part not found");

            }else{
                $part->delete();
                $response = [          
                    "message" => "Parts Deleted Sucessfully!",
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
            $part = DB::table('parts')->get();
            if(!$part){
                throw new Exception("customer not found");
            }
            $response=[
              "message" => "customer List",
              "data" => $part

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