<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;


class UserController extends Controller
{
    public $token = true;
    public $responseJSON = null;

    // /**
    //  * Create a new AuthController instance.
    //  *
    //  * @return void
    //  */
    // public function __construct() {
    //     $this->middleware('jwt_verify', ['except' => ['login', 'signup']]);
    // }



    // REGISTER USER
    public function signup(Request $request)
    {
        echo ("REGISTER NEW USER \n");


        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:8|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if($validator->fails()){
            $responseJSON = json_encode([
                'status' => 400,
                'errors' => $validator->errors()->toJSON()
            ]);
            // return response()->json($validator->errors()->toJson(), 400);
            return $responseJSON;
        }

        $inputted = $request->all();

        $hashedPassword = Hash::make($inputted['password']);


        $newItem = new User();
        $newItem->username = $inputted['username'];
        $newItem->email = $inputted['email'];
        $newItem->password = $hashedPassword;

        $token = JWTAuth::fromUser($newItem);

        $itemJSON = $newItem->toJSON(JSON_PRETTY_PRINT);

        $responseJSON = json_encode([
            'status' => 201,
            'message' => 'SUCCESS SIGNING UP',
            'result' => $itemJSON,
            'token' => $token
        ]);


        $newItem->save();
        return $responseJSON;
    }


    // LOGIN USER
    public function login(Request $request)
    {
        echo ("LOGIN USER \n");
        $inputted = $request->only('email', 'password');
        // $jwt_token = null;

        try {

            if (! $token = JWTAuth::attempt($inputted)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $responseJSON = json_encode([
            'status' => 200,
            'message' => 'SUCCESS',
            'token' => $token
        ]);


        return $responseJSON;

        
    }


    // GET AUTHENTICATED USER
    public function getAuthenticatedUser() {
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }
        catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        }
        catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        $responseJSON = json_encode([
            'status' => 200,
            'message' => 'SUCCESS',
            'user' => $user
        ]);

        return $responseJSON;
    }


    // // VIEW ALL USERS
    public function index()
    {
        echo ("FIND ALL USERS @ USER CONTROLLER \n\n");

        $items = User::all();
        $itemsJSON = json_encode($items);

        $responseJSON = json_encode([
            'status' => 200,
            'message' => 'SUCCESS',
            'result' => $itemsJSON
        ]);

        return $responseJSON;
        
    }


    // VIEW ONE USER
    public function findOne($userId)
    {
        echo ("FIND ONE USER @ TASKCONTROLLER \n\n");

        $user = User::findOrFail((int)$userId);
        // echo ($user);
        if ($user) {
            // $userJSON = json_encode($user);

            $responseJSON = json_encode([
                'status' => 200,
                'message' => 'SUCCESS',
                // 'result' => $userJSON
                'result' => $user
            ]);

            return $responseJSON;

        } 
    }


    // // UPDATE TASK AS COMPLETE/INCOMPLETE
    public function updateProfile(Request $request, $userId)
    {
        echo ("EDIT USER PROFILE @ USERCONTROLLER \n\n");
        $user = User::findOrFail((int)$userId);

        if ($user) {

            print_r("THIS IS THE REQUESTED FIELD \n\n");
            $rawInputted = $request->input(); // WE NEED TO EXCLUDE EMPTY VALUES

            // CLEAN THE RAW INPUT FROM EMPTIES
            $cleanedInput = array();
            foreach($rawInputted as $x => $x_value) {
                if($x_value && $x_value != "") {
                    $newX = substr($x, 4);
                    $cleanedInput[$newX] = $x_value;
                }
            }

            // WE NOW UPDATE ENTRY
            foreach($cleanedInput as $x => $x_value) {
                $user->update([
                    $x => $x_value
                ]);
            }

            $user->save();

            $updUserJSON = $user->toJSON();

            $responseJSON = json_encode([
                'status' => 200,
                'message' => 'SUCCESS',
                'result' => $updUserJSON
            ]);

            return $responseJSON;

        }

        
    }



    // UPDATE TASK DESCRIPTION
    public function removeUser($userId)
    {
        echo ("DROP USER @ USERCONTROLLER \n");
        $user = User::findOrFail((int)$userId);
        $delUserJSON = $user->toJSON();

        if($user) {
            $user->delete();

            $responseJSON = json_encode([
                'status' => 200,
                'message' => 'SUCCESS',
                'result' => $delUserJSON
            ]);

            return $responseJSON;

        }

    }


}
