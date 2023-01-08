<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Vendor;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class GetDataController extends Controller
{
    public function getCustomer()
    {
        try{
            $customer = Customer::all();

            if(!$customer){
                throw new Exception("Data not found");
            }else{   

                $customer = Customer::select('id','customerName')->get();

                $response = [
                    'data' => $customer,
                ];
                $status = 201;   
            }

        }catch(Exception $e){
            $response = [
                "error"=>$e->getMessage(),
                "status"=>406
            ];            
            $status = 406;

        }catch(QueryException $e){
            $response = [
                "message" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
        
        return response($response, $status);    
    }

    public function getVendor()
    {
        try{
            $customer = Vendor::all();

            if(!$customer){
                throw new Exception("Data not found");
            }else{   

                $customer = Vendor::select('id','vendorName')->get();

                $response = [
                    'data' => $customer,
                ];
                $status = 201;   
            }

        }catch(Exception $e){
            $response = [
                "error"=>$e->getMessage(),
                "status"=>406
            ];            
            $status = 406;

        }catch(QueryException $e){
            $response = [
                "message" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
        
        return response($response, $status);    
    }
}
