<?php

namespace GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sortie
 *
 * @ORM\Table(name="sortie")
 * @ORM\Entity(repositoryClass="GestionBundle\Repository\SortieRepository")
 */
class Sortie
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
     * @ORM\Column(name="dateSortie", type="datetimetz")
     */
    private $dateSortie;

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
     * @ORM\ManyToOne(targetEntity="Produit", cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity="Destination", cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $destination;

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
     *s
     * @ORM\Column(name="valeur", type="float")
     */
    private $valeur;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateSortie = new \DateTime();
    }


    /**
     * Get id
     *
     * @return int
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
     * @return Sortie
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
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return Sortie
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set dateSortie
     *
     * @param \DateTime $dateSortie
     *
     * @return Sortie
     */
    public function setDateSortie($dateSortie)
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    /**
     * Get dateSortie
     *
     * @return \DateTime
     */
    public function getDateSortie()
    {
        return $this->dateSortie;
    }

    /**
     * Set dosage
     *
     * @param string $dosage
     *
     * @return Sortie
     */
    public function setDosage($dosage)
    {
        $this->dosage = $dosage;

        return $this;
    }

    /**
     * Get dosage
     *
     * @return string
     */
    public function getDosage()
    {
        return $this->dosage;
    }


    /**
     * Set conditionnement
     *
     * @param string $conditionnement
     *
     * @return Sortie
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
     * Set produit
     *
     * @param \GestionBundle\Entity\Produit $produit
     *
     * @return Sortie
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
     * Set destination
     *
     * @param \GestionBundle\Entity\Destination $destination
     *
     * @return Sortie
     */
    public function setDestination(\GestionBundle\Entity\Destination $destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return \GestionBundle\Entity\Destination
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set datePeremption
     *
     * @param \DateTime $datePeremption
     *
     * @return Sortie
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
     * Set prix
     *
     * @param float $prix
     *
     * @return Sortie
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
     * @return Sortie
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
