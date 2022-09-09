<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\ArticleImageController;
use App\Repository\ImageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:collectionImage']],
    denormalizationContext: ['groups' => ['write:image'] ],
    collectionOperations: [
        'get',
        'post' => [
            'validation_groups' => ['create:article'],

        ]
        ],
    itemOperations: [
        'put',
        'delete',
        'get',
        'image' => [
            'method' => 'POST',
            'path' => '/post/{id}/image',
            'controller' => ArticleImageController::class,
            'openapi_context' =>[
                'requestBody' => [
                    'content'=> [
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
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['read:article' ,'read:collection', 'read:collectionImage'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['write:article', 'read:article' , 'read:collectionImage' ,'write:image'])]
    private $avant = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:article', 'write:article', 'create:article', 'read:collectionImage' ,'write:image']), Length(min: 5)]
    private ?string $link = null;

    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'images', cascade: ['persist'])]
    #[Groups(['read:article', 'read:collectionImage' ,'write:image'])    ]
    private ?Article $article = null;

    /**
     * @var File|null
     */
    #[Groups(['write:image'])]
    #[Vich\UploadableField(mapping: "images_image" , fileNameProperty: 'filePath' )]
    private $file;

    #[Groups(['read:article', 'read:collectionImage' , 'read:collection'])    ]
    private ?string $fileUrl;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:article', 'read:collectionImage' , 'read:collection'])    ]
    private ?string $filePath = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isAvant(): ?bool
    {
        return $this->avant;
    }

    public function setAvant( $avant): self
    {
        if ($avant !== 0) {
            $this->avant = 1;
        }
        $this->avant = 0;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get the value of file
     *
     * @return  File|null
     */ 
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of file
     *
     * @param  File|null  $file
     *
     * @return  self
     */ 
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the value of fileUrl
     */ 
    public function getFileUrl()
    {
        return "/images/images/".$this->getFilePath();
    }

    /**
     * Set the value of fileUrl
     *
     * @return  self
     */ 
    public function setFileUrl($fileUrl)
    {
        $this->fileUrl = $fileUrl;

        return $this;
    }
}
