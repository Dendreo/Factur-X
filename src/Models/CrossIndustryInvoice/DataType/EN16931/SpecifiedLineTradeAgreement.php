<?php

declare(strict_types=1);

namespace Dendreo\FacturX\DataType\EN16931;

use Dendreo\FacturX\DataType\Basic\GrossPriceProductTradePrice;
use Dendreo\FacturX\DataType\LineBuyerOrderReferencedDocument;
use Dendreo\FacturX\DataType\NetPriceProductTradePrice;
use Models\EN16931\BusinessTermsGroup\InvoiceLine;
use Models\EN16931\DataType\Reference\PurchaseOrderLineReference;

/**
 * BG-29.
 */
class SpecifiedLineTradeAgreement extends \Dendreo\FacturX\DataType\Basic\SpecifiedLineTradeAgreement
{
    /**
     * BT-132-00.
     */
    private ?LineBuyerOrderReferencedDocument $buyerOrderReferencedDocument;

    public function __construct(NetPriceProductTradePrice $netPriceProductTradePrice)
    {
        parent::__construct($netPriceProductTradePrice);

        $this->buyerOrderReferencedDocument = null;
    }

    public function getBuyerOrderReferencedDocument(): ?LineBuyerOrderReferencedDocument
    {
        return $this->buyerOrderReferencedDocument;
    }

    public function setBuyerOrderReferencedDocument(?LineBuyerOrderReferencedDocument $buyerOrderReferencedDocument): static
    {
        $this->buyerOrderReferencedDocument = $buyerOrderReferencedDocument;

        return $this;
    }

    public function toXML(\DOMDocument $document): \DOMElement
    {
        $currentNode = $document->createElement(self::XML_NODE);

        if ($this->buyerOrderReferencedDocument instanceof LineBuyerOrderReferencedDocument) {
            $currentNode->appendChild($this->buyerOrderReferencedDocument->toXML($document));
        }

        if ($this->grossPriceProductTradePrice instanceof GrossPriceProductTradePrice) {
            $currentNode->appendChild($this->getGrossPriceProductTradePrice()->toXML($document));
        }

        $currentNode->appendChild($this->getNetPriceProductTradePrice()->toXML($document));

        return $currentNode;
    }

    public static function fromXML(\DOMXPath $xpath, \DOMElement $currentElement): self
    {
        $specifiedLineTradeAgreementElements = $xpath->query(sprintf('./%s', self::XML_NODE), $currentElement);

        if (1 !== $specifiedLineTradeAgreementElements->count()) {
            throw new \Exception('Malformed');
        }

        /** @var \DOMElement $specifiedLineTradeAgreementElement */
        $specifiedLineTradeAgreementElement = $specifiedLineTradeAgreementElements->item(0);

        $buyerOrderReferencedDocument = LineBuyerOrderReferencedDocument::fromXML($xpath, $specifiedLineTradeAgreementElement);
        $grossPriceProductTradePrice  = GrossPriceProductTradePrice::fromXML($xpath, $specifiedLineTradeAgreementElement);
        $netPriceProductTradePrice    = NetPriceProductTradePrice::fromXML($xpath, $specifiedLineTradeAgreementElement);

        $specifiedLineTradeAgreement = new self($netPriceProductTradePrice);

        if ($buyerOrderReferencedDocument instanceof LineBuyerOrderReferencedDocument) {
            $specifiedLineTradeAgreement->setBuyerOrderReferencedDocument($buyerOrderReferencedDocument);
        }

        if ($grossPriceProductTradePrice instanceof GrossPriceProductTradePrice) {
            $specifiedLineTradeAgreement->setGrossPriceProductTradePrice($grossPriceProductTradePrice);
        }

        return $specifiedLineTradeAgreement;
    }

    public static function fromEN16931(InvoiceLine $invoiceLine): self
    {
        $buyerOrderReferencedDocument = $invoiceLine->getReferencedPurchaseOrderLineReference() instanceof PurchaseOrderLineReference
            ? LineBuyerOrderReferencedDocument::fromEN16931($invoiceLine)
            : null;

        return (new self(NetPriceProductTradePrice::fromEN16931($invoiceLine->getPriceDetails())))
            ->setGrossPriceProductTradePrice(
                \is_float($invoiceLine->getPriceDetails()->getItemGrossPrice())
                    ? GrossPriceProductTradePrice::fromEN16931($invoiceLine->getPriceDetails())
                    : null
            )
            ->setBuyerOrderReferencedDocument($buyerOrderReferencedDocument);
    }
}
