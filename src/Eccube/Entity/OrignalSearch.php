<?php

namespace Eccube\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrignalSearch
 */
class OrignalSearch extends \Eccube\Entity\AbstractEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $target_type;

    /**
     * @var string
     */
    private $search_name;

    /**
     * @var string
     */
    private $search_value;

    /**
     * @var \DateTime
     */
    private $create_date;

    /**
     * @var \DateTime
     */
    private $update_date;

    /**
     * @var integer
     */
    private $del_flg = '0';

    /**
     * @var \Eccube\Entity\Member
     */
    private $Creator;

    /**
     * @var \Eccube\Entity\Member
     */
    private $Updater;


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
     * Set target_type
     *
     * @param string $targetType
     * @return OrignalSearch
     */
    public function setTargetType($targetType)
    {
        $this->target_type = $targetType;

        return $this;
    }

    /**
     * Get target_type
     *
     * @return string 
     */
    public function getTargetType()
    {
        return $this->target_type;
    }

    /**
     * Set search_name
     *
     * @param string $searchName
     * @return OrignalSearch
     */
    public function setSearchName($searchName)
    {
        $this->search_name = $searchName;

        return $this;
    }

    /**
     * Get search_name
     *
     * @return string 
     */
    public function getSearchName()
    {
        return $this->search_name;
    }

    /**
     * Set search_value
     *
     * @param string $searchValue
     * @return OrignalSearch
     */
    public function setSearchValue($searchValue)
    {
        $this->search_value = $searchValue;

        return $this;
    }

    /**
     * Get search_value
     *
     * @return string 
     */
    public function getSearchValue()
    {
        return $this->search_value;
    }

    /**
     * Set create_date
     *
     * @param \DateTime $createDate
     * @return OrignalSearch
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
     * Set update_date
     *
     * @param \DateTime $updateDate
     * @return OrignalSearch
     */
    public function setUpdateDate($updateDate)
    {
        $this->update_date = $updateDate;

        return $this;
    }

    /**
     * Get update_date
     *
     * @return \DateTime 
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * Set del_flg
     *
     * @param integer $delFlg
     * @return OrignalSearch
     */
    public function setDelFlg($delFlg)
    {
        $this->del_flg = $delFlg;

        return $this;
    }

    /**
     * Get del_flg
     *
     * @return integer 
     */
    public function getDelFlg()
    {
        return $this->del_flg;
    }

    /**
     * Set Creator
     *
     * @param \Eccube\Entity\Member $creator
     * @return OrignalSearch
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

    /**
     * Set Updater
     *
     * @param \Eccube\Entity\Member $updater
     * @return OrignalSearch
     */
    public function setUpdater(\Eccube\Entity\Member $updater)
    {
        $this->Updater = $updater;

        return $this;
    }

    /**
     * Get Updater
     *
     * @return \Eccube\Entity\Member 
     */
    public function getUpdater()
    {
        return $this->Updater;
    }
}
