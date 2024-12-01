<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SaleRepository::class)]
#[ORM\Table(name: 'sales')]
#[ORM\HasLifecycleCallbacks]
class Sale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['sale_info'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['sale_info'])]
    private ?string $customer_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['sale_info'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    #[Groups(['sale_info'])]
    private ?int $distance = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 4)]
    #[Groups(['sale_info'])]
    private ?float $total_weight = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Groups(['sale_info'])]
    private ?float $items_price = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Groups(['sale_info'])]
    private ?float $shipping_price = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Groups(['sale_info'])]
    private ?float $order_total = null;

    #[ORM\Column]
    #[Groups(['sale_info'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Groups(['sale_info'])]
    private ?\DateTimeImmutable $updated_at = null;

    /**
     * @var Collection<int, SaleItem>
     */
    #[ORM\OneToMany(targetEntity: SaleItem::class, mappedBy: 'sale', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['sale_info'])]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->customer_id = Uuid::v4()->toString();
        $this->total_weight = 0;
        $this->items_price = 0;
        $this->shipping_price = 0;
        $this->order_total = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, SaleItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(SaleItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $this->updateOrderTotal($item, 'inserted');
            $item->setSale($this);
        }

        return $this;
    }

    public function removeItem(SaleItem $item): static
    {
        if ($this->items->removeElement($item)) {
            $this->updateOrderTotal($item, 'removed');
            if ($item->getSale() === $this) {
                $item->setSale(null);
            }
        }

        return $this;
    }

    public function getCustomerId(): ?string
    {
        return $this->customer_id;
    }

    public function setCustomerId(string $customer_id): static
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(int $distance): static
    {
        $this->distance = $distance;

        return $this;
    }

    public function getTotalWeight(): ?float
    {
        return $this->total_weight;
    }

    public function increaseTotalWeight(float $weight): static
    {
        $this->total_weight += $weight;

        return $this;
    }

    public function decreaseTotalWeight(float $weight): static
    {
        $this->total_weight -= $weight;

        return $this;
    }

    public function getItemsPrice(): ?float
    {
        return $this->items_price;
    }

    public function increaseItemsTotal(float $item_price): static
    {
        $this->items_price += $item_price;

        return $this;
    }

    public function decreaseItemsTotal(float $item_price): static
    {
        $this->items_price -= $item_price;

        return $this;
    }

    public function getShippingPrice(): ?float
    {
        return $this->shipping_price;
    }

    public function getOrderTotal(): ?float
    {
        return $this->order_total;
    }

    public function updateOrderTotal(?SaleItem $item=null, string $action=''): static
    {
        if ($item && $action) {
            switch($action) {
                case 'inserted':
                    $item->getDrink()->decreaseStock($item->getQuantity());
                    break;
                case 'removed':
                    $item->getDrink()->increaseStock($item->getQuantity());
                    break;
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }
}
