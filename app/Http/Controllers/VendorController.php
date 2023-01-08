<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class VendorController extends Controller
{
    public function store(Request $request)
    {
        try{
            $Vendor = new Vendor;
            $Vendor->vendorCode = $request->vendorCode;
            $Vendor->gstNumber= $request->gstNumber;
            $Vendor->vendorName= $request->vendorName;
            $Vendor->billingAddress= $request->billingAddress;
            $Vendor->shippingAddress= $request->shippingAddress;
            $Vendor->contactPersonName= $request->contactPersonName;
            $Vendor->primaryContactNumber= $request->primaryContactNumber;
            $Vendor->secondaryContactNumber= $request->secondaryContactNumber;
            $Vendor->email= $request->email;
            $Vendor->remark= $request->remark;

            $Vendor->save();
            
            $response = [
                "message" => "Vendor Added Sucessfully!",
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
            $Vendor = Vendor::find($id);

            if(!$Vendor){
                throw new Exception("Vendor not found");
            }

            $Vendor->vendorCode= $request->vendorCode;
            $Vendor->gstNumber= $request->gstNumber;
            $Vendor->vendorName= $request->vendorName;
            $Vendor->billingAddress= $request->billingAddress;
            $Vendor->shippingAddress= $request->shippingAddress;
            $Vendor->contactPersonName= $request->contactPersonName;
            $Vendor->primaryContactNumber= $request->primaryContactNumber;
            $Vendor->secondaryContactNumber= $request->secondaryContactNumber;
            $Vendor->email= $request->email;
            $Vendor->remark= $request->remark;

            $Vendor->save();

            $response = [       
               "message" =>' Vendor Data Updated Successfully', 
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
            $Vendor = Vendor::find($id);
            if(!$Vendor){
                throw new Exception("Vendor not found");
            }else{
                $Vendor->delete();
                $response = [          
                    "message" => " Vendor Data Deleted Sucessfully!",
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

            $result = DB::table('Vendors')->get();
            if(!$result){
                throw new Exception("Vendor not found");
            }
            $response=[
              "message" => "Vendor List",
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
}
