<?php

/**
 * @since 12.07.13 17:23
 * @author Arsen Abdusalamov
 */
class SmsDebugBehavior extends SmsSendBehavior
{

    public $filePath = 'sms.log';

    /**
     * @since 12.07.13 17:24
     * @author Arsen Abdusalamov
     * @param string $phone
     * @param string $text
     * @param string $from
     * @param timestamp $timeToSend
     */
    public function send($phone, $text, $from, $timeToSend = null, $addInfo = array())
    {
        assert(is_writable($this->filePath));
        $f = fopen($this->filePath, 'a+');
        $called = $this->getBackTrace();
        $addInfo = print_r($addInfo, 1);
        $template = "current_date: %s\nbacktrace: %s\nfrom: %s\nphone: %s\ntext: %s\ntimetosend: %s\nadd info: %s\n======\n";
        fwrite($f,
            sprintf($template, date('r'), implode("\n", $called), $from, $phone,
                wordwrap($text, 40), $timeToSend, $addInfo)
        );
        fclose($f);
    }

    public function sendSms(CEvent $event)
    {

        $params = $event->params;
        $this->send(
            (empty($params['phone']) ? '' : $params['phone']),
            (empty($params['text']) ? '' : $params['text']),
            (empty($params['from']) ? '' : $params['from']),
            (empty($params['timeToSend']) ? '' : $params['timeToSend']),
            (empty($params['addInfo']) ? '' : $params['addInfo'])
        );
    }

}