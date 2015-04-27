<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%merchant_users_permissions}}".
 *
 * @property integer $id
 * @property integer $merchant_user_id
 * @property integer $loyalty_events
 * @property integer $loyalty_promotions
 * @property integer $loyalty_activity
 * @property integer $loyalty_reward
 * @property integer $loyalty_reward_levels
 * @property integer $loyalty_game
 * @property integer $loyalty_feed
 * @property integer $ordering_android
 * @property integer $ordering_ios
 * @property integer $ordering_web
 * @property integer $call_center
 * @property integer $users
 * @property integer $stores
 *
 * @property MerchantUsers $merchantUser
 */
class MerchantUsersPermissions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%merchant_users_permissions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_user_id'], 'required'],
            [['merchant_user_id', 'loyalty_events', 'loyalty_promotions', 'loyalty_activity', 'loyalty_reward', 'loyalty_reward_levels', 'loyalty_game', 'loyalty_feed', 'ordering_android', 'ordering_ios', 'ordering_web', 'call_center', 'users', 'stores'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_user_id' => 'Merchant User ID',
            'loyalty_events' => 'Loyalty Events',
            'loyalty_promotions' => 'Loyalty Promotions',
            'loyalty_activity' => 'Loyalty Activity',
            'loyalty_reward' => 'Loyalty Reward',
            'loyalty_reward_levels' => 'Loyalty Reward Levels',
            'loyalty_game' => 'Loyalty Game',
            'loyalty_feed' => 'Loyalty Feed',
            'ordering_android' => 'Ordering Android',
            'ordering_ios' => 'Ordering iOS',
            'ordering_web' => 'Ordering Web',
            'call_center' => 'Call Center',
            'users' => 'Users',
            'stores' => 'Stores',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchantUser()
    {
        return $this->hasOne(MerchantUsers::className(), ['id' => 'merchant_user_id']);
    }
    
    public static function getAccessLevels(){
        return [1=>'Read Only', 2=>'Write'];
    }
    
    public static function getAccessLevelText($level){
        $levels = self::getAccessLevels();
        if(!empty($levels[$level])){
            return $levels[$level];
        }
        return NULL;
    }
    
}