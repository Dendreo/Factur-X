<?php

declare(strict_types=1);

namespace Dendreo\FacturX\Models\CrossIndustryInvoice\DataType\Minimum;

use Dendreo\FacturX\Models\EN16931\DataType\CountryAlpha2Code;

/**
 * BG-5 or BG-8 or BG-15.
 */
class PostalTradeAddress
{
    protected const XML_NODE = 'ram:PostalTradeAddress';

    /**
     * BT-40 or BT-55 or BT-80.
     */
    protected CountryAlpha2Code $countryIdentifier;

    public function __construct(CountryAlpha2Code $countryIdentifier)
    {
        $this->countryIdentifier = $countryIdentifier;
    }

    public function getCountryIdentifier(): CountryAlpha2Code
    {
        return $this->countryIdentifier;
    }

    public function toXML(\DOMDocument $document): \DOMElement
    {
        $currentNode = $document->createElement(self::XML_NODE);

        $currentNode->appendChild($document->createElement('ram:CountryID', $this->countryIdentifier->value));

        return $currentNode;
    }

    /**
     * PostalTradeAddress is mandatory everywhere except for "ShipToTradeParty" which is optional
     * BasicWL\PostalTradeAddress have to return ?static
     * Because of the heritage, Minimum\PostalTradeAddress have to return ?static too.
     */
    public static function fromXML(\DOMXPath $xpath, \DOMElement $currentElement): ?self
    {
        $postalTradeAddressElements = $xpath->query(sprintf('./%s', self::XML_NODE), $currentElement);

        if (1 !== $postalTradeAddressElements->count()) {
            throw new \Exception('Malformed');
        }

        /** @var \DOMElement $postalTradeAddressElement */
        $postalTradeAddressElement = $postalTradeAddressElements->item(0);

        $countryIdentifierElements = $xpath->query('./ram:CountryID', $postalTradeAddressElement);

        if (1 !== $countryIdentifierElements->count()) {
            throw new \Exception('Malformed');
        }

        $countryIdentifier = CountryAlpha2Code::tryFrom($countryIdentifierElements->item(0)->nodeValue);

        if (null === $countryIdentifier) {
            throw new \Exception('Wrong country');
        }

        return new self($countryIdentifier);
    }
}
