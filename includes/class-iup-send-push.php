<?php

class Ionic_User_Send_Push {

    /**
     * @param string $text
     * @param array $userIds
     * @param array $options
     * @return WP_Error
     */
    public function sendPushNotification($text, array $userIds, array $options) {
        if (empty($text) || count($userIds) === 0 || empty($userIds[0])) {
            return new WP_Error( 'broke', __( "Missing text or user ids to send push notification!", "menu" ) );
        }

        if (empty($options['appId']) || empty($options['privateApiKey'])) {
            return new WP_Error( 'broke', __( "Please check basic settings and enter valid data!", "menu" ) );
        }

        $data = array(
            'user_ids' => $userIds,
            'notification' => array('alert' => $text),
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://push.ionic.io/api/v1/push');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_USERPWD, $options['privateApiKey'] . ":" );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-Ionic-Application-Id: ' . $options['appId']
        ));
        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        if ($result->result === 'error') {
            return new WP_Error( 'broke', __( $result->message, "menu" ) );
        }
    }

}