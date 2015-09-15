Elcodi CartLineCoupon component for Symfony
========================================

# Table of contents

1. [Component](#component)
1. [Overview](#overview)
1. [Installation](#installation)
1. [Dependencies](#dependencies)
1. [Tests](#tests)
1. [Model layer](#model-layer)
  * [CartLineCoupon](#cartlinecoupon)
  * [OrderCoupon](#ordercoupon)
1. [Service layer](#service-layer)
  * [Services/CartLineCouponManager.php](#servicescartlinecouponmanagerphp)
  * [Services/CartLineCouponRuleManager.php](#servicescartlinecouponrulemanagerphp)
  * [Services/OrderCouponManager.php](#servicesordercouponmanagerphp)
1. [Event layer](#event-layer)
  * [CartLineCouponOnCheckEvent](#cartlinecoupononcheckevent)
  * [CartLineCouponOnApplyEvent](#cartlinecoupononapplyevent)
  * [CartLineCouponOnRemoveEvent](#cartlinecoupononremoveevent)
  * [CartLineCouponOnRejectedEvent](#cartlinecoupononrejectedevent)
  * [OrderCouponOnApplyEvent](#ordercoupononapplyevent)
1. [Event listeners](#event-listeners)
  * [AutomaticCouponApplicatorListener](#automaticcouponapplicatorlistener)
  * [AvoidDuplicatesListener](#avoidduplicateslistener)
  * [CartLineCouponManagerListener](#cartlinecouponmanagerlistener)
  * [CheckCartLineCouponListener](#checkcartlinecouponlistener)
  * [CheckCouponListener](#checkcouponlistener)
  * [CheckRulesListener](#checkruleslistener)
  * [ConvertToOrderCouponsListener](#converttoordercouponslistener)
  * [MinimumPriceCouponListener](#minimumpricecouponlistener)
  * [OrderCouponManagerListener](#ordercouponmanagerlistener)
  * [RefreshCouponsListener](#refreshcouponslistener)
1. [Tags](#tags)
1. [Contributing](#contributing)

# Component

This component is part of [elcodi project](https://github.com/elcodi).
Elcodi is a set of flexible e-commerce components for Symfony, built as a
decoupled and isolated repositories and under [MIT] license.

# Overview

The CartLineCoupon component closes the gap between CartLine and Coupon components,
managing relationship between the former and the latter through a set of tools.
You can see this components working on the [Bamboo] project to set discounts.

# Installation

You can use [Composer] to install this component getting the package from
[elcodi/cart-line-coupon packagist](https://packagist.org/packages/elcodi/cart-line-coupon)
by just executing the following line

``` bash
$ composer require "elcodi/cart-line-coupon:~0.5.*"
```

You can also do it manually by adding a line in your `composer.json` file

``` json
{
    "require": {
        "elcodi/cart-coupon": "~0.5.*"
    }
}
```

# Dependencies

The CartLineCoupon component has dependencies with:
- **PHP:** Version greater or equal to 5.4
- **doctrine/common:** A doctrine extension for php
- **doctrine/orm:** The doctrine object-relational mapping
- **symfony/event-dispatcher:** Events are dispatched at some points
- **elcodi/core:** Elcodi core component
- **elcodi/cart-line:** Elcodi cart-line component
- **elcodi/coupon:** Elcodi coupon component
- **elcodi/rule:** Elcodi rule component

# Tests

*Tests docs*

# Model layer

The model for this component simply adds a relationship between the pieces of
CartLine and Coupon, which are the following:

## CartLineCoupon

[View code](https://github.com/elcodi/CartLineCoupon/blob/master/Entity/CartLineCoupon.php)

A many-to-many relationship between a CartLine and a Coupon.

**Fields**
- **Id:** The identifier **(Unique)**
- **CartLine:** The `CartLineInterface` object.
- **Coupon:** The `CouponInterface` object.

## OrderCoupon

[View code](https://github.com/elcodi/CartLineCoupon/blob/master/Entity/OrderCoupon.php)

This models the same relationship as before, when the CartLine materializes to an OrderLine.

**Fields**
- **Id:** The identifier **(Unique)**
- **OrderLine:** The `OrderInterface` object.
- **Coupon:** The `CouponInterface` object.

# Service layer

These are the useful component services that you should know.

## Services/CartLineCouponManager.php

[View code](https://github.com/elcodi/CartLineCoupon/blob/master/Services/CartLineCouponManager.php)

This service manages coupon instances inside cartLines.

**Methods**
- **getCartLineCoupons(CartLineInterface *cartLine*)**: Get all *the relationships* between a given cartLine and its cupons.
- **getCoupons(CartLineInterface *cartLine*)**: Get all *the coupons* related to a given cartLine.
- **addCouponByCode(CartLineInterface $cartLine, $couponCode)**: Find a coupon by code and try to add it to a cartLine.
- **addCoupon(CartLineInterface *cartLine*, CouponInterface *coupon*)**: Add a coupon to a cartLine.
- **removeCouponByCode(CartLineInterface *cartLine*, *couponCode*)**: Remove a coupon by code from a cartLine.
- **removeCoupon(CartLineInterface *cartLine*, CouponInterface *coupon*)**: Remove a coupon from a cartLine.

## Services/CartLineCouponRuleManager.php

[View code](https://github.com/elcodi/CartLineCoupon/blob/master/Services/CartLineCouponRuleManager.php)

Helps with coupon coupon discount calculations through rules.

**Methods**
- **getCouponAmount(CartLineInterface *cartLine*, CouponInterface *coupon*)**: Validates a coupon Rule on a given CartLine.

**e.g.** *This service is used when a customer adds a product with a 2x1 promo, to calc discount amountto apply
on the current cart line*

## Services/OrderLineCouponManager.php

[View code](https://github.com/elcodi/CartLineCoupon/blob/master/Services/OrderLineCouponManager.php)

This service manages coupon instances inside orders.

**Methods**
- **getOrderLineCoupons(OrderLineInterface *orderLine*)**: Get all *the relationships* between a given orderLine and its cupons.
- **getCoupons(OrderLineInterface *orderLine*)**: Get all *the coupons* related to a given orderLine.

# Event layer

These are all the events for this bundle. You can get all the event names as
constant properties at the component
[ElcodiCartLineCouponEvents.php](https://github.com/elcodi/CartLineCoupon/blob/master/ElcodiCartLineCouponEvents.php)
file.

## CartLineCouponOnCheckEvent

Sent when we want to check for coupon validation in a cartLine context. If no listener
throws an `AbstractCouponException`, the check is considered valid. You can listen
to this event to ask for extra conditions.

**e.g.** *A product is added to cartLine and we check if current coupons still apply*

**Event properties**
- **Coupon**: The coupon to check validation
- **CartLine**: The context cartLine where to check the coupon

## CartLineCouponOnApplyEvent

Launched when a coupon has passed all validation and the relationship is being
created. You can still throw `AbstractCouponException` from those listeners to
avoid the generation of the relationship.

**e.g.** *A coupon is added to a cart line and we check if is duplicated*

**Event properties**
- **Coupon**: The coupon to check validation
- **CartLine**: The context cartLine where to check the coupon
- **CartLineCoupon**: The relationship that just has been created

Listeners with priority higher than 0 will not have access to the `CartLineCoupon` object.

## CartLineCouponOnRemoveEvent

Launched when an existing relationship between cartLine and coupon will be removed.

**e.g.** *A customer removes a product from the UI*

**Event properties**
- **Coupon**: The applied coupon
- **CartLine**: The cartLine where the coupon is applied
- **CartLineCoupon**: The relationship that would be removed

## CartLineCouponOnRejectedEvent

Launched after removing automatically an existing relationship between cartLine and coupon.
This event is purely instructive and can not be stopped.

**e.g.** *A coupon is removed from a cartLine because its conditions are no longer met*

**Event properties**
- **Coupon**: The coupon to check validation
- **CartLine**: The context cartLine where to check the coupon

## OrderLineCouponOnApplyEvent

Launched for each coupon in a cartLine while converting to orderLine.

**e.g.** *A customer purchases a cart*

**Event properties**
- **Coupon**: The coupon to apply
- **OrderLine**: The context orderLine where to apply the coupon
- **OrderLineCoupon**: The relationship that just has been created

Listeners with priority higher than 0 can stop the process by throwing exceptions
or calling `stopPropagation`, but will not have access to the `OrderLineCoupon` object.

# Event listeners

There are many listeners predefined for application.

## AutomaticCouponApplicatorListener

[View code](https://github.com/elcodi/CartLineCoupon/blob/master/EventListener/AutomaticCouponApplicatorListener.php)

Check in cart loading if any automatic coupon can be applied to cart lines.

## AvoidDuplicatesListener

[View code](https://github.com/elcodi/CartLineCoupon/blob/master/EventListener/AvoidDuplicatesListener.php)

Fails when a coupon is applied twice to any cartLine.

## CartLineCouponManagerListener

[View code](https://github.com/elcodi/CartLineCoupon/blob/master/EventListener/CartLineCouponManagerListener.php)

Manages creating and removing the actual relations between `CartLine` and `Coupon`.
This should be triggered with priority 0 to allow "pre" and "post" listeners.

## CheckCartLineCouponListener

[View code](https://github.com/elcodi/CartLineCoupon/blob/master/EventListener/CheckCartLineCouponListener.php)

Dispatches the check event when trying to apply a coupon to a CartLine.

## CheckCouponListener

[View code](https://github.com/elcodi/CartLineCoupon/blob/master/EventListener/CheckCouponListener.php)

Check for a coupon to be applicable.

## CheckRulesListener

[View code](https://github.com/elcodi/CartLineCoupon/blob/master/EventListener/CheckRulesListener.php)

Check for a coupon rule to be valid for a given cart.

## ConvertToOrderLineCouponsListener

[View code](https://github.com/elcodi/CartLineCoupon/blob/master/EventListener/ConvertToOrderLineCouponsListener.php)

Set up `CartLineCoupon` for conversion on order creation.

## MinimumPriceCouponListener

[View code](https://github.com/elcodi/CartLineCoupon/blob/master/EventListener/MinimumPriceCouponListener.php)

Check for a cart to exceed the coupon's minimum price to be appliable.

## OrderLineCouponManagerListener

[View code](https://github.com/elcodi/CartLineCoupon/blob/master/EventListener/OrderLineCouponManagerListener.php)

Add new `OrderLineCoupon` and notify coupon usage.
This should have priority 0 to allow "pre" and "post" listeners.


# Tags

* Use last unstable version (alias of `dev-master`) to stay always in last commit
* Use last stable version tag to stay in a stable release.

# Contributing

All issues and Pull Requests should be on the main repository
[elcodi/elcodi](https://github.com/elcodi/elcodi), so this one is read-only.

This projects follows Symfony coding standards, so pull requests must pass
phpcs checks. Read more details about
[Symfony coding standards](http://symfony.com/doc/current/contributing/code/standards.html)
and install the corresponding [CodeSniffer definition](https://github.com/escapestudios/Symfony2-coding-standard)
to run code validation.

There is also a policy for contributing to this project. Pull requests must
be explained step by step to make the review process easy in order to
accept and merge them. New features must come paired with PHPUnit tests.

If you would like to contribute, please read the [Contributing Code][1] in the
project documentation. If you are submitting a pull request, please follow the
guidelines in the [Submitting a Patch][2] section and use the
[Pull Request Template][3].

[1]: http://symfony.com/doc/current/contributing/code/index.html
[2]: http://symfony.com/doc/current/contributing/code/patches.html#check-list
[3]: http://symfony.com/doc/current/contributing/code/patches.html#make-a-pull-request
[MIT]: (http://opensource.org/licenses/MIT)
[Composer]: (https://getcomposer.org/)
[Bamboo]: https://github.com/elcodi/bamboo
