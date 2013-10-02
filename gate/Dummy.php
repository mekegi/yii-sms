<?php

/**
 * @since 22.07.13 15:08
 * @author Arsen Abdusalamov
 */
class Dummy extends AbstractSmsGate
{

    public function send($phone, $text, $from, $timeToSend = null)
    {
        return true;
    }

    public function setOptions(array $options)
    {
        //nothing
    }

}
