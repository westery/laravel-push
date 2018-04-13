<?php

/**
 * 阿里云App消息推送
 */

namespace Westery\LaravelPush\lib;




use AliyunOpenapi\Config;
use AliyunOpenapi\DefaultAcsClient;
use AliyunOpenapi\Profile\DefaultProfile;
use AliyunOpenapi\Push\Request\V20160801\PushRequest;

class Aliyun{


    private $ak_id;
    private $ak_secret;
    private $app_key;


    /**
     * Aliyun constructor.
     * @param $ak_id
     * @param $ak_secret
     * @param $app_key
     */
    public function __construct($ak_id,$ak_secret,$app_key){
        $this->ak_id = $ak_id;
        $this->ak_secret = $ak_secret;
        $this->app_key = $app_key;
        Config::load();
//        $profile = DefaultProfile::getProfile("cn-hangzhou", $this->AppKey, $this->AppSecret);
//        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", "Dysmsapi", "dysmsapi.aliyuncs.com");
//        $this->acsClient = new DefaultAcsClient($profile);
    }

    public function push($account,$title,$body,$config =[])
    {
        $push_type = isset($config['push_type'])?$config['push_type']:'MESSAGE';
        $notify_type = isset($config['notify_type'])?$config['notify_type']:'BOTH';
        $notification_bar_type = isset($config['notification_bar_type'])?$config['notification_bar_type']:1;
        $open_type = isset($config['open_type'])?$config['open_type']:'URL';
        $open_url = isset($config['open_url'])?$config['open_url']:'URL';
        $open_activity = isset($config['open_activity'])?$config['open_activity']:'com.ali.demo.OpenActivity';
        $music = isset($config['music'])?$config['music']:'default';
        $popup_activity = isset($config['popup_activity'])?$config['popup_activity']:'com.ali.demo.PopupActivity';
        $popup_title = isset($config['popup_title'])?$config['popup_title']:'title';
        $popup_body = isset($config['popup_body'])?$config['popup_body']:'body';
        $ext_parameters = isset($config['ext_parameters'])?$config['ext_parameters']:"{\"k1\":\"android\",\"k2\":\"v2\"}";
        //        Push
        $iClientProfile = DefaultProfile::getProfile("cn-hangzhou", $this->ak_id, $this->ak_secret);
        $client = new DefaultAcsClient($iClientProfile);
        $request = new PushRequest();
        // 推送目标
        $request->setAppKey($this->app_key);
        $request->setTarget("ACCOUNT"); //推送目标: DEVICE:推送给设备; ACCOUNT:推送给指定帐号,TAG:推送给自定义标签; ALL: 推送给全部
        $request->setTargetValue($account); //根据Target来设定，如Target=device, 则对应的值为 设备id1,设备id2. 多个值使用逗号分隔.(帐号与设备有一次最多100个的限制)
        $request->setDeviceType("ANDROID"); //设备类型 ANDROID iOS ALL.

        $request->setPushType($push_type); //消息类型 MESSAGE NOTICE
        $request->setTitle($title); // 消息的标题
        $request->setBody($body); // 消息的内容

        // 推送配置: Android
        $request->setAndroidNotifyType($notify_type);//通知的提醒方式 "VIBRATE" : 震动 "SOUND" : 声音 "BOTH" : 声音和震动 NONE : 静音
        $request->setAndroidNotificationBarType($notification_bar_type);//通知栏自定义样式0-100
        $request->setAndroidOpenType($open_type);//点击通知后动作 "APPLICATION" : 打开应用 "ACTIVITY" : 打开AndroidActivity "URL" : 打开URL "NONE" : 无跳转
        $request->setAndroidOpenUrl($open_url);//Android收到推送后打开对应的url,仅当AndroidOpenType="URL"有效
        $request->setAndroidActivity($open_activity);//设定通知打开的activity，仅当AndroidOpenType="Activity"有效
        $request->setAndroidMusic($music);//Android通知音乐
        $request->setAndroidPopupActivity($popup_activity);//设置该参数后启动辅助托管弹窗功能, 此处指定通知点击后跳转的Activity（辅助弹窗的前提条件：1. 集成第三方辅助通道；2. StoreOffline参数设为true
        $request->setAndroidPopupTitle($popup_title);
        $request->setAndroidPopupBody($popup_body);
        $request->setAndroidExtParameters($ext_parameters); // 设定android类型设备通知的扩展属性

        // 推送控制
        $expireTime = gmdate('Y-m-d\TH:i:s\Z', strtotime('+1 day'));//设置失效时间为1天
        $request->setExpireTime($expireTime);
        $request->setStoreOffline("true"); // 离线消息是否保存,若保存, 在推送时候，用户即使不在线，下一次上线则会收到
        $response = $client->getAcsResponse($request);
//        var_dump($response);exit;

        // 默认返回stdClass，通过返回值来判断发送成功与否
        if($response && $response->MessageId)
        {
            return ['error'=>0,'status'=>200,'message'=>'发送成功'];
        }else{
            return ['error'=>1,'status'=>422,'message'=>'发送失败','info'=>$response];
        }
    }




}

?>