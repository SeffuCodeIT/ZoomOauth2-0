<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ZoomController extends Controller
{

    public function __construct()
    {

    }
    //++++++++++++++++++++++++++++++++++++++++++++++++
    //++++++++++++++++++++++++++++++++++++++++++++++++
//

    public function showMeetingForm()
    {
        return view('create-meeting');
    }
//    public function createMeeting(Request $request)
//    {
////        dd($request->all());
//        $validator = Validator::make($request->all(), [
////            'token' => 'required',
//            'topic' => 'required',
//            'start_time' => 'required',
//            'agenda' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            dd($validator->errors()->all());
//            return 'Meeting data not validated';
//        } else {
//
////            return 'Meeting data validated'. dd($request->all());
//            $meetingConfig = [
//                    'topic' => $request->input('topic'),
//                    'start_time' => $request->input('start_time'), // Add 'Z' for UTC time zone
//                    'agenda' => $request->input('agenda'),
//                    'jwtToken' => $request->input('_token'),
//                ];
//
////            dd($meetingConfig);
//            $get_zoom_details = $this->create_a_zoom_meeting($meetingConfig);
//
//                // Handle the response accordingly
//                if ($get_zoom_details && $get_zoom_details['success']) {
//                    // Meeting created successfully
//                    // You can redirect to a success page or return a success message
//                    return 'Meeting created successfully';
//                } else {
//                    // Failed to create the meeting
//                    // You can redirect back with an error message or return an error message
//                    return 'Failed to create the meeting';
//                }
//
//        }
//
//
//    }

        public function createMeeting(Request $request)
    {
//         dd($request->all());

        if (!$request->code) {
            $this->get_oauth_step_1();
        } else {
            $accessToken = $this->get_oauth_step_2($request->code);

            // Add the access token to the request data
            $request->merge(['token' => $accessToken]);

//            dd($request->all());

            // Validate the request data
//            $validator = Validator::make($request->all(), [
//                'token' => 'required',
//                'topic' => 'required',
//                'start_time' => 'required',
//                'agenda' => 'required',
//            ]);
            $validator = $request->validate([
                'token' => 'required',
                'topic' => 'required',
                'start_time' => 'required',
                'agenda' => 'required',
            ]);

            if ($validator->fails()) {
                 dd($validator->errors()->all());
                return 'Meeting data not validated';
            } else{

                // Your code to create the meeting goes here
                $meetingDetails = [
                    'topic' => $request->input('topic'),
                    'start_time' => $request->input('start_time') . 'Z', // Add 'Z' for UTC time zone
                    'agenda' => $request->input('agenda'),
                    'jwtToken' => $getToken['access_token'],
                ];

//        dd($meetingDetails);
                $get_zoom_details = $this->create_a_zoom_meeting($meetingDetails);

                // Handle the response accordingly
                if ($get_zoom_details && $get_zoom_details['success']) {
                    // Meeting created successfully
                    // You can redirect to a success page or return a success message
                    return 'Meeting created successfully';
                } else {
                    // Failed to create the meeting
                    // You can redirect back with an error message or return an error message
                    return 'Failed to create the meeting';
                }
            }


        }
    }

//    public function createMeeting(Request $request)
//    {
//        if (!$request->has('jwtToken')) {
//            // Access token not present, obtain it
//            if (!$request->has('code')) {
//                $this->get_oauth_step_1();
//            } else {
//                $getToken = $this->get_oauth_step_2($request->code);
//                // Store the access token in the request for future use
//                $request->merge(['jwtToken' => $getToken['access_token']]);
////                dd($getToken['access_token']);
//            }
//        }
//
//
//        // Meeting details are valid, proceed with creating the Zoom meeting
//        $meetingDetails = [
//            'topic' => $request->input('topic'),
//            'start_time' => $request->input('start_time') . 'Z', // Add 'Z' for UTC time zone
//            'agenda' => $request->input('agenda'),
//            'jwtToken' => $getToken['access_token'],
//        ];
//
////        dd($meetingDetails);
//        $get_zoom_details = $this->create_a_zoom_meeting($meetingDetails);
//
//        // Handle the response accordingly
//        if ($get_zoom_details && $get_zoom_details['success']) {
//            // Meeting created successfully
//            // You can redirect to a success page or return a success message
//            return 'Meeting created successfully';
//        } else {
//            // Failed to create the meeting
//            // You can redirect back with an error message or return an error message
//            return 'Failed to create the meeting';
//        }
//
//    }

    private function getAccessToken()
    {
        $tokenURL = 'https://zoom.us/oauth/token';
        $clientID = 'KrVX6l_MQ29VyutgQiMHA';
        $clientSecret = '9DWdAFSPBq4AkoIrX2vwpGCtUzamvtOR';

        $response = Http::post($tokenURL, [
            'grant_type' => 'client_credentials',
            'client_id' => $clientID,
            'client_secret' => $clientSecret,
        ]);

        if ($response->failed()) {
            return null;
        }

        return $response['access_token'];
    }


    //++++++++++++++++++++++++++++++++++++++++++++++++
    //++++++++++++++++++++++++++++++++++++++++++++++++
    private function get_oauth_step_1()
    {
        //++++++++++++++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++++++++++++++++
        $redirectURL  = 'http://192.168.43.241:8001/create-meeting';
        $authorizeURL = 'https://zoom.us/oauth/authorize';
        //++++++++++++++++++++++++++++++++++++++++++++++++++
        $clientID     = 'KrVX6l_MQ29VyutgQiMHA';
        $clientSecret = '9DWdAFSPBq4AkoIrX2vwpGCtUzamvtOR';
        //++++++++++++++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++++++++++++++++
        $authURL = $authorizeURL . '?client_id=' . $clientID . '&redirect_uri=' . $redirectURL . '&response_type=code&scope=&state=xyz';
        header('Location: ' . $authURL);
        exit;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++
    //++++++++++++++++++++++++++++++++++++++++++++++++
    private function get_oauth_step_2($code)
    {
        //++++++++++++++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++++++++++++++++
        $tokenURL    = 'https://zoom.us/oauth/token';
        $redirectURL = 'http://192.168.43.241:8001/create-meeting';
        //++++++++++++++++++++++++++++++++++++++++++++++++++
        $clientID     = 'KrVX6l_MQ29VyutgQiMHA';
        $clientSecret = '9DWdAFSPBq4AkoIrX2vwpGCtUzamvtOR';
        //++++++++++++++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++++++++++++++++
        $curl   = curl_init();
        $params = array(CURLOPT_URL => $tokenURL . "?"
            . "code=" . $code
            . "&grant_type=authorization_code"
            . "&client_id=" . $clientID
            . "&client_secret=" . $clientSecret
            . "&redirect_uri=" . $redirectURL,
            CURLOPT_RETURNTRANSFER      => true,
            CURLOPT_MAXREDIRS           => 10,
            CURLOPT_TIMEOUT             => 30,
            CURLOPT_HTTP_VERSION        => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST       => "POST",
            CURLOPT_NOBODY              => false,
            CURLOPT_HTTPHEADER          => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
                "accept: *",
            ),
        );
        curl_setopt_array($curl, $params);
        $response = curl_exec($curl);
        //++++++++++++++++++++++++++++++++++++++++++++++++++
        $err = curl_error($curl);
        curl_close($curl);
        //++++++++++++++++++++++++++++++++++++++++++++++++++
        $response = json_decode($response, true);
        return $response;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++
    //++++++++++++++++++++++++++++++++++++++++++++++++
    private function create_a_zoom_meeting($meetingConfig = [])
    {
        //++++++++++++++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++++++++++++++++
        $requestBody = [
            'topic'      => $meetingConfig['topic'] ?? 'New Meeting General Talk',
            'type'       => $meetingConfig['type'] ?? 2,
            'start_time' => $meetingConfig['start_time'] ?? date('Y-m-dTh:i:00') . 'Z',
            'duration'   => $meetingConfig['duration'] ?? 30,
            'password'   => $meetingConfig['password'] ?? mt_rand(),
            'timezone'   => 'Nairobi/Kenya',
            'agenda'     => $meetingConfig['agenda'] ?? 'Interview Meeting',
            'settings'   => [
                'host_video'        => false,
                'participant_video' => true,
                'cn_meeting'        => false,
                'in_meeting'        => false,
                'join_before_host'  => true,
                'mute_upon_entry'   => true,
                'watermark'         => false,
                'use_pmi'           => false,
                'approval_type'     => 0,
                'registration_type' => 0,
                'audio'             => 'voip',
                'auto_recording'    => 'none',
                'waiting_room'      => false,
            ],
        ];
        //++++++++++++++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++++++++++++++++
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // Skip SSL Verification
        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://api.zoom.us/v2/users/me/meetings",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => json_encode($requestBody),
            CURLOPT_HTTPHEADER     => array(
                "Authorization: Bearer " . $meetingConfig['jwtToken'],
                "Content-Type: application/json",
                "cache-control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);
        //++++++++++++++++++++++++++++++++++++++++++++++++
        if ($err) {
            return [
                'success'  => false,
                'msg'      => 'cURL Error #:' . $err,
                'response' => null,
            ];
        } else {
            return [
                'success'  => true,
                'msg'      => 'success',
                'response' => json_decode($response, true),
            ];
        }
    }
}
