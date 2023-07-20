<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Coupon\Api\Data;

/**
 * Interface for node search results.
 * @api
 * @author Albert Shen <albertshen1206@gmail.com>
 */
interface CouponSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get node list.
     *
     * @return \AlbertMage\Coupon\Api\Data\CouponInterface[]
     */
    public function getItems();

    /**
     * Set node list.
     *
     * @param \AlbertMage\Coupon\Api\Data\CouponInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
