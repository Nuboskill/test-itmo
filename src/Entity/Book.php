<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="books",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="name_year", columns={"name", "year"}),
 *          @ORM\UniqueConstraint(name="name_isbn", columns={"name", "isbn"})
 *     })
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 * @UniqueEntity(
 *     fields={"name", "year"},
 *     message="Уже существует книга с таким названием и годом издания."
 * )
 * @UniqueEntity(
 *     fields={"name", "isbn"},
 *     message="Уже существует книга с таким названием и ISBN."
 * )
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint", options={"unsigned":true})
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=190, unique=true)
     */
    private $isbn;

    /**
     * @ORM\Column(type="smallint", nullable=true, options={"unsigned":true})
     */
    private $pages_count;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Author", inversedBy="books")
     */
    private $authors;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
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

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getPagesCount(): ?int
    {
        return $this->pages_count;
    }

    public function setPagesCount(?int $pages_count): self
    {
        $this->pages_count = $pages_count;

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
        }

        return $this;
    }
}
