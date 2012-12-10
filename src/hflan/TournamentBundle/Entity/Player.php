<?php

namespace hflan\TournamentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Player
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="hflan\TournamentBundle\Entity\PlayerRepository")
 */
class Player
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
     * @ORM\Column(name="firstname", type="string", length=127, nullable=true)
     * @Assert\MinLength(2)
     * @Assert\MaxLength(127)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=127, nullable=true)
     * @Assert\MinLength(2)
     * @Assert\MaxLength(127)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=127, nullable=true)
     * @Assert\MinLength(2)
     * @Assert\MaxLength(127)
     */
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=127, nullable=true)
     * @Assert\Email(checkMX = true)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     * @Assert\Date()
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="pc_type", type="string", length=127, nullable=true)
     * @Assert\Choice(choices = {"Descktop", "Laptop"})
     */
    private $pc_type;

    /**
     * @var string
     *
     * @ORM\Column(name="customFields", type="array", nullable=true)
     */
    private $customFields;

    /**
     * @ORM\ManyToOne(targetEntity="hflan\TournamentBundle\Entity\Team", inversedBy="players")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;


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
     * Set firstname
     *
     * @param string $firstname
     * @return Player
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Player
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     * @return Player
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    
        return $this;
    }

    /**
     * Get nickname
     *
     * @return string 
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Player
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
     * Set pc_type
     *
     * @param string $pcType
     * @return Player
     */
    public function setPcType($pcType)
    {
        $this->pc_type = $pcType;
    
        return $this;
    }

    /**
     * Get pc_type
     *
     * @return string 
     */
    public function getPcType()
    {
        return $this->pc_type;
    }

    /**
     * Set team
     *
     * @param \hflan\TournamentBundle\Entity\Team $team
     * @return Player
     */
    public function setTeam(\hflan\TournamentBundle\Entity\Team $team)
    {
        $this->team = $team;
    
        return $this;
    }

    /**
     * Get team
     *
     * @return \hflan\TournamentBundle\Entity\Team 
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return Player
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    
        return $this;
    }

    public function isValid()
    {
        if($this->firstname === null || $this->lastname === null || $this->nickname === null || $this->email === null || $this->birthday === null || $this->pc_type === null)
            return false;

        foreach($this->customFields as $value)
            if($value === null)
                return false;

        return true;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set customFields
     *
     * @param array $customFields
     * @return Player
     */
    public function setCustomFields($customFields)
    {
        $this->customFields = $customFields;
    
        return $this;
    }

    /**
     * Get customFields
     *
     * @return array 
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }
}