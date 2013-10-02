<?php

/**
 * @since 18.09.12 16:41
 * @author Arsen Abdusalamov
 */
class SmsRu extends AbstractSmsGate
{

    const GATE_URL = 'http://sms.ru/sms/send';
    const TIMIOUT = 30;

    protected $apiId = null;
    static protected $errorResponseCodes = array(
        //100 => 'Сообщение принято к отправке.',
        200 => 'Неправильный api_id',
        201 => 'Не хватает средств на лицевом счету',
        202 => 'Неправильно указан получатель',
        203 => 'Нет текста сообщения',
        204 => 'Имя отправителя не согласовано с администрацией',
        205 => 'Сообщение слишком длинное (превышает 8 СМС)',
        206 => 'Будет превышен или уже превышен дневной лимит на отправку сообщений',
        207 => 'На этот номер (или один из номеров) нельзя отправлять сообщения, либо указано более 100 номеров в списке получателей',
        208 => 'Параметр time указан неправильно',
        209 => 'Вы добавили этот номер (или один из номеров) в стоп-лист',
        210 => 'Используется GET, где необходимо использовать POST',
        211 => 'Метод не найден',
        220 => 'Сервис временно недоступен, попробуйте чуть позже.',
    );

    /**
     * Send sms to $phone
     * @since 24.09.12 15:46
     * @author Arsen Abdusalamov
     * @param string $phone
     * @param string $text
     * @return bool
     */
    public function send($phone, $text, $from, $timeToSend = null)
    {
        $ch = curl_init(self::GATE_URL);

        $postFields = array(
            "api_id" => $this->getApiId(),
            "from" => $from,
            "to" => self::canonicalPhone($phone),
            "text" => $text,
            'test' => intval(APPLICATION_ENV !== 'live'),
        );
        if ($timeToSend) {
            $postFields['time'] = $timeToSend;
        }

        curl_setopt_array($ch,
            array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => self::TIMIOUT,
            CURLOPT_POSTFIELDS => $postFields,
        ));

        $result = false;

        try {
            $body = curl_exec($ch);

            if (isset(self::$errorResponseCodes[$body])) {
                throw new SmsException(self::$errorResponseCodes[$body]);
            }
            $result = true;
        } catch (Sms_Model_Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_INFO);
        } catch (Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
        }

        curl_close($ch);

        return $result;
    }

    /**
     * @since 24.09.12 15:56
     * @author Arsen Abdusalamov
     * @return string
     */
    public function getApiId()
    {
        return $this->apiId;
    }

    /**
     * @since 12.07.13 17:09
     * @author Arsen Abdusalamov
     * @param string $apiId
     */
    public function setApiId($apiId)
    {
        $this->apiId = $apiId;
    }

    /**
     * @since 12.07.13 17:09
     * @author Arsen Abdusalamov
     * @param array $options
     */
    public function setOptions(array $options)
    {
        assert(isset($options['api_key']));
        $this->setApiId($options['api_key']);
    }

}