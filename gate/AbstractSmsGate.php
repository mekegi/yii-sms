<?php

/**
 * @since 18.09.12 16:41
 * @author Arsen Abdusalamov
 */

abstract class AbstractSmsGate
{

    /**
     * Send sms to $phone
     * @since 24.09.12 15:46
     * @author Arsen Abdusalamov
     * @param string $phone
     * @param string $text
     * @param string $from
     * @param int|null $timeToSend timestamp for send
     * @return bool
     */
    abstract public function send($phone, $text, $from, $timeToSend = null);

    /**
     * Convert phone to canonical ( +7(987)-111-11-11 => 79871111111 )
	 * 909-123-45-66 => 79091234566
     * @since 24.09.12 15:46
     * @author Arsen Abdusalamov
     * @param string $phone
     * @return string
     */
    public static function canonicalPhone( $phone )
    {
        $result = preg_replace( '/[^0-9]+/', '', $phone );
		if(strlen($result)==10){
			$result = '7'.$result;
		}
        return $result;
    }
    /**
     * @since 12.07.13 17:06
     * @author Arsen Abdusalamov
     * @param array $options
     */
    abstract public function setOptions(array $options);
}
