<?php
namespace Espo\Modules\ProcumanCE\Hooks\CPurchaseOrderLine;

use Espo\ORM\Entity;
use Espo\ORM\EntityManager;

class CalculateTotals
{
    public function __construct(private EntityManager $entityManager)
    {}

    public function afterSave(Entity $entity, array $options): void
    {
        $this->calculateTotals($entity);
    }

    public function afterRelate(Entity $entity, array $options): void
    {
        $this->calculateTotals($entity);
    }

    public function afterRemove(Entity $entity, array $options): void
    {
        // For afterRemove, we need to get the purchaseOrderId from the entity before it was removed
        $purchaseOrderId = $entity->get('purchaseOrderId');
        
        if (!$purchaseOrderId) {
            return;
        }
        
        $this->calculateTotalsForPurchaseOrder($purchaseOrderId);
    }

    private function calculateTotals(Entity $entity): void
    {
        // Get the parent CPurchaseOrder ID from the CPurchaseOrderLine
        $purchaseOrderId = $entity->get('purchaseOrderId');
        
        if (!$purchaseOrderId) {
            return;
        }
        
        $this->calculateTotalsForPurchaseOrder($purchaseOrderId);
    }

    private function calculateTotalsForPurchaseOrder(string $purchaseOrderId): void
    {
        // Load the parent CPurchaseOrder entity
        $purchaseOrder = $this->entityManager->getEntityById('CPurchaseOrder', $purchaseOrderId);
        
        if (!$purchaseOrder) {
            return;
        }
        
        // Find all CPurchaseOrderLine records related to this CPurchaseOrder
        $purchaseOrderLineList = $this->entityManager
            ->getRDBRepository('CPurchaseOrderLine')
            ->where(['purchaseOrderId' => $purchaseOrderId])
            ->find();
        
        // Calculate the total sums of amount, taxAmount, and grandTotal fields
        $totalAmount = 0;
        $totalTaxAmount = 0;
        $totalGrandTotal = 0;
        
        foreach ($purchaseOrderLineList as $line) {
            // Calculate amount total
            $amount = $line->get('amount');
            if ($amount !== null && is_numeric($amount)) {
                $totalAmount += (float) $amount;
            }
            
            // Calculate taxAmount total
            $taxAmount = $line->get('taxAmount');
            if ($taxAmount !== null && is_numeric($taxAmount)) {
                $totalTaxAmount += (float) $taxAmount;
            }
            
            // Calculate grandTotal total
            $grandTotal = $line->get('grandTotal');
            if ($grandTotal !== null && is_numeric($grandTotal)) {
                $totalGrandTotal += (float) $grandTotal;
            }
        }
        
        // Update the CPurchaseOrder's total fields
        $purchaseOrder->set('totalAmount', $totalAmount);
        $purchaseOrder->set('taxAmount', $totalTaxAmount);
        $purchaseOrder->set('grandTotal', $totalGrandTotal);
        
        // Save the CPurchaseOrder entity
        $this->entityManager->saveEntity($purchaseOrder);
    }
}
