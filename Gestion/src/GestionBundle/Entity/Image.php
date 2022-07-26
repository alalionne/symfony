<?php

namespace GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="GestionBundle\Repository\ImageRepository")
 */

class Image
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
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255, nullable=true)
     */
    private $alt;

    /**
     * @var \DateTime
     * 
     * @ORM\COlumn(name="updated_at",type="datetime", nullable=true) 
     */
    private $updateAt;

    private $file;

    // On ajoute cet attribut pour y stocker le nom du fichier temporairement
    private $tempFilename;
    
    /************ Le constructeur ************/
    
    public function __construct()
    {
        $this->alt = 'image';
        $this->url= 'anonymous.png';
    }
    
    /************ Les setters et getters ************/

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
     * Set url
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    public function getFile()
    {
        return $this->file;
    }


    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     *
     * @return Image
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe déjà un autre
  public function setFile(UploadedFile $file)
  {
    $this->file = $file;
  }

  /**
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        $this->updateAt = new \DateTime();
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate() 
     */
    public function preUpload()
    {   if (null === $this->file) return false;
        $this->oldFile = $this->getUrl();
        $this->updateAt = new \DateTime();
        $this->name = sha1(uniqid(mt_rand(),true));
        $this->url = sha1(uniqid(mt_rand(),true)).'.'.$this->file->guessExtension();
        $this->alt = $this->file->getClientOriginalName();
        return true;
    }
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate() 
     */
    public function upload()
    {    
        if (null !== $this->file) {
            $this->file->move($this->getUploadRootDir(),$this->url);
            unset($this->file);
        }
        return true;
    }

  /**
   * @ORM\PreRemove()
   */
  public function preRemoveUpload()
  {
    // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
    $this->tempFilename = $this->getAbsolutePath();
  }

  /**
   * @ORM\PostRemove()
   */
  public function removeUpload()
  {
    // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
    if (file_exists($this->tempFilename)) {
      // On supprime le fichier
      unlink($this->tempFilename);
    }
  }

  public function getUploadDir()
  {
    // On retourne le chemin relatif vers l'image pour un navigateur
    return 'uploads/img';
  }

  protected function getUploadRootDir()
  {  
    // On retourne le chemin relatif vers l'image pour notre code PHP
    return __DIR__.'/../../../web/'.$this->getUploadDir();
  }

  public function getAbsolutePath()
  {
    return null === $this->url ? null : $this->getUploadRootDir().'/'.$this->url;
  }

  public function getWebPath()
  {   
    return $this->getUploadDir().'/'.$this->url;
  }

}
