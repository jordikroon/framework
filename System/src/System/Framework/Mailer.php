<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package Framework
 */

namespace System\Framework;

class Mailer
{
    private $mailer;

    public function __construct()
    {

        $config = new Config;
        $config->loadFile(__dir__ . '/../../../../Config/application.php');

        $mailsettings = $config->get('email');

        $transport = \Swift_SmtpTransport::newInstance($mailsettings['server'], $mailsettings['port'])
            ->setUsername($mailsettings['username'])
            ->setPassword($mailsettings['password']);

        $this->mailer = \Swift_Mailer::newInstance($transport);
    }

    public function send($message)
    {
        return $this->mailer->send($message);
    }
}
