<?php

/*
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014-2015 Elcodi.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @author Aldo Chiecchia <zimage@tiscali.it>
 * @author Elcodi Team <tech@elcodi.com>
 */

namespace Elcodi\Component\CartLineCoupon;

/**
 * ElcodiCartLineCouponEvents
 */
final class ElcodiCartLineCouponEvents
{
    /**
     * This event is dispatched while checking if a Coupon applies to a CartLine
     *
     * event.name : cart_line_coupon.oncheck
     * event.class : CartLineCouponOnCheckEvent
     */
    const CART_LINE_COUPON_ONCHECK = 'cart_line_coupon.oncheck';

    /**
     * This event is dispatched each time a coupon is applied into a CartLine
     *
     * event.name : cart_line_coupon.onapply
     * event.class : CartLineCouponOnApplyEvent
     */
    const CART_LINE_COUPON_ONAPPLY = 'cart_line_coupon.onapply';

    /**
     * This event is dispatched each time a coupon is removed from a CartLine
     *
     * event.name : cart_line_coupon.onremove
     * event.class : CartLineCouponOnRemoveEvent
     */
    const CART_LINE_COUPON_ONREMOVE = 'cart_line_coupon.onremove';

    /**
     * This event is dispatched each time a coupon is rejected from a CartLine
     *
     * event.name : cart_line_coupon.onrejected
     * event.class : CartLineCouponOnRejectedEvent
     */
    const CART_LINE_COUPON_ONREJECTED = 'cart_line_coupon.onrejected';

    /**
     * This event is dispatched each time a coupon is applied into an OrderLine
     *
     * event.name : order_line_coupon.onapply
     * event.class : OrderLineCouponOnApplyEvent
     */
    const ORDER_LINE_COUPON_ONAPPLY = 'order_line_coupon.onapply';
}
