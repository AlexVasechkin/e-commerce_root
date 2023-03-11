<?php

namespace App\Entity;

use App\Entity\Contracts\PutProductGroupInterface;
use App\Repository\ProductGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductGroupRepository::class)
 * @ORM\Table(
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="ix_uq_product_group__name",
 *             columns={"name"}
 *         )
 *     }
 * )
 */
class ProductGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ProductGroupItem::class, mappedBy="productGroup")
     */
    private $productGroupItems;

    public function __construct()
    {
        $this->productGroupItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function put(PutProductGroupInterface $request): self
    {
        return $this
            ->setName($request->getName())
        ;
    }

    /**
     * @return Collection<int, ProductGroupItem>
     */
    public function getProductGroupItems(): Collection
    {
        return $this->productGroupItems;
    }

    public function addProductGroupItem(ProductGroupItem $productGroupItem): self
    {
        if (!$this->productGroupItems->contains($productGroupItem)) {
            $this->productGroupItems[] = $productGroupItem;
            $productGroupItem->setProductGroup($this);
        }

        return $this;
    }

    public function removeProductGroupItem(ProductGroupItem $productGroupItem): self
    {
        if ($this->productGroupItems->removeElement($productGroupItem)) {
            // set the owning side to null (unless already changed)
            if ($productGroupItem->getProductGroup() === $this) {
                $productGroupItem->setProductGroup(null);
            }
        }

        return $this;
    }
}