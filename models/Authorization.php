<?php

namespace app\models;

class Authorization extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $authKey;
    private $_user = NULL;

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        $that = new self;
        $user = MerchantUsers::find()->where(['email'=>$id])->one();
        if($user){
            $that->_user = $user;
            return $that;
        }
        $user = CsEmployees::find()->where(['email'=>$id])->one();
        if($user){
            $that->_user = $user;
            return $that;
        }
        return NULL;
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $that = new self;
        $user = MerchantUsers::find()->where(['access_token' => $token])->one();
        if($user){
            $that->_user = $user;
            return $that;
        }
        $user = CsEmployees::find()->where(['access_token' => $token])->one();
        if($user){
            $that->_user = $user;
            return $that;
        }
        return NULL;
    }
    
        /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $that = new self;
        $user = MerchantUsers::find()->where(['email'=>$username])->one();
        if($user){
            $that->_user = $user;
            return $that;
        }
        $user = CsEmployees::find()->where(['username'=>$username])->one();
        if($user){
            $that->_user = $user;
            return $that;
        }
        return NULL;
    }
    
     /**
     * Finds user by email
     *
     * @param  string      $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        $that = new self;
        $user = MerchantUsers::find()->where(['email'=>$email])->one();
        if($user){
            $that->_user = $user;
            return $that;
        }
        $user = CsEmployees::find()->where(['email'=>$email])->one();
        if($user){
            $that->_user = $user;
            return $that;
        }
        return NULL;
    }
    
     /**
     * Finds user by activation key
     *
     * @param  string      $key
     * @return static|null
     */
    public static function findByActivationKey($key)
    {
        $that = new self;
        $user = MerchantUsers::find()->where(['activation_key'=>$key])->one();
        if($user){
            $that->_user = $user;
            return $that;
        }
        $user = CsEmployees::find()->where(['activation_key'=>$key])->one();
        if($user){
            $that->_user = $user;
            return $that;
        }
        return NULL;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function __call($name, $params) {
        if(!method_exists($this, $name)){
            if(method_exists($this->_user, $name)){
                return call_user_func_array(array(&$this->_user, $name),$params);
            }
        }
        return parent::__call($name, $params);
    }
    
    public function __get($name) {
        if(!property_exists($this, $name)){
            if($this->_user->hasAttribute($name) || method_exists($this->_user, 'get'.ucfirst($name))){
                return $this->_user->{$name};
            }
        }
        return parent::__get($name);
    }
    
    public function __set($name, $value) {
        if(!property_exists($this, $name)){
            if($this->_user->hasAttribute($name)){
                $this->_user->{$name} = $value;
            }
        }
        parent::__set($name, $value);
    }
}
