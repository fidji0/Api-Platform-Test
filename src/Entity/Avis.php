<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\AvisImageController;
use App\Repository\AvisRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: AvisRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:collectionImage']],
    denormalizationContext: ['groups' => ['write:avis']],
    
    itemOperations: [
        'put',
        'delete',
        'get',
        'image' => [
            'method' => 'POST',
            'path' => '/post/{id}/image',
            'controller' => AvisImageController::class,
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[Groups(['read:collectionImage'])]
    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[Groups(['write:avis' , 'read:collectionImage'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['write:avis' , 'read:collectionImage'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[Groups(['write:avis' , 'read:collectionImage'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;
    
    #[Groups(['write:avis' ])]
    #[Vich\UploadableField(mapping: "images_avis", fileNameProperty: 'image')]
    private $file;

    public function __construct()
    {
        $this->date = new DateTime();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
     
    public function getFile()
    {
        return $this->file;
    }

   
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }
}
