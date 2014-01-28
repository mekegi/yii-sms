yii-sms
=======

Yii extension for sms

Installation
------------
add to composer.json

    "require": {
        ...
        "mekegi/yii-sms": "@dev",
        ...
    },
    "repositories": [
        ...
        {
            "type": "git",
            "url": "http://github.com/mekegi/yii-sms"
        }
        ...
    ],

Config
------
add to config.php

    'components' => [

        'sms' => [
            'class' => 'ext.sms.Sender',
            'gate' => 'Pilot',// available sms gates ['Dummy', 'SmsRu', 'Pilot']
            'options' => ['api_key' => 'you-api-key-for-sms-gate',],

            // if you need log sms send add this
            'behaviors' => [
                'log' => [
                    'class' => 'SmsDebugBehavior',
                    'filePath' => '/tmp/somefile.log',
                ],
            ],
            // for debuggung, if set debugPhone - all sms send to this phone
            'debugPhone' => '791234567788',
        ],

Usage
-----
    try {
        Yii::app()->sms->send('+7912-345-66-77', 'hello how are you', 'fantomas');
        Yii::app()->sms->send('89123456677', 'привет как дела', 'фантомас');
        Yii::app()->sms->send('9123456677', 'mix микс', 'фантомас-fantomas');
    }
    // if sending failed, throws SmsException with message from gate error response
    catch (SmsException $e) {
        echo $e->getMessage();
    }
