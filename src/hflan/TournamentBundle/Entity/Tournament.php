<?php

namespace hflan\TournamentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * tournament
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="hflan\TournamentBundle\Entity\TournamentRepository")
 */
class Tournament
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
     * @ORM\Column(name="name", type="string", length=127)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="game", type="string", length=127)
     * @Assert\NotBlank()
     */
    private $game;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbrTeams", type="integer")
     * @Assert\NotBlank()
     */
    private $nbrTeams;

    /**
     * @var integer
     *
     * @ORM\Column(name="playersPerTeam", type="integer")
     * @Assert\NotBlank()
     */
    private $playersPerTeam;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer")
     * @Assert\NotBlank()
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="embedded_player", type="text", nullable=true)
     */
    private $embeddedPlayer;

    /**
     * @var boolean
     *
     * @ORM\Column(name="casu", type="boolean")
     */
    private $casu;

    /**
     * @ORM\ManyToOne(targetEntity="hflan\TournamentBundle\Entity\Event", inversedBy="tournaments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\OneToMany(targetEntity="hflan\TournamentBundle\Entity\Team", mappedBy="tournament", cascade={"remove"})
     */
    protected $teams;

    /**
     * @ORM\OneToMany(targetEntity="hflan\TournamentBundle\Entity\CustomField", mappedBy="tournament", cascade={"persist", "remove"})
     */
    protected $customFields;

    public function __construct($type)
    {
        $this->name = "Tournoi ";
        $this->nbrTeams = 16;
        $this->playersPerTeam = 1;
        $this->teams = new ArrayCollection();
        $this->customFields = new ArrayCollection();
        $this->casu = false;

        if($type == 'casu')
        {
            $this->setName('Freeplay/Visiteur');
            $this->setNbrTeams(40);
            $this->setCasu(true);
            $this->setPlayersPerTeam(1);
            $this->setGame('none');
            $this->setPrice(0);
        }
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getPrizePool()
    {
        return $this->nbrTeams*$this->price*$this->playersPerTeam;
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
     * @return tournament
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
     * Set game
     *
     * @param string $game
     * @return tournament
     */
    public function setGame($game)
    {
        $this->game = $game;
    
        return $this;
    }

    /**
     * Get game
     *
     * @return string 
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set nbrTeams
     *
     * @param integer $nbrTeams
     * @return tournament
     */
    public function setNbrTeams($nbrTeams)
    {
        $this->nbrTeams = $nbrTeams;
    
        return $this;
    }

    /**
     * Get nbrTeams
     *
     * @return integer 
     */
    public function getNbrTeams()
    {
        return $this->nbrTeams;
    }

    /**
     * Set playersPerTeam
     *
     * @param integer $playersPerTeam
     * @return tournament
     */
    public function setPlayersPerTeam($playersPerTeam)
    {
        $this->playersPerTeam = $playersPerTeam;
    
        return $this;
    }

    /**
     * Get playersPerTeam
     *
     * @return integer 
     */
    public function getPlayersPerTeam()
    {
        return $this->playersPerTeam;
    }

    /**
     * Set price
     *
     * @param integer $price
     * @return tournament
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set event
     *
     * @param \hflan\TournamentBundle\Entity\Event $event
     * @return Tournament
     */
    public function setEvent(\hflan\TournamentBundle\Entity\Event $event)
    {
        $this->event = $event;
    
        return $this;
    }

    /**
     * Get event
     *
     * @return \hflan\TournamentBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Add teams
     *
     * @param \hflan\TournamentBundle\Entity\Team $teams
     * @return Tournament
     */
    public function addTeam(\hflan\TournamentBundle\Entity\Team $teams)
    {
        $this->teams[] = $teams;
    
        return $this;
    }

    /**
     * Remove teams
     *
     * @param \hflan\TournamentBundle\Entity\Team $teams
     */
    public function removeTeam(\hflan\TournamentBundle\Entity\Team $teams)
    {
        $this->teams->removeElement($teams);
    }

    /**
     * Get teams
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * Set casu
     *
     * @param boolean $casu
     * @return Tournament
     */
    public function setCasu($casu)
    {
        $this->casu = $casu;
    
        return $this;
    }

    /**
     * Get casu
     *
     * @return boolean 
     */
    public function getCasu()
    {
        return $this->casu;
    }

    /**
     * Set embeddedPlayer
     *
     * @param string $embeddedPlayer
     * @return Tournament
     */
    public function setEmbeddedPlayer($embeddedPlayer)
    {
        $this->embeddedPlayer = $embeddedPlayer;
    
        return $this;
    }

    /**
     * Get embeddedPlayer
     *
     * @return string 
     */
    public function getEmbeddedPlayer()
    {
        return $this->embeddedPlayer;
    }

    /**
     * Add customFields
     *
     * @param \hflan\TournamentBundle\Entity\CustomField $customFields
     * @return Tournament
     */
    public function addCustomField(\hflan\TournamentBundle\Entity\CustomField $customFields)
    {
        $customFields->setTournament($this);
        $this->customFields[] = $customFields;
    
        return $this;
    }

    /**
     * Remove customFields
     *
     * @param \hflan\TournamentBundle\Entity\CustomField $customFields
     */
    public function removeCustomField(\hflan\TournamentBundle\Entity\CustomField $customFields)
    {
        $this->customFields->removeElement($customFields);
    }

    /**
     * Get customFields
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }
}