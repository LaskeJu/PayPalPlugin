<?php

namespace Sylius\PayPalPlugin\Provider;

use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

final class OrderItemNonNeutralTaxProvider implements OrderItemNonNeutralTaxProviderInterface
{
    public function provide(OrderItemInterface $orderItem): int
    {
        $taxTotal = 0;

        foreach ($orderItem->getAdjustments(AdjustmentInterface::TAX_ADJUSTMENT)->toArray() as $taxAdjustment) {
            if(!$taxAdjustment->isNeutral()){
                $taxTotal += $taxAdjustment->getAmount();
            }
        }

        foreach ($orderItem->getUnits()->toArray() as $unit) {
            foreach ($unit->getAdjustments(AdjustmentInterface::TAX_ADJUSTMENT)->toArray() as $taxAdjustment) {
                if(!$taxAdjustment->isNeutral()){
                    $taxTotal += $taxAdjustment->getAmount();
                    return $taxTotal;
                }
            }
        }

        return $taxTotal;
    }
}
