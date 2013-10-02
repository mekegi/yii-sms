<?php
/**
 * @since 12.07.13 17:19
 * @author Arsen Abdusalamov
 */

Yii::import('ext.sms.*');
Yii::import('ext.sms.gate.*');
Yii::import('ext.sms.behavior.*');

class Sender extends CApplicationComponent
{
    const DEFAULT_GATE = 'SmsRu';
    const DUMMY_GATE = 'Dummy';

    const EVENT_SEND = 'onSendSms';
    
    public $gate = self::DEFAULT_GATE;
    public $options = array();

    /**
     *
     * @var type
     */
    public $debugPhone;

    /**
     *
     * @var AbstractSmsGate
     */
    protected $gateObj = null;

    /**
     * @since 12.07.13 16:40
     * @author Arsen Abdusalamov
     * @return AbstractSmsGate
     */
    protected function getGateObj()
    {
        assert(!empty($this->gate));
        if(!$this->gateObj) {
            if (class_exists($this->gate)) {
                $this->gateObj = new $this->gate;
            }
            else {
                throw new CException("undefined sms gate [{$this->gate}]");
            }
            $this->gateObj->setOptions($this->options);
        }
        assert($this->gateObj instanceof AbstractSmsGate);
        return $this->gateObj;
    }

    /**
     * Convert phone to canonical ( +7(987)-111-11-11 => 79871111111 )
     * @since 19.07.13 13:09
     * @author Arsen Abdusalamov
     * @param string $phone
     * @return string
     */
    public function canonicalPhoneNonStatic($phone)
    {
        return self::canonicalPhone($phone);
    }

    /**
     * Convert phone to canonical ( +7(987)-111-11-11 => 79871111111 )
     * @since 24.09.12 15:46
     * @author Arsen Abdusalamov
     * @param string $phone
     * @return string
     */
    public static function canonicalPhone($phone)
    {
        return AbstractSmsGate::canonicalPhone($phone);
    }

    /**
     * Send sms to $phone
     * @since 24.09.12 15:46
     * @author Arsen Abdusalamov
     * @param string $phone
     * @param string $text
     * @return bool
     */
    public function send($phone, $text, $from, $timeToSend = null, $addInfo = array())
    {
        $result = $this->getGateObj()->send($this->debugPhone ?: $phone, $text, $from, $timeToSend);

        if ($this->hasEventHandler(self::EVENT_SEND)) {
            $event = new CEvent($this, [
                'phone' => $phone,
                'text' => $text,
                'from' => $from,
                'timeToSend' => $timeToSend,
                'addInfo' => $addInfo,
            ]);
            $this->raiseEvent(self::EVENT_SEND, $event);
        }
        return $result;
    }

    protected function onSendSms()
    {
    }
}