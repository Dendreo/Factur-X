<?php

declare(strict_types=1);

namespace Dendreo\FacturX\Models\CrossIndustryInvoice\DataType\BasicWL;

use Dendreo\FacturX\Models\CrossIndustryInvoice\DataType\SellerGlobalIdentifier;
use Dendreo\FacturX\Models\CrossIndustryInvoice\DataType\SpecifiedTaxRegistrationVA;
use Dendreo\FacturX\Models\CrossIndustryInvoice\DataType\URIUniversalCommunication;
use Dendreo\FacturX\Models\EN16931\DataType\Identifier\SellerIdentifier;

/**
 * BG-4.
 */
class SellerTradeParty extends \Dendreo\FacturX\Models\CrossIndustryInvoice\DataType\Minimum\SellerTradeParty
{
    /**
     * BT-29.
     *
     * @var array<int, SellerIdentifier>
     */
    protected array $identifiers;

    /**
     * BT-29-0 & BT-29-1.
     *
     * @var array<int, SellerGlobalIdentifier>
     */
    protected array $globalIdentifiers;

    /**
     * BT-34-00.
     */
    protected ?URIUniversalCommunication $URIUniversalCommunication;

    public function __construct(string $name, PostalTradeAddress $postalTradeAddress, ?SpecifiedTaxRegistrationVA $specifiedTaxRegistration = null)
    {
        parent::__construct($name, $postalTradeAddress);

        $this->identifiers               = [];
        $this->globalIdentifiers         = [];
        $this->URIUniversalCommunication = null;
        $this->specifiedTaxRegistrationVA = $specifiedTaxRegistration;
    }

    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }

    public function setIdentifiers(array $identifiers): static
    {
        $tmpIdentifiers = [];

        foreach ($identifiers as $identifier) {
            if (!$identifier instanceof SellerIdentifier) {
                throw new \TypeError();
            }

            $tmpIdentifiers[] = $identifier;
        }

        $this->identifiers = $tmpIdentifiers;

        return $this;
    }

    public function getGlobalIdentifiers(): array
    {
        return $this->globalIdentifiers;
    }

    public function setGlobalIdentifiers(array $globalIdentifiers): static
    {
        $tmpGlobalIdentifiers = [];

        foreach ($globalIdentifiers as $globalIdentifier) {
            if (!$globalIdentifier instanceof SellerGlobalIdentifier) {
                throw new \TypeError();
            }

            $tmpGlobalIdentifiers[] = $globalIdentifier;
        }

        $this->globalIdentifiers = $tmpGlobalIdentifiers;

        return $this;
    }

    public function setURIUniversalCommunication(?URIUniversalCommunication $URIUniversalCommunication): static
    {
        $this->URIUniversalCommunication = $URIUniversalCommunication;

        return $this;
    }

    public function toXML(\DOMDocument $document): \DOMElement
    {
        $currentNode = $document->createElement(self::XML_NODE);

        foreach ($this->identifiers as $identifier) {
            $currentNode->appendChild($document->createElement('ram:ID', $identifier->value));
        }

        foreach ($this->globalIdentifiers as $globalIdentifier) { // move inside class
            $globalIdentifierElement = $document->createElement('ram:GlobalID', $globalIdentifier->value);
            $globalIdentifierElement->setAttribute('schemeID', $globalIdentifier->scheme->value);
            $currentNode->appendChild($globalIdentifierElement);
        }

        $currentNode->appendChild($document->createElement('ram:Name', $this->name));

        if ($this->specifiedLegalOrganization instanceof SellerSpecifiedLegalOrganization) {
            $currentNode->appendChild($this->specifiedLegalOrganization->toXML($document));
        }

        $currentNode->appendChild($this->postalTradeAddress->toXML($document));

        if ($this->URIUniversalCommunication instanceof URIUniversalCommunication) {
            $currentNode->appendChild($this->URIUniversalCommunication->toXML($document));
        }

        if ($this->specifiedTaxRegistrationVA instanceof SpecifiedTaxRegistrationVA) {
            $currentNode->appendChild($this->specifiedTaxRegistrationVA->toXML($document));
        }

        return $currentNode;
    }

    public static function fromXML(\DOMXPath $xpath, \DOMElement $currentElement): self
    {
        $sellerTradePartyElements = $xpath->query(sprintf('./%s', self::XML_NODE), $currentElement);

        if ($sellerTradePartyElements->count() !== 1) {
            throw new \Exception('Malformed');
        }

        /** @var \DOMElement $sellerTradePartyElement */
        $sellerTradePartyElement = $sellerTradePartyElements->item(0);

        $identifierElements = $xpath->query('./ram:ID', $sellerTradePartyElement);

        $identifiers = [];

        foreach ($identifierElements as $identifierElement) {
            $identifier = $identifierElement->nodeValue;

            $identifiers[] = new SellerIdentifier($identifier);
        }

        $nameElements = $xpath->query('./ram:Name', $sellerTradePartyElement);

        if ($nameElements->count() !== 1) {
            throw new \Exception('Malformed');
        }

        $name = $nameElements->item(0)->nodeValue;

        $globalIdentifiers          = SellerGlobalIdentifier::fromXML($xpath, $sellerTradePartyElement);
        $specifiedLegalOrganization = SellerSpecifiedLegalOrganization::fromXML($xpath, $sellerTradePartyElement);
        $postalTradeAddress         = PostalTradeAddress::fromXML($xpath, $sellerTradePartyElement);
        $URIUniversalCommunication  = URIUniversalCommunication::fromXML($xpath, $sellerTradePartyElement);
        $specifiedTaxRegistrationVA = SpecifiedTaxRegistrationVA::fromXML($xpath, $sellerTradePartyElement);

        if (!$postalTradeAddress instanceof PostalTradeAddress) {
            throw new \Exception('Malformed');
        }

        $sellerTradeParty = new self($name, $postalTradeAddress);

        if (\count($identifiers) > 0) {
            $sellerTradeParty->setIdentifiers($identifiers);
        }

        if (\count($globalIdentifiers) > 0) {
            $sellerTradeParty->setGlobalIdentifiers($globalIdentifiers);
        }

        if ($specifiedLegalOrganization instanceof SellerSpecifiedLegalOrganization) {
            $sellerTradeParty->setSpecifiedLegalOrganization($specifiedLegalOrganization);
        }

        if ($URIUniversalCommunication instanceof URIUniversalCommunication) {
            $sellerTradeParty->setURIUniversalCommunication($URIUniversalCommunication);
        }

        if ($specifiedTaxRegistrationVA instanceof SpecifiedTaxRegistrationVA) {
            $sellerTradeParty->setSpecifiedTaxRegistrationVA($specifiedTaxRegistrationVA);
        }

        return $sellerTradeParty;
    }
}
