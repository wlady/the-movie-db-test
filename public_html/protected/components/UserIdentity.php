<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends MyUserIdentity
{
    public function __construct($api_key)
    {
        $this->api_key = $api_key;
        parent::__construct($api_key, '');
    }

    public function authenticate()
    {
        $output = Yii::app()->curl->get('https://api.themoviedb.org/3/authentication/guest_session/new?api_key=' . $this->api_key);
        $response = json_decode($output);
        if (is_object($response)) {
            if (isset($response->success)) {
                $this->errorCode = self::ERROR_NONE;
                TMDbSession::initSession($this->api_key, $response->guest_session_id);
            } else {
                $this->errorCode = self::ERROR_API_KEY_INVALID;
                $this->errorMessage = $response->status_message;
            }
        }

        return $this->errorCode == self::ERROR_NONE;
    }

}
