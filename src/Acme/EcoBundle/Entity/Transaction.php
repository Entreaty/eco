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
     * @ORM\Column(type="string")
     */
    protected $transactionType;

    /**
     * @ORM\Column(type="string")
     */
    protected $transactionName;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sum;

    /**
     * @ORM\Column(type="date")
     */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="transactions")
     * @ORM\JoinColumn(name="memberId", referencedColumnName="memberId")
     * @ORM\Column(type="integer")
     */
    protected $memberId;

    /**
     * @ORM\Column(type="integer", nullable =true)
     */
    protected $familyId;

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
     * Set transactionType
     *
     * @param string $transactionType
     * @return Transaction
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;

        return $this;
    }

    /**
     * Get transactionType
     *
     * @return string 
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * Set transactionName
     *
     * @param string $transactionName
     * @return Transaction
     */
    public function setTransactionName($transactionName)
    {
        $this->transactionName = $transactionName;

        return $this;
    }

    /**
     * Get transactionName
     *
     * @return string 
     */
    public function getTransactionName()
    {
        return $this->transactionName;
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

//    /**
//     * Set date
//     *
//     * @param \DateTime $date
//     * @return Transaction
//     */
//    public function setDate($date)
//    {
//        $this->date = $date;
//
//        return $this;
//    }
//
//    /**
//     * Get date
//     *
//     * @return \DateTime
//     */
//    public function getDate()
//    {
//        return $this->date;
//    }

    /**
     * Set memberId
     *
     * @param integer $memberId
     * @return Transaction
     */
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;

        return $this;
    }

    /**
     * Get memberId
     *
     * @return integer 
     */
    public function getMemberId()
    {
        return $this->memberId;
    }

    public function setCreatedAtValue()
    {
        if(!$this->getCreatedAt())
        {
            $this->created_at = new \DateTime();
        }
    }

    /**
     * Set familyId
     *
     * @param integer $familyId
     * @return Transaction
     */
    public function setFamilyId($familyId)
    {
        $this->familyId = $familyId;

        return $this;
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

    public function getDate()
    {
        return $this->date;
    }
    public function setDate(\DateTime $date = null)
    {
        $this->date = $date;
    }
}
