<?php

/**
 * Created by PhpStorm.
 * User: wlady
 * Date: 01.06.16
 * Time: 12:16
 */
class MyUserIdentity extends CUserIdentity
{
    const ERROR_API_KEY_INVALID = 7;

    public $api_key = null;
}