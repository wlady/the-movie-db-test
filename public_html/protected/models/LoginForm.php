<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
    public $api_key = null;
    public $rememberMe = 3600; // 1 hour

    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that api_key is required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // api_key is required
            array('api_key', 'required'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'api_key' => 'TMDb API Key',
        );
    }

    /**
     * Logs in the user using the given api_key and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->api_key);
            if (!$this->_identity->authenticate()) {
                $this->addError('api_key', $this->_identity->errorMessage);
            }
        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            Yii::app()->user->setState('api_key', $this->api_key);
            Yii::app()->user->login($this->_identity, $this->rememberMe);
            return true;
        } else {
            return false;
        }
    }
}
