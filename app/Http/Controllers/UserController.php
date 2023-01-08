<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    public function store(Request $request)
    {
        try{
            $users = new User;
            $users->userRole= $request->userRole;
            $users->employeeId= $request->employeeId;
            $users->email= $request->email;
            $users->phone= $request->phone;
            $users->fullName= $request->fullName;
            $users->password= $request->password;

            $users->save();
            $response = [
                "message" => "Data Added Sucessfully!",
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
            $users = User::find($id);
            if(!$users){
                throw new Exception("user not found");
            }
            
            $users->userRole= $request->userRole;
            $users->employeeId= $request->employeeId;
            $users->email= $request->email;
            $users->phone= $request->phone;
            $users->fullName= $request->fullName;
            $users->password= $request->password;

            $users->update();
           
            
            $response = [       
               "message" => 'Data Updated Successfully', 
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
            $users = User::find($id);
            if(!$users){
                throw new Exception("user not found");
            }else{
                $users->delete();
                $response = [          
                    "message" => $users->fullName. " user Deleted Sucessfully!",
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


    public function login(Request $request)
    {   
        $email = $request->email;
        $password = $request->password;
        $data = DB::table('users')->where('email','=',$email)->where('password','=',$password)->get();


        $users = User::where(['email' => $request->email])->first();
       
        if(!$users){    

            $response = [
                'error' => 'Entered email has not been registered. Please enter the registered email id',
                "status" => 401
            ];
            $status=401;
            
            }elseif($users['blocked']){

                $response = [
                    'message'=>"User is blocked, please contact admin",
                ];
                $status = 403; 
                            
            }elseif(!$users  = User::where(['password' => $request->password])->first()){  
                
                $response = [
                    'error' => 'Entered password is invalid',
                    "status" => 401
                ];
                $status=401;
            
            }elseif(count($data)<=0){

            $response = [
                'error' => 'Entered email and password is invalid',
                    "status" => 401
                ];
            $status=401;  

        }elseif(count($data)>0){
                    
            $users = User::where('email', $request['email'])->firstOrFail();
            $token = $users->createToken('auth_token')->plainTextToken;
            // $response = [
            //     'userDetails' =>[ 
            //         'id' =>$users->id,
            //         'username' => $users->fullName,
            //         'email'=>$users->email,
            //         'userRole'=>$users->userRole
            //     ],
            //     'access_token' => $token, 
            // ];
            // $status = 200;   
            
            $response = [
                'userDetails'=>[
                    'id' =>$users->id,
                    'username' => $users->fullName,
                    'email'=>$users->email,
                    'userRole'=>$users->userRole,
                    'forcePasswordReset'=>0,
                    'secondLevelAuthorization'=>false
                ],
                'intervalDetails'=>[
                    'alertLogInterval'=>null,
                    'deviceLogInterval'=>null,
                    'sensorLogInterval'=>null,
                    'periodicBackupInterval'=>null,
                    'dataRetentionPeriodInterval'=>null,
                    'expireDateReminder'=>null
                ],
                'locationDetails'=>[
                        'location_id'=>null,
                        'branch_id'=>null,
                        'facility_id'=>null,
                        'building_id'=>null
                ],
                'user_token'=>$token,
                'lastLoginActivity'=>null, 
            ];
            $status = 201;
        }

        return response($response, $status);
    }

    public function showData()
    {
        try{
            $user = User::all();

            if(!$user){
                throw new Exception("Data not found");
            }else{   
                $response = [
                    'data' => $user,
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
