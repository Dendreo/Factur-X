<?php

declare(strict_types=1);

namespace Dendreo\FacturX\Models\CrossIndustryInvoice\DataType\Basic;

use Dendreo\FacturX\Models\EN16931\BusinessTermsGroup\InvoiceLine;

/**
 * BT-129-00.
 */
class SpecifiedLineTradeDelivery
{
    protected const XML_NODE = 'ram:SpecifiedLineTradeDelivery';

    /**
     * BT-129 & BT-130.
     */
    private BilledQuantity $billedQuantity;

    public function __construct(BilledQuantity $billedQuantity)
    {
        $this->billedQuantity = $billedQuantity;
    }

    public function getBilledQuantity(): BilledQuantity
    {
        return $this->billedQuantity;
    }

    public function toXML(\DOMDocument $document): \DOMElement
    {
        $element = $document->createElement(self::XML_NODE);

        $element->appendChild($this->billedQuantity->toXML($document));

        return $element;
    }

    public static function fromXML(\DOMXPath $xpath, \DOMElement $currentElement): self
    {
        $specifiedLineTradeDeliveryElements = $xpath->query(sprintf('./%s', self::XML_NODE), $currentElement);

        if (1 !== $specifiedLineTradeDeliveryElements->count()) {
            throw new \Exception('Malformed');
        }

        /** @var \DOMElement $specifiedLineTradeDeliveryElement */
        $specifiedLineTradeDeliveryElement = $specifiedLineTradeDeliveryElements->item(0);

        $billedQuantity = BilledQuantity::fromXML($xpath, $specifiedLineTradeDeliveryElement);

        return new self($billedQuantity);
    }

    public static function fromEN16931(InvoiceLine $invoiceLine): self
    {
        return new self(new BilledQuantity(
            $invoiceLine->getInvoicedQuantity(),
            $invoiceLine->getInvoicedQuantityUnitOfMeasureCode()
        ));
    }
}
