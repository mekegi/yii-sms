<?php

/**
 * @since 02.10.13 12:05
 * @author Arsen Abdusalamov
 */

include __DIR__.DIRECTORY_SEPARATOR.'sms-pilot'
            .DIRECTORY_SEPARATOR.'smspilot.class.php';

class Pilot extends AbstractSmsGate
{

    /**
     * @var SMSPilot
     */
    protected $smsPilotObj;

    /**
     * Send sms to $phone
     * @since 02.10.13 12:05
     * @author Arsen Abdusalamov
     * @param string $phone
     * @param string $text
     * @return bool
     */
    public function send($phone, $text, $from, $timeToSend = null)
    {
        assert(is_object($this->smsPilotObj));
        $result = $this->smsPilotObj->send($phone, $text, $from, $timeToSend);
        if (!$result) {
            Yii::log($this->smsPilotObj->error, CLogger::LEVEL_ERROR);
            throw new SmsException($this->smsPilotObj->error);
        }
        return $result;
    }

    /**
     * @since 02.10.13 11:59
     * @author Arsen Abdusalamov
     * @param array $options
     */
    public function setOptions(array $options)
    {
        assert(isset($options['api_key']));
        $this->smsPilotObj = new SMSPilot($options['api_key']);
    }

}