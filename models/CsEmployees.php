<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cs_employees}}".
 *
 * @property integer $id
 * @property string $username
 * @property integer $status
 * @property string $email
 * @property string $social_security_number
 * @property integer $birthday
 * @property string $phone
 * @property string $phone_ext
 * @property integer $createtime
 * @property integer $updatetime
 * @property string $password
 */
class CsEmployees extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $birthday_month;
    public $birthday_date;
    public $birthday_year;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cs_employees}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'birthday', 'createtime', 'updatetime', 'first_name', 'last_name', 'phone', 'social_security_number', 'role', 'status', ], 'required'],
            [['status', 'birthday', 'createtime', 'updatetime', 'role', 'birthday_date', 'birthday_month', 'birthday_year'], 'integer'],
            [['username', 'email', 'social_security_number'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 50],
            [['email'], 'email'],
            [['social_security_number'], 'string', 'max' => 11],
            [['phone'], 'string', 'max' => 12],
            [['phone_ext'], 'string', 'max' => 10],
            [['phone'], 'app\components\PhoneValidator'],
            [['social_security_number'], 'app\components\SSNValidator'],
        ];
    }
    
    public function beforeValidate() {
        if($this->isNewRecord){
            $this->username = $this->email;
            $this->createtime = time();
            $this->updatetime = 0;
        }else{
            $this->updatetime = time();
        }
        if(is_array($this->phone)){
            $this->phone = implode('-', $this->phone);
            $this->phone = trim($this->phone);
            $this->phone = trim($this->phone, '-');
        }
        if(is_array($this->social_security_number)){
            $this->social_security_number = implode('-', $this->social_security_number);
            $this->social_security_number = trim($this->social_security_number);
            $this->social_security_number = trim($this->social_security_number, '-');
        }
        if(!empty($this->birthday_date) && !empty($this->birthday_month) && !empty($this->birthday_year) ){
            $this->birthday = mktime(0, 0, 0, $this->birthday_month, $this->birthday_date, $this->birthday_year);
        }
        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'status' => 'Status',
            'email' => 'Email',
            'social_security_number' => 'Social Security #',
            'birthday' => 'Birthday',
            'phone' => 'Phone',
            'phone_ext' => 'Phone Ext',
            'createtime' => 'Createtime',
            'updatetime' => 'Updatetime',
            'password' => 'Password',
        ];
    }
    
    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
    
        /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return CsEmployees::find()->where(['username'=>$username])->one();
    }
    
     /**
     * Finds user by email
     *
     * @param  string      $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return CsEmployees::find()->where(['email'=>$email])->one();
    }
    
     /**
     * Finds user by activation key
     *
     * @param  string      $key
     * @return static|null
     */
    public static function findByActivationKey($key)
    {
        return CsEmployees::find()->where(['activation_key'=>$key])->one();
    }

    
    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return '';
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
     /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($this->email.$password);
    }
    
    public function generateActivationKey(){
        $Security = new \yii\base\Security;
        $this->activation_key = $Security->generateRandomString(50);
        $this->activation_key_creation_time = time();
        return $this->save(false);
    }
    
    public function updatePassword($password){
        $Security = new \yii\base\Security;
        $this->password = md5($this->email.$password);
        return $this->save(false);
    }
    
    public static function getStatuses(){
        return [
            0=>'Inactive',
            1=>'Active',
        ];
    }
    
    public static function getRoles(){
        return [
            1=>'Superadmin',
        ];
    }
    
    public function getName(){
        return $this->first_name.' '.$this->last_name.'('.$this->email.')';
    }
    
    public function getRoleText(){
        $roles = self::getRoles();
        if(!empty($roles[$this->role])) return $roles[$this->role];
        else return NULL;
    }
    
    public function getStatusText(){
        $statuses = self::getStatuses();
        if(!empty($statuses[$this->status])) return $statuses[$this->status];
        else return NULL;
    }
    
    public function getApplications(){
        return $this->hasMany(CsEmployeesToApp::className(), ['employee_id' => 'id']);
    }
    
    public function getApplicationsSummary(){
        $result = [];
        if(!empty($this->applications)){
            foreach($this->applications as $app){
               $result[] = $app->app->name;
            }
        }
        return $result;
    }
    
    public function getApplicationsIds(){
        $result = [];
        if(!empty($this->applications)){
            foreach($this->applications as $app){
               $result[] = $app->app->id;
            }
        }
        return $result;
    }
    
    public function getBirthday_month(){
        if(!empty($this->birthday)) return date('n', $this->birthday);
        else return NULL;
    }
    
    public function getBirthday_date(){
        if(!empty($this->birthday)) return date('j', $this->birthday);
        else return NULL;
    }
    
    public function getBirthday_year(){
        if(!empty($this->birthday)) return date('Y', $this->birthday);
        else return NULL;
    }
    
    public function createUser($applications = array()){
        if($this->validate()){
            if(!is_array($applications) || count($applications) == 0){
                $this->addError('merchants', 'Select at least 1 merchant.');
                return false;
            }
            if($this->save()){
                foreach($applications as $app_id){
                    $model = new CsEmployeesToApp();
                    $model->setAttributes(['employee_id'=>$this->id, 'app_id'=>$app_id]);
                    if(!$model->save()){
                        $this->addError('merchant_'.$app_id, 'Can\'t save permissions to merchant ID '.$app_id.'.');
                        $this->delete();
                        return false;
                    }
                }
            }else{
                $this->addError('user', 'Error creating user.');
                return false;
            }
        }
        return !$this->hasErrors();
    }
    
    public function updateUser($applications = array()){
        if($this->validate()){
            if(!is_array($applications) || count($applications) == 0){
                $this->addError('merchants', 'Select at least 1 merchant.');
                return false;
            }
            $this->save();
            $model = new CsEmployeesToApp();
            $model->deleteAll(['employee_id'=>$this->id]);
            foreach($applications as $app_id){
                $model = new CsEmployeesToApp();
                $model->setAttributes(['employee_id'=>$this->id, 'app_id'=>$app_id]);
                if(!$model->save()){
                    $this->addError('merchant_'.$app_id, 'Can\'t save permissions to merchant ID '.$app_id.'.');
                    return false;
                }
            }
        }
        return !$this->hasErrors();
    }
}
