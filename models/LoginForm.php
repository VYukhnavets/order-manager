<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $old_password;
    public $new_password;
    public $confirm_new_password;
    public $rememberMe = false;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'new_password', 'confirm_new_password', 'email', 'old_password'], 'required'],
            ['confirm_new_password', 'compare', 'compareAttribute' => 'new_password'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            [['password', 'old_password'], 'validatePassword'],
            ['email', 'validateEmailForgotPwd'],
        ];
    }
    
    public function scenarios()
    {
        return [
            // on signup allow mass assignment of username
            'login' => ['username', 'password', 'rememberMe'],
            'forgotpassword' => ['email'],
            'changepassword' => ['new_password', 'confirm_new_password'],
            'switchpassword' => ['new_password', 'confirm_new_password', 'old_password'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if($this->scenario == 'switchpassword'){
                $this->password = $this->old_password;
                $this->_user = \Yii::$app->user->identity;
            }
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }
    
    /**
     * Validates email during password restore.
     * This method serves as the inline validation for email.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateEmailForgotPwd($attribute, $params){
        if (!$this->hasErrors()) {
            $user = Authorization::findByEmail($this->email);
            if (!$user){
                $this->addError($attribute, 'User with this email can\'t be found.');
            }else{
                $this->_user = $user;
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Authorization::findByUsername($this->username);
        }

        return $this->_user;
    }
    
    /**
     * Creates new activation key for password reset.
     * @return boolean whether key is created successfully
     */
    public function resetPwd(){
        if ($this->validate()) {
            if($this->_user->generateActivationKey()){
                Yii::$app->mailer->compose('restorepwd', ['user' => $this->_user])
                ->setFrom(Yii::$app->params['fromEmail'])
                ->setTo($this->_user->email)
                ->setSubject('Restore password')
                ->send();
                return true;
            }else{
                $this->addError('resetpwd', 'Can\'t generate new activation key.');
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * Saves new password.
     * @return boolean whether password is changes successfully
     */
    public function newPwd(){
        if ($this->validate()) {
            if($this->scenario == 'switchpassword') $this->_user = \Yii::$app->user->identity;
            if($this->_user->updatePassword($this->new_password)){
                return true;
            }else{
                $this->addError('resetpwd', 'Can\'t update password.');
                return false;
            }
        } else {
            return false;
        }
    }
    
    public function checkActivationKey(){
        $key = Yii::$app->request->get('key');
        if(!$key){
            $this->addError('activation_key', 'Empty activation key.');
            return false;
        }
        $this->_user = Authorization::findByActivationKey($key);
        if(!$this->_user){
            $this->addError('activation_key', 'Wrong activation key.');
            return false;
        }
        $max_expiration = 3600*24;
        if((time() - $this->_user->activation_key_creation_time) > $max_expiration){
            $this->addError('activation_key', 'Activation key expired.');
            return false;
        }
        return true;
    }
    
    public function attributeLabels() {
        return \yii\helpers\ArrayHelper::merge(['username'=>'Email'], parent::attributeLabels());
    }
}
