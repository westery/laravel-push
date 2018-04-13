<?php

namespace Westery\LaravelPush;
use Westery\LaravelPush\lib\Aliyun;
/**
 * App推送 Push
 * Class Push
 * @package Westery\LaravelPush
 */
class Push
{

    protected $pushData ;

    protected $config = 'Aliyun';

    public function __construct($config)
    {
        $this->config = $config;
        $this->pushData = [
            'account' => '',
            'title' => 'title',
            'body' => 'body',
            'config' => []
        ];
    }

    /**
     * @param $account
     * @return $this
     */
    public function to($account)
    {
        $this->pushData['account'] = $account;

        return $this;
    }

    /**
     * @param $title
     * @return $this
     */
    public function title($title)
    {
        $this->pushData['title'] = $title;

        return $this;
    }

    /**
     * @param $body
     * @return $this
     */
    public function body($body)
    {
        $this->pushData['body'] = $body;
        return $this;
    }

    /**
     * @param array $config
     * @return $this
     */
    public function config($config=[])
    {
        $this->pushData['config'] = $config;
        return $this;
    }

    /**
     * @throws \Exception
     * @return mixed
     */
    public function send()
    {
        if($this->config === 'Aliyun'){
            $_config = config('push.agents.'.$this->config);
            //$ak_id,$ak_secret,$app_key
            $rest = new Aliyun($_config['accessKeyId'],
                $_config['accessKeySecret'],
                $_config['appKey']);
            return $rest->push($this->pushData['account'],$this->pushData['title'],$this->pushData['body'],$this->pushData['config']);
        } else{
            throw new \Exception('make sure you have choose a right agent');
        }
    }
}
