<?php
namespace Acme\EcoBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Acme\EcoBundle\Entity\TransactionTypeRepository")
 * @ORM\Table(name="transactionType")
 */
class TransactionType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $typeId;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $typeName;
    /**
     * @ORM\ManyToOne(targetEntity="Family", inversedBy="types")
     * @ORM\JoinColumn(name="familyId", referencedColumnName="familyId")
     */
    protected $family;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isDeleted;

    /**
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="type")
     */
    protected $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    /**
     * Get typeId
     *
     * @return integer 
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return TransactionType
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set typeName
     *
     * @param string $typeName
     * @return TransactionType
     */
    public function setTypeName($typeName)
    {
        $this->typeName = $typeName;

        return $this;
    }

    /**
     * Get typeName
     *
     * @return string 
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * Set isDeleted
     *
     * @param integer $isDeleted
     * @return TransactionType
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
     * @return TransactionType
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
     * @return TransactionType
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
