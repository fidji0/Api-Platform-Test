<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Valid;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[
    ApiResource(

        normalizationContext: ['groups' => ['read:collection']],
        denormalizationContext: ['groups' => ['write:article']],
        paginationItemsPerPage: 2,
        paginationMaximumItemsPerPage: 2,
        paginationClientItemsPerPage: true,
        collectionOperations: [
            'get',
            'post'
        ],
        //denormalizationContext: ['groups' => ['write:article']],
        itemOperations: [
            'put',

            'delete',
            'get' => [
                'normalization_context' => ['groups' => [
                    'read:item', 'read:post', 'read:collection'
                ]]
            ],
        ]

    ),
    ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'title' => 'partial'])
]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['read:collection'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[
        Groups(['read:collection', 'write:article']),
        Length(min: 5, groups: ['create:article'])
    ]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:item', 'write:article'])]
    private ?string $description = null;

    #[ORM\Column(length: 100)]
    #[Groups(['read:item', 'write:article'])]
    private ?string $city = null;

    #[ORM\Column]
    #[Groups(['write:article', 'read:item', 'read:collection'])]
    private ?int $zipCode = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:collection', 'write:article'])]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Image::class, cascade: ['persist'])]
    #[
        Groups(
            ['read:collection', 'write:article']
        )
    ]
    private Collection $images;

    public static function validationGroups(self $article)
    {
        return ['create:article'];
    }

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->zipCode = 12324;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setArticle($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getArticle() === $this) {
                $image->setArticle(null);
            }
        }

        return $this;
    }
}
