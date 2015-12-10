<?php
        
/*
This file is part of Incipio.

Incipio is an enterprise resource planning for Junior Enterprise
Copyright (C) 2012-2014 Florian Lefevre.

Incipio is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as
published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

Incipio is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with Incipio as the file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
*/


namespace mgate\LoggerBundle\Util;

use mgate\LoggerBundle\Entity\Mail;

use Doctrine\ORM\EntityManager;

class MailerWrapper extends \Swift_Mailer
{
    /** @var \Swift_Mailer */
    private $_mailer;

    private $em;

    public function __construct(\Swift_Transport $transport, \Doctrine\Bundle\DoctrineBundle\Registry $em)
    {
        $this->_mailer = parent::newInstance($transport); // _transport is private in Swift_Mailer
        $this->em = $em->getManager();
    }

    public static function newInstance(\Swift_Transport $transport)
    {
        return new self($transport);
    }

    public function send(\Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $ret = $this->_mailer
            ->send($message, $failedRecipients);
        $mail = new Mail();

        $to = implode(',',array_keys($message->getTo()));
        $mail->setDest($to);
        $mail->setSubject($message->getSubject());
        $mail->setContent($message->getBody());
        $mail->setDate(new \DateTime(date("Y-m-d H:i:s",$message->getDate())));

        $this->em->persist($mail);
        $this->em->flush();

        return $ret;
    }

    public function getTransport()
    {
        return $this->_mailer
            ->getTransport();
    }

    public function registerPlugin(Swift_Events_EventListener $plugin)
    {
        $this->_transport
            ->registerPlugin($plugin);
    }
}
