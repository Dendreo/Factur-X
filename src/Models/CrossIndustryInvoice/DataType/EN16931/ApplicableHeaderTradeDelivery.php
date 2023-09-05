<?php

declare(strict_types=1);

namespace Dendreo\FacturX\Models\CrossIndustryInvoice\DataType\EN16931;

use Dendreo\FacturX\Models\CrossIndustryInvoice\DataType\ActualDeliverySupplyChainEvent;
use Dendreo\FacturX\Models\CrossIndustryInvoice\DataType\DespatchAdviceReferencedDocument;
use Dendreo\FacturX\Models\CrossIndustryInvoice\DataType\OccurrenceDateTime;
use Dendreo\FacturX\Models\CrossIndustryInvoice\DataType\ReceivingAdviceReferencedDocument;
use Dendreo\FacturX\Models\CrossIndustryInvoice\DataType\ShipToTradeParty;
use Dendreo\FacturX\Models\EN16931\BusinessTermsGroup\DeliveryInformation;
use Dendreo\FacturX\Models\EN16931\DataType\Reference\DespatchAdviceReference;
use Dendreo\FacturX\Models\EN16931\DataType\Reference\ReceivingAdviceReference;
use Dendreo\FacturX\Models\EN16931\Invoice;

/**
 * BG-13-00.
 */
class ApplicableHeaderTradeDelivery extends \Dendreo\FacturX\Models\CrossIndustryInvoice\DataType\BasicWL\ApplicableHeaderTradeDelivery
{
    /**
     * BT-15-00.
     */
    private ?ReceivingAdviceReferencedDocument $receivingAdviceReferencedDocument;

    public function __construct()
    {
        parent::__construct();

        $this->receivingAdviceReferencedDocument = null;
    }

    public function getReceivingAdviceReferencedDocument(): ?ReceivingAdviceReferencedDocument
    {
        return $this->receivingAdviceReferencedDocument;
    }

    public function setReceivingAdviceReferencedDocument(?ReceivingAdviceReferencedDocument $receivingAdviceReferencedDocument): static
    {
        $this->receivingAdviceReferencedDocument = $receivingAdviceReferencedDocument;

        return $this;
    }

    public function toXML(\DOMDocument $document): \DOMElement
    {
        $currentNode = $document->createElement(self::XML_NODE);

        if ($this->shipToTradeParty instanceof ShipToTradeParty) {
            $currentNode->appendChild($this->shipToTradeParty->toXML($document));
        }

        if ($this->actualDeliverySupplyChainEvent instanceof ActualDeliverySupplyChainEvent) {
            $currentNode->appendChild($this->actualDeliverySupplyChainEvent->toXML($document));
        }

        if ($this->despatchAdviceReferencedDocument instanceof DespatchAdviceReferencedDocument) {
            $currentNode->appendChild($this->despatchAdviceReferencedDocument->toXML($document));
        }

        if ($this->receivingAdviceReferencedDocument instanceof ReceivingAdviceReferencedDocument) {
            $currentNode->appendChild($this->receivingAdviceReferencedDocument->toXML($document));
        }

        return $currentNode;
    }

    public static function fromXML(\DOMXPath $xpath, \DOMElement $currentElement): self
    {
        $applicableHeaderTradeDeliveryElements = $xpath->query(sprintf('./%s', self::XML_NODE), $currentElement);

        if (1 !== $applicableHeaderTradeDeliveryElements->count()) {
            throw new \Exception('Malformed');
        }

        /** @var \DOMElement $applicableHeaderTradeDeliveryElement */
        $applicableHeaderTradeDeliveryElement = $applicableHeaderTradeDeliveryElements->item(0);

        $shipToTradeParty                  = ShipToTradeParty::fromXML($xpath, $applicableHeaderTradeDeliveryElement);
        $actualDeliverySupplyChainEvent    = ActualDeliverySupplyChainEvent::fromXML($xpath, $applicableHeaderTradeDeliveryElement);
        $despatchAdviceReferencedDocument  = DespatchAdviceReferencedDocument::fromXML($xpath, $applicableHeaderTradeDeliveryElement);
        $receivingAdviceReferencedDocument = ReceivingAdviceReferencedDocument::fromXML($xpath, $applicableHeaderTradeDeliveryElement);

        $applicableHeaderTradeDelivery = new self();

        if ($shipToTradeParty instanceof ShipToTradeParty) {
            $applicableHeaderTradeDelivery->setShipToTradeParty($shipToTradeParty);
        }

        if ($actualDeliverySupplyChainEvent instanceof ActualDeliverySupplyChainEvent) {
            $applicableHeaderTradeDelivery->setActualDeliverySupplyChainEvent($actualDeliverySupplyChainEvent);
        }

        if ($despatchAdviceReferencedDocument instanceof DespatchAdviceReferencedDocument) {
            $applicableHeaderTradeDelivery->setDespatchAdviceReferencedDocument($despatchAdviceReferencedDocument);
        }

        if ($receivingAdviceReferencedDocument instanceof ReceivingAdviceReferencedDocument) {
            $applicableHeaderTradeDelivery->setReceivingAdviceReferencedDocument($receivingAdviceReferencedDocument);
        }

        return $applicableHeaderTradeDelivery;
    }

    public static function fromEN16931(Invoice $invoice): self
    {
        return (new self())
            ->setShipToTradeParty(
                $invoice->getDeliveryInformation() instanceof DeliveryInformation
                    ? ShipToTradeParty::fromEN16931($invoice->getDeliveryInformation())
                    : null
            )
            ->setActualDeliverySupplyChainEvent(
                $invoice->getDeliveryInformation()?->getActualDeliveryDate() instanceof \DateTimeInterface
                    ? new ActualDeliverySupplyChainEvent(new OccurrenceDateTime($invoice->getDeliveryInformation()->getActualDeliveryDate()))
                    : null
            )
            ->setDespatchAdviceReferencedDocument(
                $invoice->getDespatchAdviceReference() instanceof DespatchAdviceReference
                    ? new DespatchAdviceReferencedDocument($invoice->getDespatchAdviceReference())
                    : null
            )
            ->setReceivingAdviceReferencedDocument(
                $invoice->getReceivingAdviceReference() instanceof ReceivingAdviceReference
                    ? new ReceivingAdviceReferencedDocument($invoice->getReceivingAdviceReference())
                    : null
            );
    }
}
