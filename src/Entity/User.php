<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $first_name;

    /**
     * @ORM\Column(type="date")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sex;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $weigth;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="meContacts")
     */
    private $contacts;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="contacts")
     */
    private $meContacts;

    /**
     * @ORM\ManyToOne(targetEntity=Sport::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sport;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $priceManager;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $career;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $speciality;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imgpath;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->meContacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getWeigth(): ?string
    {
        return $this->weigth;
    }

    public function setWeigth(string $weigth): self
    {
        $this->weigth = $weigth;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(self $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
        }

        return $this;
    }

    public function removeContact(self $contact): self
    {
        $this->contacts->removeElement($contact);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getMeContacts(): Collection
    {
        return $this->meContacts;
    }

    public function addMeContact(self $meContact): self
    {
        if (!$this->meContacts->contains($meContact)) {
            $this->meContacts[] = $meContact;
            $meContact->addContact($this);
        }

        return $this;
    }

    public function removeMeContact(self $meContact): self
    {
        if ($this->meContacts->removeElement($meContact)) {
            $meContact->removeContact($this);
        }

        return $this;
    }

    public function getSport(): ?Sport
    {
        return $this->sport;
    }

    public function setSport(?Sport $sport): self
    {
        $this->sport = $sport;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPriceManager(): ?string
    {
        return $this->priceManager;
    }

    public function setPriceManager(?string $priceManager): self
    {
        $this->priceManager = $priceManager;

        return $this;
    }

    public function getCareer(): ?string
    {
        return $this->career;
    }

    public function setCareer(?string $career): self
    {
        $this->career = $career;

        return $this;
    }

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(?string $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    public function getImgpath(): ?string
    {
        return $this->imgpath;
    }

    public function setImgpath(string $imgpath): self
    {
        $this->imgpath = $imgpath;

        return $this;
    }
}
