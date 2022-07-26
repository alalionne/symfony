<?php

namespace GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="GestionBundle\Repository\CommandeRepository")
 */
class Commande
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
     * @ORM\Column(name="quantite", type="string", length=255)
     */
    private $quantite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="dosage", type="string", length=255)
     */
    private $dosage;

    /**
     * @var string
     *
     * @ORM\Column(name="forme", type="string", length=255)
     */
    private $forme;

    /**
     * @var string
     *
     * @ORM\Column(name="conditionnement", type="string", length=255)
     */
    private $conditionnement;

    /**
     * @ORM\ManyToOne(targetEntity="Produit", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity="Destination", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $destination;


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
     * Set quantite
     *
     * @param string $quantite
     *
     * @return Commande
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return string
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Commande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set dosage
     *
     * @param string $dosage
     *
     * @return Commande
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
     * Set forme
     *
     * @param string $forme
     *
     * @return Commande
     */
    public function setForme($forme)
    {
        $this->forme = $forme;

        return $this;
    }

    /**
     * Get forme
     *
     * @return string
     */
    public function getForme()
    {
        return $this->forme;
    }

    /**
     * Set conditionnement
     *
     * @param string $conditionnement
     *
     * @return Commande
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
     * @return Commande
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
     * @return Commande
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
}
