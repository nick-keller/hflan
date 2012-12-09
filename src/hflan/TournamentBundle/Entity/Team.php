<?php

namespace hflan\TournamentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Team
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="hflan\TournamentBundle\Entity\TeamRepository")
 */
class Team
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
     * @ORM\Column(name="name", type="string", unique=true, length=127)
     * @Assert\NotBlank()
     * @Assert\MinLength(2)
     * @Assert\MaxLength(127)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="confirmed", type="boolean")
     */
    private $confirmed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="hflan\TournamentBundle\Entity\Tournament", inversedBy="teams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    /**
     * @ORM\OneToOne(targetEntity="hflan\UserBundle\Entity\User", inversedBy="team", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="hflan\TournamentBundle\Entity\Player", mappedBy="team", cascade={"remove"})
     */
    protected $players;

    public function __construct()
    {
        $this->confirmed = false;
        $this->created_at = new \Datetime();
        $this->players = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set tournament
     *
     * @param \hflan\TournamentBundle\Entity\Tournament $tournament
     * @return Team
     */
    public function setTournament(\hflan\TournamentBundle\Entity\Tournament $tournament)
    {
        $this->tournament = $tournament;
    
        return $this;
    }

    /**
     * Get tournament
     *
     * @return \hflan\TournamentBundle\Entity\Tournament 
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * Set user
     *
     * @param \hflan\UserBundle\Entity\User $user
     * @return Team
     */
    public function setUser(\hflan\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \hflan\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set confirmed
     *
     * @param boolean $confirmed
     * @return Team
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    
        return $this;
    }

    /**
     * Get confirmed
     *
     * @return boolean 
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Team
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

    /**
     * Add players
     *
     * @param \hflan\TournamentBundle\Entity\Player $players
     * @return Team
     */
    public function addPlayer(\hflan\TournamentBundle\Entity\Player $players)
    {
        $this->players[] = $players;
    
        return $this;
    }

    /**
     * Remove players
     *
     * @param \hflan\TournamentBundle\Entity\Player $players
     */
    public function removePlayer(\hflan\TournamentBundle\Entity\Player $players)
    {
        $this->players->removeElement($players);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayers()
    {
        return $this->players;
    }
}