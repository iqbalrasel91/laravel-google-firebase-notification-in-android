<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    public function __construct()
    {
       // parent::__construct();

        $this->Post = new Post;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $input = array_except($request->all(),array('_token'));

        $this->Post->AddData($input);

        $notification = \DB::table('api_users')->get();

        foreach ($notification as $key => $value) {
            $this->notification($value->token, $request->get('title'));
        }

        \Session::put('success','Post store and send notification successfully!!');

        return redirect()->route('post.index');
    }

    public function notification($token, $title)
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $token=$token;

        $notification = [
            'title' => $title,
            'sound' => true,
        ];

        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=AIzaSyAqH3pYx3mCn4nmB3EgRNpLqEtuDDQOcyk',
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);

        return true;
    }

    public function test()
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $token='diWhHpEdy1k:APA91bHfaE_zy4FUJ_GGDmO3XuJNz5qshyMeyjbIvvdLKI-DkR5rzhS00k9Hwc49yKzJLUraUPbu9-H-XOv8hbT-q-omtzXa8-uAv8Ewej52zO1gH0maKoGP4FLCu9FwVlLSpwBDC_3T';


        $notification = [
            'body' => 'this is test',
            'sound' => true,
        ];

        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=AAAAovEEJu4:APA91bFBbNvcMMKg5S2dR5TCdwuboyIpHEUiMtDo4dObeZoO5wqrm8rFGuhjo9Xzxb3Uf2kM764RcX5By-1K-Yy-gyKOMsQ2-14PE7aeBokBfGLTkJmJyxVAtvVVlxGAZExCRmtXVbV9',
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);


        dd($result);
    }
}