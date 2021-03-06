<?php
namespace Acme\EcoBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Entity
* @ORM\Entity(repositoryClass="Acme\EcoBundle\Entity\CategoryRepository")
* @ORM\Table(name="category")
*/
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $categoryId;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $categoryName;

    /**
     * @ORM\ManyToOne(targetEntity="Family", inversedBy="categories")
     * @ORM\JoinColumn(name="familyId", referencedColumnName="familyId")
     */
    protected $family;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $isDeleted;

    /**
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="category")
     */
    protected $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }


    /**
     * Get categoryId
     *
     * @return integer 
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set categoryName
     *
     * @param string $categoryName
     * @return Category
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * Get categoryName
     *
     * @return string 
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * Set isDeleted
     *
     * @param integer $isDeleted
     * @return Category
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return integer 
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set family
     *
     * @param \Acme\EcoBundle\Entity\Family $family
     * @return Category
     */
    public function setFamily(\Acme\EcoBundle\Entity\Family $family = null)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * Get family
     *
     * @return \Acme\EcoBundle\Entity\Family 
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * Add transactions
     *
     * @param \Acme\EcoBundle\Entity\Transaction $transactions
     * @return Category
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
}
