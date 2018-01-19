<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Spatie\Permission\Models\Role;
use App\Services\RoleService;
use App\Services\PermissionService;
use Validator;

trait NotificationTrait {

    public $successStatus = 200;
    /**
     * init api
     *
     * @return \Illuminate\Http\Response
     */   
    public function saveUserDevice(Request $request){

        $validator = Validator::make($request->all(), [
        'id' =>'required',
        'device_id'=>'required',
        ]);

         if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);            
        }  

         if($this->modelService->update($request))
        {
            return response()->json(['success'=>'success'], 200);
        }

        return response()->json(['error'=>'Server error'], 501);
    }

    public function sendToOne($user, $title, $message, $id){

        $url = 'https://fcm.googleapis.com/fcm/send';

        $token = $user->device_id;
        $data = array(
            'message' => $message,
            'theme' => $title
        );
		$fields = array(
			 'to' => $token,
			 'data' => $data,
             'id' => $id
			);
		$headers = array(
			'Authorization:key = AAAAu-IVaa4:APA91bG9dM3E57G7aZi4EeEloxj3dGo-R-Dup9YnzFFXTsNQMR0WTW006L2LmDgtGRU2e5byl_sSXZ0oh0LVAR6XHyVlX0a6QEhQsrJ9TYXO6SmkWwy5eLrQix1KsjHI-13o_sfkC_Lz',
			'Content-Type: application/json'
			);
	   $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
       $result = curl_exec($ch);           
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       echo $result;
       return $result;
    }

    public function sendToMany($users, $title, $message, $idToSend){

        $url = 'https://fcm.googleapis.com/fcm/send';

        $tokens = array();
        foreach ($users as $user)
        {
            array_push($tokens, $user->device_id);
        }
        $data = array(
            'message' => $message,
            'theme' => $title,
            'id' => $idToSend
        );
		$fields = array(
			 'registration_ids' => $tokens,
			 'data' => $data
			);
		$headers = array(
			'Authorization:key = AAAAu-IVaa4:APA91bG9dM3E57G7aZi4EeEloxj3dGo-R-Dup9YnzFFXTsNQMR0WTW006L2LmDgtGRU2e5byl_sSXZ0oh0LVAR6XHyVlX0a6QEhQsrJ9TYXO6SmkWwy5eLrQix1KsjHI-13o_sfkC_Lz',
			'Content-Type: application/json'
			);
            
	   $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
       $result = curl_exec($ch);           
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       echo $result;
       return $result;
    }

     public function sendToManyByid($usersIds, $title, $message, $idToSend){

         $url = 'https://fcm.googleapis.com/fcm/send';

        $tokens = array();
        foreach ($usersIds as $id)
        {
            $user = User::where('id', $id)->first();
            array_push($tokens, $user->device_id);
        }
        $data = array(
            'message' => $message,
            'theme' => $title,
            'id' => $idToSend
        );
		$fields = array(
			 'registration_ids' => $tokens,
			 'data' => $data
			);
		$headers = array(
			'Authorization:key = AAAAu-IVaa4:APA91bG9dM3E57G7aZi4EeEloxj3dGo-R-Dup9YnzFFXTsNQMR0WTW006L2LmDgtGRU2e5byl_sSXZ0oh0LVAR6XHyVlX0a6QEhQsrJ9TYXO6SmkWwy5eLrQix1KsjHI-13o_sfkC_Lz',
			'Content-Type: application/json'
			);
            
	   $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
       $result = curl_exec($ch);           
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       echo $result;
       return $result;
    }

    public function sendToAll($title ,$message, $idToSend){

        $url = 'https://fcm.googleapis.com/fcm/send';

        $data = array(
            'message' => $message,
            'theme' => $title,
            'id' => $idToSend
        );
		$fields = array(
			 'to' => '/topics/SZZ',
			 'data' => $data
			);
		$headers = array(
			'Authorization:key = AAAAu-IVaa4:APA91bG9dM3E57G7aZi4EeEloxj3dGo-R-Dup9YnzFFXTsNQMR0WTW006L2LmDgtGRU2e5byl_sSXZ0oh0LVAR6XHyVlX0a6QEhQsrJ9TYXO6SmkWwy5eLrQix1KsjHI-13o_sfkC_Lz ',
			'Content-Type: application/json'
			);
	   $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
       $result = curl_exec($ch);           
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       echo $result;
       return $result;
	}
	
    public function sendToTopic($topic, $title ,$message){
        $url = 'https://fcm.googleapis.com/fcm/send';
        $data = array(
            'message' => $message,
            'theme' => $title
        );
		$fields = array(
			 'to' => $topic,
			 'data' => $data
			);
		$headers = array(
			'Authorization:key = AAAAu-IVaa4:APA91bG9dM3E57G7aZi4EeEloxj3dGo-R-Dup9YnzFFXTsNQMR0WTW006L2LmDgtGRU2e5byl_sSXZ0oh0LVAR6XHyVlX0a6QEhQsrJ9TYXO6SmkWwy5eLrQix1KsjHI-13o_sfkC_Lz ',
			'Content-Type: application/json'
			);
	   $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
       $result = curl_exec($ch);           
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       echo $result;
       return $result;
    }

}