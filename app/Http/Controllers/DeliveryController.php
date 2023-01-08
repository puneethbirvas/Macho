<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Vendor;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class DeliveryController extends Controller
{
    public function store(Request $request)
    {
        try{
            $delivery = new Delivery;
            $delivery->dispatchDate = $request->dispatchDate;
            $delivery->dispatchTime= $request->dispatchTime;
            $delivery->type= $request->type;

            if($delivery->type == "customer" ){
                $delivery->customerName= $request->customerName;
                $delivery->vendorName= null;
            }

            if($delivery->type == "vendor" ){
                $delivery->vendorName= $request->vendorName;
                $delivery->customerName= null;
            }
            $delivery->deliveryMode= $request->deliveryMode;
            $delivery->orderDate= $request->orderDate;
            $delivery->orderReferenceNo= $request->orderReferenceNo;
            $delivery->deliveryType= $request->deliveryType;

            $delivery->save();
            
            $response = [
                "message" => "Delivery Scheduled Sucessfully!",
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
                "error" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406;             
        }

        return response($response,$status);
    } 


    public function update(Request $request,$id)
    {
        try{
            $delivery = Delivery::find($id);

            if(!$delivery){
                throw new Exception("Data not found");
            }

            $delivery->dispatchDate = $request->dispatchDate;
            $delivery->dispatchTime= $request->dispatchTime;
            $delivery->type= $request->type;

            if($delivery->type == "customer" ){
                $delivery->customerName= $request->customerName;
                $delivery->vendorName= null;
            }

            if($delivery->type == "vendor" ){
                $delivery->vendorName= $request->vendorName;
                $delivery->customerName= null;
            }
            $delivery->deliveryMode= $request->deliveryMode;
            $delivery->orderDate= $request->orderDate;
            $delivery->orderReferenceNo= $request->orderReferenceNo;
            $delivery->deliveryType= $request->deliveryType;

            $delivery->save();

            $response = [       
               "message" =>' Delivery Scheduled Data Updated Successfully', 
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
                   "error" => $e->errorInfo,
                   "status" => 406
                ];
               $status = 406; 
            }

            return response($response,$status);
    } 


    public function destroy($id)
    { 
        try{
            $delivery = Delivery::find($id);
            if(!$delivery){
                throw new Exception("Data not found");
            }else{
                $delivery->delete();
                $response = [          
                    "message" => " Data Deleted Sucessfully!",
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
                "error" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406; 
        }

        return response($response,$status);
    } 

    public function showData()
    {
        try{    

            $result = DB::table('deliveries')->get();
            if(!$result){
                throw new Exception("Data not found");
            }
            $response=[
              "message" => "Deliveries List",
              "data" => $result

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
              "error" => $e->errorInfo,
              "status" => 406
            ];
            $status = 406; 
        }
        return response($response,$status); 
    }

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
                "error" => $e->errorInfo,
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
                "error" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
        
        return response($response, $status);    
    }
}
