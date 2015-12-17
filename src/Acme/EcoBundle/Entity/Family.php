<?php
namespace Acme\EcoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Acme\EcoBundle\Entity\FamilyRepository")
 * @ORM\Table(name="family")
 */
class Family
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $familyId;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $familyName;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="family")
     */
    protected $categories;

    /**
     * @ORM\OneToMany(targetEntity="Member", mappedBy="family")
     */
    protected $members;

    /**
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="family")
     */
    protected $transactions;

    /**
     * @ORM\OneToMany(targetEntity="TransactionType", mappedBy="family")
     */
    protected $types;

    public function __construct()
    {
        $this->members = new ArrayCollection();

        $this->types = new ArrayCollection();

        $this->categories = new ArrayCollection();

        $this->transactions = new ArrayCollection();
    }


    /**
     * Get familyId
     *
     * @return integer 
     */
    public function getFamilyId()
    {
        return $this->familyId;
    }

    /**
     * Set familyName
     *
     * @param string $familyName
     * @return Family
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;

        return $this;
    }

    /**
     * Get familyName
     *
     * @return string 
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * Add categories
     *
     * @param \Acme\EcoBundle\Entity\Category $categories
     * @return Family
     */
    public function addCategory(\Acme\EcoBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Acme\EcoBundle\Entity\Category $categories
     */
    public function removeCategory(\Acme\EcoBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add members
     *
     * @param \Acme\EcoBundle\Entity\Member $members
     * @return Family
     */
    public function addMember(\Acme\EcoBundle\Entity\Member $members)
    {
        $this->members[] = $members;

        return $this;
    }

    /**
     * Remove members
     *
     * @param \Acme\EcoBundle\Entity\Member $members
     */
    public function removeMember(\Acme\EcoBundle\Entity\Member $members)
    {
        $this->members->removeElement($members);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Add transactions
     *
     * @param \Acme\EcoBundle\Entity\Transaction $transactions
     * @return Family
     */
    public function addTransaction(\Acme\EcoBundle\Entity\Transaction $transactions)
    {
        $this->transactions[] = $transactions;

        return $this;
    }

    /**
     * Remove transactions
     *
     * @param \Acme\EcoBundle\Entity\Transaction $transactions
     */
    public function removeTransaction(\Acme\EcoBundle\Entity\Transaction $transactions)
    {
        $this->transactions->removeElement($transactions);
    }

    /**
     * Get transactions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * Add types
     *
     * @param \Acme\EcoBundle\Entity\TransactionType $types
     * @return Family
     */
    public function addType(\Acme\EcoBundle\Entity\TransactionType $types)
    {
        $this->types[] = $types;

        return $this;
    }

    /**
     * Remove types
     *
     * @param \Acme\EcoBundle\Entity\TransactionType $types
     */
    public function removeType(\Acme\EcoBundle\Entity\TransactionType $types)
    {
        $this->types->removeElement($types);
    }

    /**
     * Get types
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTypes()
    {
        return $this->types;
    }
}
