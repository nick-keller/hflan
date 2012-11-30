<?php

namespace hflan\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * hflan\UserBundle\Entity\User
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email(checkMX = true)
     */
    protected $email;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     * @Assert\NotBlank()
     * @Assert\MinLength(6)
     */
    protected $plainPassword;

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
     * Set team
     *
     * @param \hflan\TournamentBundle\Entity\Team $team
     * @return User
     */
    public function setTeam(\hflan\TournamentBundle\Entity\Team $team = null)
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
}