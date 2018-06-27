<?php
        
namespace SeeItAll\assetXploreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

//use Google\Cloud\Storage\StorageClient;


# Your Google Cloud Platform project ID
//$projectId = 'assetxplor';

# Instantiates a client
//$storage = new StorageClient([
   // 'projectId' => $projectId
//]);

/**
 * building
 *
 * @ORM\Table(name="building")
 * @ORM\Entity(repositoryClass="SeeItAll\assetXploreBundle\Repository\buildingRepository")
 * @ORM\HasLifecycleCallbacks
 */
class building
{

/*    public static $counter=0;
    private $id_path_building = 0;

    public function __construct()
    {
        self::$counter=self::$counter+1;
        $this->id_path_building= self::$counter;
    } */
    

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_asset", type="integer", nullable=true)
     */
    private $id_asset;

      /**
     * @var int
     *
     * @ORM\Column(name="contract_number", type="integer", nullable=true )
     */
    private $contract_number;


    /**
     * @var string
     *
     * @ORM\Column(name="building_name", type="string", length=255,  nullable=true)
     */
    private $buildingName;

 

    /**
     * @var string
     *
     * @ORM\Column(name="building_image", type="string", length=255, nullable=true)
     */
    private $buildingImage;

    /**
    *  @var string
    * @ORM\Column(name="note", type="string", length=50, nullable=true)
    */
    private $note;


        /**
    *  @var string
    * @ORM\Column(name="data_loc", type="string", length=400, nullable=true)
    */
    private $data_loc;


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
     * Get asset_id
     *
     * @return int
     */
    public function getIdAsset()
    {
        return $this->id_asset;
    }

    /**
     * Set id_asset
     *
     * @param string $idAsset
     *
     * @return building
     */
    public function setIdAsset($idAsset)
    {
        $this->id_asset =$idAsset;

        return $this;
    }

    /**
     * Set buildingName
     *
     * @param string $BuildingName
     *
     * @return building
     */
    public function setBuildingName($BuildingName)
    {
        $this->buildingName =$BuildingName;

        return $this;
    }
    /**
     * Get data_loc
     *
     * @return string
     */
    public function getDataLoc()
    {
        return $this->data_loc;
    }

     /**
     * Set data_loc
     *
     * @param string $Dataloc
     *
     * @return building
     */
    public function setDataLoc($DataLoc)
    {
        $this->data_loc =$DataLoc;
        return $this;
    }

    /**
     * Get buildingName
     *
     * @return string
     */
    public function getBuildingName()
    {
        return $this->buildingName;
    }

    /**
     * Set buildingPdf
     *
     * @param string $buildingPdf
     *
     * @return building
     */
    public function setBuildingPdf($buildingPdf)
    {
        $this->buildingPdf = $buildingPdf;

        return $this;
    }

    /**
     * Get buildingPdf
     *
     * @return string
     */
    public function getBuildingPdf()
    {
        return $this->buildingPdf;
    }

    /**
     * Set buildingImage
     *
     * @param string $buildingImage
     *
     * @return building
     */
    public function setBuildingImage($buildingImage)
    {
        $this->buildingImage = $buildingImage;

        return $this;
    }

    /**
     * Get buildingImage
     *
     * @return string
     */
    public function getBuildingImage()
    {
        return $this->buildingImage;
    }


      /**
     * SetNote
     *
     * @param string $Note
     *
     * @return building
     */
    public function setNote($Note)
    {
        $this->note =$Note;

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


        /**
     * SetContract_number
     *
     * @param string $Contract_number
     *
     * @return building
     */
    public function setContractNumber($Contract_number)
    {
        $this->contract_number =$Contract_number;

        return $this->contract_number;
    }

    /**
     * Get Contract_number
     *
     * @return string
     */
    public function getContractNumber()
    {
        return $this->contract_number;
    }






 private $file;

// On ajoute cet attribut pour y stocker le nom du fichier temporairement
  private $tempFilename;


    public function getFile()
  {
    return $this->file;
  }

  // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe déjà un autre
  public function setFile(UploadedFile $file)
  {
    $this->file = $file;

    // On vérifie si on avait déjà un fichier pour cette entité
    if (null !== $this->buildingName) {
      // On sauvegarde l'extension du fichier pour le supprimer plus tard
      $this->tempFilename = $this->buildingName;


      // On réinitialise les valeurs des attributs url et alt
      $this->buildingImage = null;
      $this->buildingName = null;
    }
  }


private $uniqid;


  /**
   * @ORM\PreUpdate()
   *  @ORM\PrePersist()    
   * 
   */
  public function preUpload()
  {
    // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
    if (null === $this->file) {
      return;
    }
            $this->uniqid = uniqid();
              //$this->buildingName = date('Y-m-d H:i:s');
            $this->buildingName = $this->file->getClientOriginalName();
             $this->buildingImage = $this->getUploadDir().'/'.$this->uniqid;
             
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
      //$storage = new StorageClient();  

      //move_uploaded_file($this->file, "gs://assetxplor/".$this->buildingName);
 // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move(
         $this->getUploadRootDir(), // Le répertoire de destination
             $this->uniqid // Le nom du fichier à créer, ici « id.extension »
            );
      
     // move_uploaded_file($this->file, "gs://${my_bucket}/{$this->buildingName}");
 
    }


   /**
   * @ORM\PreRemove()
   */
  public function preRemoveUpload()
  {
    // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
    $this->tempFilename = $this->getUploadRootDir().'/'.$this->buildingName;
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
      return 'uploads';
    }
  
    protected function getUploadRootDir()
    {
      // On retourne le chemin relatif vers l'image pour notre code PHP
      return __DIR__.'/../../../../web/'.$this->getUploadDir();
       
    }
    

}
