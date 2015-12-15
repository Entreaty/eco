<?php
namespace Acme\EcoBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="string", length=100)
     */
    protected $categoryType;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $familyId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $memberId;

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
     * Set categoryType
     *
     * @param string $categoryType
     * @return Category
     */
    public function setCategoryType($categoryType)
    {
        $this->categoryType = $categoryType;

        return $this;
    }

    /**
     * Get categoryType
     *
     * @return string 
     */
    public function getCategoryType()
    {
        return $this->categoryType;
    }

    /**
     * Set familyId
     *
     * @param integer $familyId
     * @return Category
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

    /**
     * Set memberId
     *
     * @param integer $memberId
     * @return Category
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
}
