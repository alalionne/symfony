<?php

namespace GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entree
 *
 * @ORM\Table(name="entree")
 * @ORM\Entity(repositoryClass="GestionBundle\Repository\EntreeRepository")
 */
class Entree
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEntree", type="datetime")
     */
    private $dateEntree;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePeremption", type="datetime")
     */
    private $datePeremption;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;

    /**
     * @var float
     *
     * @ORM\Column(name="valeur", type="float")
     */
    private $valeur;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="dosage", type="string", length=255, nullable=true)
     */
    private $dosage;

    /**
     * @var string
     *
     * @ORM\Column(name="conditionnement", type="string", length=255)
     */
    private $conditionnement;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="annee", type="datetimetz")
     */
    private $annee;

    /**
     * @ORM\ManyToOne(targetEntity="Produit", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity="Utilisation", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisation;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateEntree = new \DateTime();
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
     * Set code
     *
     * @param string $code
     *
     * @return Entree
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set dateEntree
     *
     * @param \DateTime $dateEntree
     *
     * @return Entree
     */
    public function setDateEntree($dateEntree)
    {
        $this->dateEntree = $dateEntree;

        return $this;
    }

    /**
     * Get dateEntree
     *
     * @return \DateTime
     */
    public function getDateEntree()
    {
        return $this->dateEntree;
    }

    /**
     * Set datePeremption
     *
     * @param \DateTime $datePeremption
     *
     * @return Entree
     */
    public function setDatePeremption($datePeremption)
    {
        $this->datePeremption = $datePeremption;

        return $this;
    }

    /**
     * Get datePeremption
     *
     * @return \DateTime
     */
    public function getDatePeremption()
    {
        return $this->datePeremption;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return Entree
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set conditionnement
     *
     * @param string $conditionnement
     *
     * @return Entree
     */
    public function setConditionnement($conditionnement)
    {
        $this->conditionnement = $conditionnement;

        return $this;
    }

    /**
     * Get conditionnement
     *
     * @return string
     */
    public function getConditionnement()
    {
        return $this->conditionnement;
    }

    /**
     * Set annee
     *
     * @param \DateTime $annee
     *
     * @return Entree
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return \DateTime
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set produit
     *
     * @param \GestionBundle\Entity\Produit $produit
     *
     * @return Entree
     */
    public function setProduit(\GestionBundle\Entity\Produit $produit)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \GestionBundle\Entity\Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * Set utilisation
     *
     * @param \GestionBundle\Entity\Utilisation $utilisation
     *
     * @return Entree
     */
    public function setUtilisation(\GestionBundle\Entity\Utilisation $utilisation)
    {
        $this->utilisation = $utilisation;

        return $this;
    }

    /**
     * Get utilisation
     *
     * @return \GestionBundle\Entity\Utilisation
     */
    public function getUtilisation()
    {
        return $this->utilisation;
    }

    /**
     * Set dosage
     *
     * @param integer $dosage
     *
     * @return Entree
     */
    public function setDosage($dosage)
    {
        $this->dosage = $dosage;

        return $this;
    }

    /**
     * Get dosage
     *
     * @return integer
     */
    public function getDosage()
    {
        return $this->dosage;
    }

    /**
     * Set prix
     *
     * @param float $prix
     *
     * @return Entree
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set valeur
     *
     * @param float $valeur
     *
     * @return Entree
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return float
     */
    public function getValeur()
    {
        return $this->valeur;
    }
}
