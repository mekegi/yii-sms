<?php

/**
 * @since 22.07.13 15:19
 * @author Arsen Abdusalamov
 */
abstract class SmsSendBehavior extends CBehavior
{

    /**
     * @since 22.07.13 15:06
     * @author Arsen Abdusalamov
     * @param CEvent $event
     */
    abstract protected function sendSms(CEvent $event);

    /**
     * @since 22.07.13 15:50
     * @author Arsen Abdusalamov
     * @return array
     */
    public function events()
	{
		return array_merge(parent::events(), [
			'onSendSms' => 'sendSms',
		]);
	}

    /**
     * @since 22.07.13 16:17
     * @author Arsen Abdusalamov
     * @return array
     */
    protected function getBackTrace($skip = 4, $lineNumber=1)
    {
        $result = [];
        $backtrace = debug_backtrace(0);

        for($i = $skip; ($i-$skip)<$lineNumber; $i++){
            if(!isset($backtrace[$i])) {
                break;
            }
            $result[] = (empty($backtrace[$i]['file']) ? '(closure)' : $backtrace[$i]['file'])
                . ':' . (empty($backtrace[$i]['line']) ? '(closure)' : $backtrace[$i]['line']);
        }

        return $result;
    }
}