<?php

/**
 * Created by PhpStorm.
 * User: wlady
 * Date: 01.06.16
 * Time: 13:26
 */
class TMDbSession
{

    public static function getApiKey()
    {
        return Yii::app()->user->getState('tmdb_api_key');
    }

    public static function getSessionID()
    {
        return Yii::app()->user->getState('tmdb_session_id');
    }

    public static function getConfigurations()
    {
        return Yii::app()->user->getState('tmdb_configurations');
    }

    public static function initSession($api_key, $sessionID)
    {
        $output = Yii::app()->curl->get('https://api.themoviedb.org/3/configuration?api_key='.$api_key);
        Yii::app()->user->setState('tmdb_api_key', $api_key);
        Yii::app()->user->setState('tmdb_session_id', $sessionID);
        Yii::app()->user->setState('tmdb_configurations', json_decode($output));
    }
}
