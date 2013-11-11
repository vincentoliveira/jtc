<?php

namespace Jtc\AnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Annonce
 *
 * @ORM\Table(name="annonce")
 * @ORM\Entity(repositoryClass="Jtc\AnnonceBundle\Repository\AnnonceRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Annonce
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_maj", type="datetime")
     */
    private $dateMaj;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDepart", type="datetime")
     */
    private $dateDepart;
    
     /**
     * @var string
     *
     * @ORM\Column(name="statut", type="integer", length=2)
     */
    private $statut;
    
     /**
     * @var string
     *
     * @ORM\Column(name="type", type="integer", length=2)
     */
    private $type;
    
     /**
     * @var Jtc\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Jtc\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     */
    private $utilisateur;

     /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_depart", type="string", length=255)
     */
    private $villeDepart;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ville_arrive", type="string", length=255)
     */
    private $villeArrive;

    /**
     * @var integer
     *
     * @ORM\Column(name="poids", type="integer", nullable=true)
     */
    private $poids;

    /**
     * @var integer
     *
     * @ORM\Column(name="prix", type="integer", nullable=true)
     */
    private $prix;

    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->dateMaj = new \DateTime();
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
     * Set dateMaj
     *
     * @param \DateTime $dateMaj
     * @return Annonce
     */
    public function setDateMaj($dateMaj)
    {
        $this->dateMaj = $dateMaj;
    
        return $this;
    }

    /**
     * Get dateMaj
     *
     * @return \DateTime 
     */
    public function getDateMaj()
    {
        return $this->dateMaj;
    }

    /**
     * Set dateDepart
     *
     * @param \DateTime $dateDepart
     * @return Annonce
     */
    public function setDateDepart($dateDepart)
    {
        $this->dateDepart = $dateDepart;
    
        return $this;
    }

    /**
     * Get dateDepart
     *
     * @return \DateTime 
     */
    public function getDateDepart()
    {
        return $this->dateDepart;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     * @return Annonce
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    
        return $this;
    }

    /**
     * Get statut
     *
     * @return integer 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Annonce
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Annonce
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set villeDepart
     *
     * @param string $villeDepart
     * @return Annonce
     */
    public function setVilleDepart($villeDepart)
    {
        $this->villeDepart = $villeDepart;
    
        return $this;
    }

    /**
     * Get villeDepart
     *
     * @return string 
     */
    public function getVilleDepart()
    {
        return $this->villeDepart;
    }

    /**
     * Set villeArrive
     *
     * @param string $villeArrive
     * @return Annonce
     */
    public function setVilleArrive($villeArrive)
    {
        $this->villeArrive = $villeArrive;
    
        return $this;
    }

    /**
     * Get villeArrive
     *
     * @return string 
     */
    public function getVilleArrive()
    {
        return $this->villeArrive;
    }

    /**
     * Set poids
     *
     * @param integer $poids
     * @return Annonce
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;
    
        return $this;
    }

    /**
     * Get poids
     *
     * @return integer 
     */
    public function getPoids()
    {
        return $this->poids;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     * @return Annonce
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    
        return $this;
    }

    /**
     * Get prix
     *
     * @return integer 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set utilisateur
     *
     * @param \Jtc\UserBundle\Entity\User $utilisateur
     * @return Annonce
     */
    public function setUtilisateur(\Jtc\UserBundle\Entity\User $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;
    
        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \Jtc\UserBundle\Entity\User 
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }
}