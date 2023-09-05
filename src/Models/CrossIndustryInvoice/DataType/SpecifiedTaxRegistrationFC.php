<?php

declare(strict_types=1);

namespace Dendreo\FacturX\DataType;

use Models\EN16931\DataType\Identifier\VatIdentifier;

/**
 * BT-32-00.
 */
class SpecifiedTaxRegistrationFC
{
    protected const XML_NODE = 'ram:SpecifiedTaxRegistration';

    /**
     * BT-32.
     */
    private VatIdentifier $identifier;

    /**
     * BT-32-0.
     */
    private string $schemeIdentifier;

    public function __construct(VatIdentifier $identifier)
    {
        $this->identifier       = $identifier;
        $this->schemeIdentifier = 'FC';
    }

    public function getIdentifier(): VatIdentifier
    {
        return $this->identifier;
    }

    public function getSchemeIdentifier(): string
    {
        return $this->schemeIdentifier;
    }

    public function toXML(\DOMDocument $document): \DOMElement
    {
        $currentNode = $document->createElement(self::XML_NODE);

        $identifierElement = $document->createElement('ram:ID', $this->identifier->getValue());
        $identifierElement->setAttribute('schemeID', $this->schemeIdentifier);
        $currentNode->appendChild($identifierElement);

        return $currentNode;
    }

    public static function fromXML(\DOMXPath $xpath, \DOMElement $currentElement): ?self
    {
        $specifiedTaxRegistrationElements = $xpath->query(sprintf('./%s[ram:ID[@schemeID = \'FC\']]', self::XML_NODE), $currentElement);

        if (0 === $specifiedTaxRegistrationElements->count()) {
            return null;
        }

        if ($specifiedTaxRegistrationElements->count() > 1) {
            throw new \Exception('Malformed');
        }

        /** @var \DOMElement $specifiedTaxRegistrationElement */
        $specifiedTaxRegistrationElement = $specifiedTaxRegistrationElements->item(0);

        $identifierElements = $xpath->query('./ram:ID', $specifiedTaxRegistrationElement);

        if (1 !== $identifierElements->count()) {
            throw new \Exception('Malformed');
        }

        $identifierItem = $identifierElements->item(0);
        $identifier     = $identifierItem->nodeValue;

        $schemeIdentifier = null;

        if ($identifierItem->hasAttribute('schemeID')) {
            $schemeIdentifier = $identifierItem->getAttribute('schemeID');
        }

        if (!\is_string($schemeIdentifier)) {
            throw new \Exception('Malformed');
        }

        if ('FC' !== $schemeIdentifier) {
            throw new \Exception('Wrong schemeID');
        }

        return new self(new VatIdentifier($identifier));
    }
}
