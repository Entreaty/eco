<?php
namespace Acme\EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Acme\EcoBundle\Entity\TransactionRepository")
 * @ORM\Table(name="transaction")
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $transactionId;

    /**
     * @ORM\Column(type="float")
     */
    protected $sum;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $isDeleted;

    /**
     * @ORM\Column(type="date")
     */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="transactions")
     * @ORM\JoinColumn(name="categoryId", referencedColumnName="categoryId")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="transactions")
     * @ORM\JoinColumn(name="memberId", referencedColumnName="memberId")
     */
    protected $member;

    /**
     * @ORM\ManyToOne(targetEntity="Family", inversedBy="transactions")
     * @ORM\JoinColumn(name="familyId", referencedColumnName="familyId")
     */
    protected $family;

    /**
     * @ORM\ManyToOne(targetEntity="TransactionType", inversedBy="transactions")
     * @ORM\JoinColumn(name="typeId", referencedColumnName="typeId")
     */
    protected $type;





    /**
     * Get transactionId
     *
     * @return integer 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set sum
     *
     * @param integer $sum
     * @return Transaction
     */
    public function setSum($sum)
    {
        $this->sum = $sum;

        return $this;
    }

    /**
     * Get sum
     *
     * @return integer 
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Set isDeleted
     *
     * @param integer $isDeleted
     * @return Transaction
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
     * Set date
     *
     * @param \DateTime $date
     * @return Transaction
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set category
     *
     * @param \Acme\EcoBundle\Entity\Category $category
     * @return Transaction
     */
    public function setCategory(\Acme\EcoBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Acme\EcoBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set member
     *
     * @param \Acme\EcoBundle\Entity\Member $member
     * @return Transaction
     */
    public function setMember(\Acme\EcoBundle\Entity\Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \Acme\EcoBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set family
     *
     * @param \Acme\EcoBundle\Entity\Family $family
     * @return Transaction
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
     * Set type
     *
     * @param \Acme\EcoBundle\Entity\TransactionType $type
     * @return Transaction
     */
    public function setType(\Acme\EcoBundle\Entity\TransactionType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Acme\EcoBundle\Entity\TransactionType 
     */
    public function getType()
    {
        return $this->type;
    }
}
