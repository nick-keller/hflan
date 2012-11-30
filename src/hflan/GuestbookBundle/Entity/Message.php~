<?php

namespace hflan\GuestbookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Message
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="hflan\GuestbookBundle\Entity\MessageRepository")
 */
class Message
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=127)
     * @Assert\NotBlank()
     * @Assert\MinLength(2)
     * @Assert\MaxLength(127)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     * @Assert\NotBlank()
     * @Assert\MinLength(8)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\Email(
     *     message = "Si vous indiquez votre adresse e-mail, elle doit être valide.",
     *     checkMX = true)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer")
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     * @Assert\Min(1)
     * @Assert\Max(5)
     */
    private $rating;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="date")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $created_at;

    public function __construct()
    {
        $this->created_at = new \Datetime();
        $this->rating = 5;
    }


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
     * Set author
     *
     * @param string $author
     * @return Message
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    
        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Message
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return Message
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    
        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Message
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}