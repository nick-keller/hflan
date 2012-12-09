<?php

namespace hflan\TournamentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CustomField
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="hflan\TournamentBundle\Entity\CustomFieldRepository")
 */
class CustomField
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
     * @Assert\MinLength(2)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="validation", type="string", length=127)
     * @Assert\NotBlank()
     */
    private $validation;

    /**
     * @ORM\ManyToOne(targetEntity="hflan\TournamentBundle\Entity\Tournament", inversedBy="customFields")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;


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
     * @return CustomField
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
     * Set validation
     *
     * @param string $validation
     * @return CustomField
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;
    
        return $this;
    }

    /**
     * Get validation
     *
     * @return string 
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * Set tournament
     *
     * @param \hflan\TournamentBundle\Entity\Tournament $tournament
     * @return CustomField
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
}