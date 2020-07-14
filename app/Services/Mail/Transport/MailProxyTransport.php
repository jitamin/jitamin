<?php

namespace Jitamin\Services\Mail\Transport;

use Swift_Events_EventListener;
use Swift_Mime_Message;
use Swift_Transport;

class MailProxyTransport implements Swift_Transport
{
    public static function newInstance()
    {
        return new self();
    }

    /**
     * Not used.
     */
    public function isStarted()
    {
        return false;
    }

    /**
     * Not used.
     */
    public function start()
    {
    }

    /**
     * Not used.
     */
    public function stop()
    {
    }

    /**
     * @param Swift_Mime_Message $message
     * @param null $failedRecipients
     * @return int|void
     */
    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $to = implode(array_keys($message->getTo()), ":");

        // custom mail proxy params
        $body = [
            "TextMode" => false,
            "To" => $to,
            "Title" => $message->getSubject(),
            "Body" => base64_encode($message->getBody()),
            "EncodeBody" => true,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => MAIL_PROXY_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "send mail error #:" . $err;
        }
    }

    /**
     * Not used.
     */
    public function registerPlugin(Swift_Events_EventListener $plugin)
    {

    }

}