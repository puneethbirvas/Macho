<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        try{
            $customer = new Customer;
            $customer->customerCode = $request->customerCode;
            $customer->gstNumber= $request->gstNumber;
            $customer->customerName= $request->customerName;
            $customer->billingAddress= $request->billingAddress;
            $customer->shippingAddress= $request->shippingAddress;
            $customer->contactPersonName= $request->contactPersonName;
            $customer->primaryContactNumber= $request->primaryContactNumber;
            $customer->secondaryContactNumber= $request->secondaryContactNumber;
            $customer->email= $request->email;
            $customer->remark= $request->remark;

            $customer->save();
            
            $response = [
                "message" => "Customer DataAdded Sucessfully!",
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
            $customer = Customer::find($id);

            if(!$customer){
                throw new Exception("customer Data Not found");
            }

            $customer->customerCode= $request->customerCode;
            $customer->gstNumber= $request->gstNumber;
            $customer->customerName= $request->customerName;
            $customer->billingAddress= $request->billingAddress;
            $customer->shippingAddress= $request->shippingAddress;
            $customer->contactPersonName= $request->contactPersonName;
            $customer->primaryContactNumber= $request->primaryContactNumber;
            $customer->secondaryContactNumber= $request->secondaryContactNumber;
            $customer->email= $request->email;
            $customer->remark= $request->remark;

            $customer->save();

            $response = [       
               "message" =>' customer Data Updated Successfully', 
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
            $customer = Customer::find($id);
            if(!$customer){
                throw new Exception("customer not found");
            }else{
                $customer->delete();
                $response = [          
                    "message" => " customer Data Deleted Sucessfully!",
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

            $result = DB::table('customers')->get();
            if(!$result){
                throw new Exception("customer not found");
            }
            $response=[
              "message" => "customer List",
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
              "message" => $e->errorInfo,
              "status" => 406
            ];
            $status = 406; 
        }
        return response($response,$status); 
    }
}
