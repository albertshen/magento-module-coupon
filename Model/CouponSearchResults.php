<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
declare(strict_types=1);

namespace AlbertMage\Coupon\Model;

use AlbertMage\Coupon\Api\Data\CouponSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Service Data Object with Coupon search results.
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class CouponSearchResults extends SearchResults implements CouponSearchResultsInterface
{
}
