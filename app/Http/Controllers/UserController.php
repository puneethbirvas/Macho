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
            $users->emailId= $request->emailId;
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
                "error" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406;             
        }

        return response($response,$status);
    }
     
    //Default EmpID
    public function empId()
    {
        $last = DB::table('users')->latest('id')->first();
        if(!$last){
           $emp = "1";
        }else{
            $emp = $last->id + 1;
        }
        $get = "emp-".$emp;

        $response = [
            'success' => true,
            'data' =>  $get,
            'status' => 201
        ];
        $status = 201;   

        return Response($response,$status);
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
            $users->emailId= $request->emailId;
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
                "error" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406; 
        }

        return response($response,$status);
    } 

 
    public function loginUser(Request $request)
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
                            
            }elseif(!$users  = Users::where(['password' => $request->password])->first()){  
                
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
                    
            $users = users::where('email', $request['email'])->firstOrFail();
            $token = $users->createToken('auth_token')->plainTextToken;
            $response = [
                'userDetails' =>[ 
                    'id' =>$users->id,
                    'username' => $users->user_name,
                    'email'=>$users->email,
                    'userRole'=>$users->userType
                ],
                'access_token' => $token, 
            ];
            $status = 200;    
                        
        }
        return response($response, $status);
    }

    public function login(Request $request){             
        
        $data = $request->validate([
            'email' => 'required|string|max:191',
            'password' => 'required|string'
        ]);             
        
        $user = User::where('email', $data['email'])->first(); 

      
        if(!$user){    
            $response = [
                'error' => 'Entered email has not been registered. Please enter the registered email id'
            ];
            $status = 404;   
            //throw new CustomException("Entered email has not been registered. Please enter the registered email id");
            // abort(404);
        }
        else{
            if($user['blocked']){
                $response = [
                    'message'=>"User is blocked, please contact admin",
                    'user'=>$user->email
                ];
                $status = 403;                
            }
            elseif(!Hash::check($data['password'], $user->password)){        
                
                /**
                 * checking the count user of login attempts
                 * blocking the user if count exceeds
                 */

                $user = User::where('email',$data['email'])->first();                    
                
                $fail_attempts_count = $user->login_fail_attempt;
                if($fail_attempts_count == 4){
                    $user->blocked = 1;
                    $user->update();
                }
                else{
                    $user->login_fail_attempt = $fail_attempts_count + 1;
                    $user->update();
                }            
                $response = [
                    'message' => 'Invalid credentials',
                    'username' =>$user->email,
                    'fail_attempts' => $user->login_fail_attempt
                ];
                $status = 401;                 
                
            }   
            else{              
                
                $sec_level_auth = $user->sec_level_auth;
                
                $userLog = new userLog();
                $userLog->userId =$user->name;
                $userLog->userEmail =$user->email;
                $userLog->companyCode =$user->companyCode;
                $userLog->action = "LoggedIn";
                $userLog->save();
                
                
                $logoPath = "";
                if($user->user_role == "superAdmin"){
                    $users = User::where('companyCode', $user->companyCode)->first();  
                    $companyName = $users->name;
                    $logoPath = $users->companyLogo;
                    $alertLogInterval = "";
                    $deviceLogInterval = "";
                    $sensorLogInterval = "";
                    $periodicBackupInterval = "";
                    $dataRetentionPeriodInterval = "";
                    $expireDateReminder="";
                    
                    
                }else{
                    $customer = Customer::where('customerId', $user->companyCode)->first();  
                    $companyName = $customer->customerName;
                    $logoPath = $customer->customerLogo;
                    $alertLogInterval = $customer->alertLogInterval;
                    $deviceLogInterval = $customer->deviceLogInterval;
                    $sensorLogInterval = $customer->sensorLogInterval; 
                    $periodicBackupInterval = $customer->periodicBackupInterval;
                    $dataRetentionPeriodInterval = $customer->dataRetentionPeriodInterval;
                    $expireDateReminder = $customer->expireDateReminder;
                    
                    
                }
                
                
                if($sec_level_auth == 0){
                    $user_feature = "false";
                    $token = $user->createToken($user->email)->plainTextToken;    
                   
                    $user->login_fail_attempt = 0;
                    $user->last_login_ativity = $this->current_time;
                    $user->update(); 
   
                    
                    $response = [
                        'userDetails'=>[
                            // 'email'=>$this->hide_email($user->email),
                            'emailId'=>$user->email,
                            'secondLevelAuthorization'=>$user_feature,
                            'userName'=>$user->name,
                            'userRole'=>$user->user_role,
                            'companyCode'=>$user->companyCode,
                            'companyName'=>$companyName,
                            'companyLogo'=>$logoPath,
                            'forcePasswordReset'=>$user->changePassword
                        ],
                        'intervalDetails'=>[
                            'alertLogInterval'=>$alertLogInterval,
                            'deviceLogInterval'=>$deviceLogInterval,
                            'sensorLogInterval'=>$sensorLogInterval,
                            'periodicBackupInterval'=>$periodicBackupInterval,
                            'dataRetentionPeriodInterval'=>$dataRetentionPeriodInterval,
                            'expireDateReminder'=>$expireDateReminder
                            
                        ],
                        'locationDetails'=>[
                                'location_id'=>$user->location_id,
                                'branch_id'=>$user->branch_id,
                                'facility_id'=>$user->facility_id,
                                'building_id'=>$user->building_id
                        ],
                        
                        'user_token'=>$token,
                        'lastLoginActivity'=>$this->current_time, 
                    ];
                    $status = 201;
                }
                else{
                    $user_feature = "true";
                    $token = $user->createToken($user->email)->plainTextToken;   

                    $user->login_fail_attempt = 0;
                    $user->last_login_ativity = $this->current_time;
                    $user->update(); 

                    $response = [
                        'userDetails'=>[
                            // 'email'=>$this->hide_email($user->email),
                            'emailId'=>$user->email,
                            'secondLevelAuthorization'=>$user_feature,
                            'userName'=>$user->name,
                            'userRole'=>$user->user_role,
                            'companyCode'=>$user->companyCode,
                            'companyName'=>$companyName,
                            'companyLogo'=>$logoPath,
                            'forcePasswordReset'=>$user->changePassword
                        ],
                        'intervalDetails'=>[
                            'alertLogInterval'=>$alertLogInterval,
                            'deviceLogInterval'=>$deviceLogInterval,
                            'sensorLogInterval'=>$sensorLogInterval,
                            'periodicBackupInterval'=>$periodicBackupInterval,
                            'dataRetentionPeriodInterval'=>$dataRetentionPeriodInterval,
                            'expireDateReminder'=>$expireDateReminder
                        ],
                        'locationDetails'=>[
                                'location_id'=>$user->location_id,
                                'branch_id'=>$user->branch_id,
                                'facility_id'=>$user->facility_id,
                                'building_id'=>$user->building_id
                        ],
                        'user_token'=>$token,
                        'lastLoginActivity'=>$this->current_time, 
                    ];
                    $status = 201;
                }               
            }
        }          
        return response($response,$status);
    } 

    public function logout(Request $request) 
    {
        try{
           if ($request->user()) { 
                $request->user()->tokens()->delete();
            }
            $response = [          
                "message" =>  " user Logout Sucessfully!",
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

    public function block($id)
    {
        try{
            $users = Users::find($id);
            if(!$users){
                throw new Exception("user not found");
            }else{
                $users->update(['blocked' => true]);
                $response = [
                    'message'=>"User is blocked Sucessfully!",
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
            $result = DB::table('users')
                ->join('departments','departments.id','=','users.department')
                ->select('users.*','departments.department_name as department','departments.id as departmentId')
                ->get();
            if(!$result){

             throw new Exception("user not found");
            }

            $response=[
                "message" => "Users List",
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
