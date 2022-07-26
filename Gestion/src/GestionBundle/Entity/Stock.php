<?php

namespace GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 *
 * @ORM\Table(name="stock")
 * @ORM\Entity(repositoryClass="GestionBundle\Repository\StockRepository")
 */
class Stock
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
     * @var string
     *
     * @ORM\Column(name="dci", type="string", length=255)
     */
    private $dci;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var int
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
     * @var string
     *
     * @ORM\Column(name="utilisation", type="string", length=255)
     */
    private $utilisation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_peremption", type="datetime")
     */
    private $datePeremption;

    /**
     * @var int
     *
     * @ORM\Column(name="qte_sortie", type="integer", nullable=true)
     */
    private $qteSortie;

    /**
     * @var int
     *
     * @ORM\Column(name="lastqte_sortie", type="integer", nullable=true)
     */
    private $lastqteSortie;

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
     * @return Stock
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
     * Set produit
     *
     * @param string $produit
     *
     * @return Stock
     */
    public function setProduit($produit)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return string
     */
    public function getProduit()
    {
        return $this->produit;
    }


    /**
     * Set datePeremption
     *
     * @param \DateTime $datePeremption
     *
     * @return Stock
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
     * @return Stock
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
     * Set qteSortie
     *
     * @param integer $qteSortie
     *
     * @return Stock
     */
    public function setQteSortie($qteSortie)
    {
        $this->qteSortie = $qteSortie;

        return $this;
    }

    /**
     * Get qteSortie
     *
     * @return integer
     */
    public function getQteSortie()
    {
        return $this->qteSortie;
    }

    /**
     * Set lastqteSortie
     *
     * @param integer $lastqteSortie
     *
     * @return Stock
     */
    public function setLastqteSortie($lastqteSortie)
    {
        $this->lastqteSortie = $lastqteSortie;

        return $this;
    }

    /**
     * Get lastqteSortie
     *
     * @return integer
     */
    public function getLastqteSortie()
    {
        return $this->lastqteSortie;
    }

    /**
     * Set dci
     *
     * @param string $dci
     *
     * @return Stock
     */
    public function setDci($dci)
    {
        $this->dci = $dci;

        return $this;
    }

    /**
     * Get dci
     *
     * @return string
     */
    public function getDci()
    {
        return $this->dci;
    }

    /**
     * Set conditionnement
     *
     * @param string $conditionnement
     *
     * @return Stock
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
     * Set utilisation
     *
     * @param string $utilisation
     *
     * @return Stock
     */
    public function setUtilisation($utilisation)
    {
        $this->utilisation = $utilisation;

        return $this;
    }

    /**
     * Get utilisation
     *
     * @return string
     */
    public function getUtilisation()
    {
        return $this->utilisation;
    }

    /**
     * Set dosage
     *
     * @param string $dosage
     *
     * @return Stock
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
     * Set prix
     *
     * @param float $prix
     *
     * @return Stock
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
     * @return Stock
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
