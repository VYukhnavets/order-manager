<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%merchant_users}}".
 *
 * @property integer $id
 * @property integer $app_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property integer $status
 * @property integer $createtime
 * @property integer $updatetime
 * @property integer $lastvisit
 * @property string $sess_id
 * @property string $activation_key
 *
 * @property Application $app
 */
class MerchantUsers extends \yii\db\ActiveRecord
{
    
    private $statusNames = array(2=>'Active', 0=>'Inactive',1=>'Unverified');
    private $roles = array(1=>'Supervisor',2=>'User');
    private $password_str;
    /*public $loyalty_events_enabled, $loyalty_promotions_enabled, $loyalty_activity_enabled, $loyalty_reward_enabled, $loyalty_reward_levels_enabled, $loyalty_game_enabled, $loyalty_feed_enabled, $ordering_android_enabled, $ordering_ios_enabled, $ordering_web_enabled, $call_center_enabled, $users_enabled, $stores_enabled, $loyalty_events_access, $loyalty_promotions_access, $loyalty_activity_access, $loyalty_reward_access, $loyalty_reward_levels_access, $loyalty_game_access, $loyalty_feed_access, $ordering_android_access, $ordering_ios_access, $ordering_web_access, $call_center_access, $users_access, $stores_access;*/
    
    public function init() {
        $this->setAttribute('role', 2);
        $this->setAttribute('status', 2);
        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%merchant_users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'first_name', 'last_name', 'email', 'status', 'createtime', 'role'], 'required'],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['app_id', 'status', 'createtime', 'updatetime', 'lastvisit', 'role'], 'integer'],
            [['loyalty_events_enabled', 'loyalty_promotions_enabled', 'loyalty_activity_enabled', 'loyalty_reward_enabled', 'loyalty_reward_levels_enabled', 'loyalty_game_enabled', 'loyalty_feed_enabled', 'ordering_android_enabled', 'ordering_ios_enabled', 'ordering_web_enabled', 'call_center_enabled', 'users_enabled', 'stores_enabled', 'loyalty_events_access', 'loyalty_promotions_access', 'loyalty_activity_access', 'loyalty_reward_access', 'loyalty_reward_levels_access', 'loyalty_game_access', 'loyalty_feed_access', 'ordering_android_access', 'ordering_ios_access', 'ordering_web_access', 'call_center_access', 'users_access', 'stores_access'], 'safe'],
            [['first_name', 'last_name', 'email', 'password', 'sess_id', 'activation_key'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'status' => 'Status',
            'createtime' => 'Createtime',
            'updatetime' => 'Updatetime',
            'lastvisit' => 'Lastvisit',
            'sess_id' => 'Sess ID',
            'activation_key' => 'Activation Key',
            'loyalty_events_enabled' => 'Events',
            'loyalty_promotions_enabled' => 'Promotions',
            'loyalty_activity_enabled' => 'Activity',
            'loyalty_reward_enabled' => 'Reward',
            'loyalty_reward_levels_enabled' => 'Reward Levels',
            'loyalty_game_enabled' => 'Game',
            'loyalty_feed_enabled' => 'Feed',
            'ordering_android_enabled' => 'Android',
            'ordering_ios_enabled' => 'iOS',
            'ordering_web_enabled' => 'Web',
            'call_center_enabled' => 'Call Center',
            'users_enabled' => 'Users',
            'stores_enabled' => 'Stores',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApp()
    {
        return $this->hasOne(Application::className(), ['id' => 'app_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions()
    {
        return $this->hasOne(MerchantUsersPermissions::className(), ['merchant_user_id' => 'id']);
    }
    
    public function getLoyaltyAccess(){
        if($this->permissions && $this->permissions->loyalty_events > 0) return true;
        if($this->permissions && $this->permissions->loyalty_promotions > 0) return true;
        if($this->permissions && $this->permissions->loyalty_activity > 0) return true;
        if($this->permissions && $this->permissions->loyalty_reward > 0) return true;
        if($this->permissions && $this->permissions->loyalty_reward_levels > 0) return true;
        if($this->permissions && $this->permissions->loyalty_game > 0) return true;
        if($this->permissions && $this->permissions->loyalty_feed > 0) return true;
        return false;
    }
    
    public function getLoyaltyAccessSummary(){
        if($this->loyaltyAccess){
            $return = ['class'=>'text-center', 'data-toggle'=>"popover", 'data-container'=>"body", 'data-html'=>"true", 'data-placement'=>"bottom", 'data-trigger'=>"click hover focus", 'data-content'=>"<strong>Loyalty:</strong>"];
            $tmp = [];
            if($this->permissions && $this->permissions->loyalty_events > 0) $tmp[] = 'Events';
            if($this->permissions && $this->permissions->loyalty_promotions > 0) $tmp[] = 'Promotions';
            if($this->permissions && $this->permissions->loyalty_activity > 0) $tmp[] = 'Activity';
            if($this->permissions && $this->permissions->loyalty_reward > 0) $tmp[] = 'Reward';
            if($this->permissions && $this->permissions->loyalty_reward_levels > 0) $tmp[] = 'Reward Levels';
            if($this->permissions && $this->permissions->loyalty_game > 0) $tmp[] = 'Game';
            if($this->permissions && $this->permissions->loyalty_feed > 0) $tmp[] = 'Feed';
            $return['data-content'] .= ' '.implode(', ', $tmp);
            return $return;
        }else{
            return ['class'=>'text-center'];
        }
    }
    public function getOrderingAccess(){
        if($this->permissions && $this->permissions->ordering_android > 0) return true;
        if($this->permissions && $this->permissions->ordering_ios > 0) return true;
        if($this->permissions && $this->permissions->ordering_web > 0) return true;
        return false;
    }
    public function getOrderingAccessSummary(){
        if($this->orderingAccess){
            $return = ['class'=>'text-center', 'data-toggle'=>"popover", 'data-container'=>"body", 'data-html'=>"true", 'data-placement'=>"bottom", 'data-trigger'=>"click hover focus", 'data-content'=>"<strong>Ordering:</strong>"];
            $tmp = [];
            if($this->permissions && $this->permissions->ordering_android > 0) $tmp[] = 'Android';
            if($this->permissions && $this->permissions->ordering_ios > 0) $tmp[] = 'iOS';
            if($this->permissions && $this->permissions->ordering_web > 0) $tmp[] = 'Web';
            $return['data-content'] .= ' '.implode(', ', $tmp);
            return $return;
        }else{
            return ['class'=>'text-center'];
        }
    }
    public function getCallCenterAccess(){
        if($this->permissions && $this->permissions->call_center > 0) return true;
        return false;
    }
    public function getUsersAccess(){
        if($this->permissions && $this->permissions->users > 0) return true;
        return false;
    }
    public function getStoresAccess(){
        if($this->permissions && $this->permissions->stores > 0) return true;
        return false;
    }
    
    public function __get($name) {
        if(strpos($name, '_enabled') !== false){
            $name = str_replace('_enabled', '', $name);
            $user_permissions = new MerchantUsersPermissions();
            $permissions = $user_permissions->getAttributes();
            if(in_array($name, array_keys($permissions))){
                if($this->permissions && $this->permissions->$name > 0) return true;
                else return false;
            }
        }
        if(strpos($name, '_access') !== false){
            $name = str_replace('_access', '', $name);
            $user_permissions = new MerchantUsersPermissions();
            $permissions = $user_permissions->getAttributes();
            if(in_array($name, array_keys($permissions))){
                if($this->permissions && $this->permissions->$name > 0) return $this->permissions->$name;
                else return 1;
            }
        }
        
        return parent::__get($name);
    }
    
    public function __set($name, $value) {
        if(strpos($name, '_enabled') !== false){
            $this->$name = $value;
            return true;
        }
        if(strpos($name, '_access') !== false){
            $this->$name = $value;
            return true;
        }
        return parent::__set($name, $value);
    }
    
    public function getName(){
        return $this->first_name.' '.$this->last_name;
    }
    
    public function getStatusText(){
        if(!empty($this->statusNames[$this->status])){
            return $this->statusNames[$this->status];
        }else{
            return NULL;
        }
    }
    
    public function getRoleText(){
        if(!empty($this->role) && !empty($this->roles[$this->role])) return $this->roles[$this->role];
        return NULL;
    }
    
    public static function getRoles(){
        $model = new MerchantUsers();
        return $model->roles;
    }
    
    public static function getStatuses(){
        $model = new MerchantUsers();
        unset($model->statusNames[1]);
        return $model->statusNames;
    }
    
    public function createUser(){
        if(!$this->validate()){
            return false;
        }
        if(!$this->save()){
            $this->addError('save', 'Error creating user.');
            return false;
        }
        return $this->doPermissions();
    }
    
    public function updateUser(){
        if(!$this->validate()){
            return false;
        }
        if(!$this->save()){
            $this->addError('save', 'Error updating user.');
            return false;
        }
        return $this->doPermissions();
    }
    
    private function doPermissions(){
        if(!empty($this->permissions)){
            $user_permissions = $this->permissions;
        }else{
            $user_permissions = new MerchantUsersPermissions();
            $user_permissions->setAttribute('merchant_user_id', $this->id);
        }
        $permissions = $user_permissions->getAttributes();
        foreach(array_keys($permissions) as $module){
            $enabled = $module.'_enabled';
            $access = $module.'_access';
            if(property_exists($this, $enabled) && property_exists($this, $access)){
                if($this->$enabled == 1){
                    $user_permissions->setAttribute($module, $this->$access);
                }else{
                    $user_permissions->setAttribute($module, 0);
                }
            }
        }
        if($user_permissions->save()){
            return true;
        }else{
            $this->addError('permissions', 'Can\'t save user permissions.');
        }
        return false;
    }
    
    public function beforeValidate() {
        if($this->isNewRecord){
            $this->createtime = time();
            $this->app_id = Yii::$app->session['app_id'];
            $Security = new \yii\base\Security();
            $this->activation_key = $Security->generateRandomString(45);
            $this->password_str = $Security->generateRandomString(8);
            $this->password = md5($this->password_str);
        }else{
            $this->updatetime = time();
        }
        return parent::beforeValidate();
    }
    
    public function afterSave($insert, $changedAttributes) {
        if($this->isNewRecord){
            //hardcoded dev server, change to merchant app domain in future
            Yii::$app->mailer->compose('newmerchantuser', ['user' => $this, 'merchant_app_domain'=>'dev.circleshout.com'])
                ->setFrom(Yii::$app->params['fromEmail'])
                ->setTo($this->email)
                ->setSubject('Welcome to Wahoo\'s Rewards Program')
                ->send();
        }
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public function getPassword_str(){
        return $this->password_str;
    }
    
    public function validatePassword($password){
        return $this->password === md5($password);
    }
    
    public function getUsername(){
        return $this->email;
    }
}