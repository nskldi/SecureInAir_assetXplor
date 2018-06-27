<?php

namespace SeeItAll\assetXploreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * document
 *
 * @ORM\Table(name="document")
 * @ORM\Entity(repositoryClass="SeeItAll\assetXploreBundle\Objects\documentRepository")
 * @ORM\HasLifecycleCallbacks 
 */
class document
{

 /**
   * @ORM\ManyToOne(targetEntity="SeeItAll\assetXploreBundle\Entity\building")
   * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")  
   */
//@ORM\JoinColumn(nullable=false) prohibits the creation of pdfs without a building
private $building;

 /**
   * @ORM\ManyToOne(targetEntity="SeeItAll\assetXploreBundle\Entity\room")
   * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")  
   */
//@ORM\JoinColumn(nullable=false) prohibits the creation of pdfss without a room 
private $room;

 /**
   * @ORM\ManyToOne(targetEntity="SeeItAll\assetXploreBundle\Entity\item")
   * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")  
   */
//@ORM\JoinColumn(nullable=false) prohibits the creation of rooms without a building
private $item;

    /**
     * @var string
     *
     * @ORM\Column(name="pdf_name", type="string", length=255, nullable=true)
     */
    private $PdfName;

        /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $Path;


        /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

        /**
    *  @var string
    * @ORM\Column(name="note", type="string", length=50, nullable=true)
    */
    private $note;



 
    
    








  



    /**
     * Set pdfName
     *
     * @param string $pdfName
     *
     * @return pdf
     */
    public function setPdfName($pdfName)
    {
        $this->PdfName = $pdfName;

        return $this;
    }

    /**
     * Get pdfName
     *
     * @return string
     */
    public function getPdfName()
    {
        return $this->PdfName;
    }

    /**
     * Set building
     *
     * @param \SeeItAll\assetXploreBundle\Entity\building $building
     *
     * @return pdf
     */
    public function setBuilding(\SeeItAll\assetXploreBundle\Entity\building $building = null)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * Get building
     *
     * @return \SeeItAll\assetXploreBundle\Entity\building
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Set room
     *
     * @param \SeeItAll\assetXploreBundle\Entity\room $room
     *
     * @return pdf
     */
    public function setRoom(\SeeItAll\assetXploreBundle\Entity\room $room = null)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return \SeeItAll\assetXploreBundle\Entity\room
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set item
     *
     * @param \SeeItAll\assetXploreBundle\Entity\item $item
     *
     * @return pdf
     */
    public function setItem(\SeeItAll\assetXploreBundle\Entity\item $item = null)
    {
        $this->item = $item;

        return $this;
    }



        /**
     * Set note
     *
     * @param string $Note
     *
     * @return note
     */
    public function setNote($Note)
    {
        $this->note = $Note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }








    private $tempFilename;

    private $file;

    
 



    public function getFile()
  {
    return $this->file;
  }

  // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe déjà un autre
  public function setFile(UploadedFile $file)
  {
    $this->file = $file;

    // On vérifie si on avait déjà un fichier pour cette entité
    if (null !== $this->Path) {
      // On sauvegarde l'extension du fichier pour le supprimer plus tard
      $this->tempFilename = $this->Path;

      // On réinitialise les valeurs des attributs url et alt
      $this->Path = null;
      
      $this->PdfName = null;
    }
  }



  /**
   * @ORM\PrePersist()
   * @ORM\PreUpdate()
   */
  public function preUpload()
  {
    // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
    if (null === $this->file) {
      return;
    }
    
    // Le nom du fichier est son id, on doit juste stocker également son extension
    // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « url »
    $this->Path = $this->getUploadDir().'/'.$this->file->getClientOriginalName();

    // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
    $this->PdfName = $this->file->getClientOriginalName();
    
    

  }

  /**
   * @ORM\PostPersist()
   * @ORM\PostUpdate()
   */

 public function upload()
    {
      // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
      if (null === $this->file) {
        return;
      }
        
         // Si on avait un ancien fichier, on le supprime
    if (null !== $this->tempFilename) {
      $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
      if (file_exists($oldFile)) {
        unlink($oldFile);
      }
    }

        // On déplace le fichier envoyé dans le répertoire de notre choix
    $this->file->move(
      $this->getUploadRootDir(), // Le répertoire de destination
       $this->PdfName  // Le nom du fichier à créer, ici « id.extension »
    );

 
    }


   /**
   * @ORM\PreRemove()
   */
  public function preRemoveUpload()
  {
    // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
    //$this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->roomImage;
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
      // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
      return 'uploads/docs';
    }
  
    protected function getUploadRootDir()
    {
      // On retourne le chemin relatif vers l'image pour notre code PHP
      return __DIR__.'/../../../../web/'.$this->getUploadDir();
       
    }





  






    /**
     * Set path
     *
     * @param string $path
     *
     * @return document
     */
    public function setPath($path)
    {
        $this->Path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->Path;
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
     * Get item
     *
     * @return \SeeItAll\assetXploreBundle\Entity\item
     */
    public function getItem()
    {
        return $this->item;
    }
}
