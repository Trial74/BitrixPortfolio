<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $USER;

class EX_Notification {
    const SERVER_KEY = "///";
    const USER_KEY = '///';

    public function EX_setPush($title, $message, $token, $url='', $image = ''){

        $url_request = "https://fcm.googleapis.com/fcm/send";
        $API_KEY = self::SERVER_KEY;

        $request_body = array(
            "to" => $token,
            "notification" => array(
                "title"                 => $title,
                "body"                  => $message,
                'icon'                  => 'ic_notification',
                "color"                 => "#7b66fe",
                "notification_count"    => 1,
                "vibrate"	            => 1,
                "sound"		            => "default",
                "image"                 => $image,
                "badge"                 => "1"
            ),
            'data' => array(
                "title"                 => $title,
                "body"                  => $message,
                'show_notification'     => 'Y',
                'url'                   => $url,
                "image"                 => $image,
            ),
            "apns" => array(
                "payload" => array(
                    "aps" => array(
                        "badge" => "1",
                        "mutable-content" => true
                    ),
                ),
                "fcm_options" => array(
                    "image" => $image
                )
            ),
            "priority" => "high"
        );

        $fields = json_encode($request_body);
        $request_headers = array(
            'Content-Type: application/json',
            'Authorization: key=' . $API_KEY,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_request);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}?>