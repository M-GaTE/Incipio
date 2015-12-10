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


namespace mgate\LoggerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * mgate\LoggerBundle\Entity\Mail
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Mail {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected	$id;

    /**
     * @ORM\Column(name="dest", type="string", length=255)
     */
    protected   $dest;

    /**
     * @ORM\Column(name="subject", type="string", length=300)
     */
    protected   $subject;

    /**
     * @ORM\Column(name="content", type="text")
     */
    protected   $content;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    protected   $date;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Mail
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Mail
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Constructor
     */
    public function __construct() {
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Mail
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set dest
     *
     * @param string $dest
     * @return Mail
     */
    public function setDest($dest)
    {
        $this->dest = $dest;
    
        return $this;
    }

    /**
     * Get dest
     *
     * @return string 
     */
    public function getDest()
    {
        return $this->dest;
    }
}