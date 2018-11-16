<?php

namespace Eccube\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Flyer
 */
class Flyer extends \Eccube\Entity\AbstractEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $file_name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $link_label;

    /**
     * @var integer
     */
    private $rank;

    /**
     * @var \DateTime
     */
    private $create_date;

    /**
     * @var \Eccube\Entity\Master\CustomerType
     */
    private $Target;

    /**
     * @var \Eccube\Entity\Member
     */
    private $Creator;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set file_name
     *
     * @param string $fileName
     * @return Flyer
     */
    public function setFileName($fileName)
    {
        $this->file_name = $fileName;

        return $this;
    }

    /**
     * Get file_name
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Flyer
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set link_label
     *
     * @param string $linkLabel
     * @return Flyer
     */
    public function setLinkLabel($linkLabel)
    {
        $this->link_label = $linkLabel;

        return $this;
    }

    /**
     * Get link_label
     *
     * @return string 
     */
    public function getLinkLabel()
    {
        return $this->link_label;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return Flyer
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set create_date
     *
     * @param \DateTime $createDate
     * @return Flyer
     */
    public function setCreateDate($createDate)
    {
        $this->create_date = $createDate;

        return $this;
    }

    /**
     * Get create_date
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * Set Target
     *
     * @param \Eccube\Entity\Master\CustomerType $target
     * @return Flyer
     */
    public function setTarget(\Eccube\Entity\Master\CustomerType $target = null)
    {
        $this->Target = $target;

        return $this;
    }

    /**
     * Get Target
     *
     * @return \Eccube\Entity\Master\CustomerType 
     */
    public function getTarget()
    {
        return $this->Target;
    }

    /**
     * Set Creator
     *
     * @param \Eccube\Entity\Member $creator
     * @return Flyer
     */
    public function setCreator(\Eccube\Entity\Member $creator)
    {
        $this->Creator = $creator;

        return $this;
    }

    /**
     * Get Creator
     *
     * @return \Eccube\Entity\Member 
     */
    public function getCreator()
    {
        return $this->Creator;
    }
}
