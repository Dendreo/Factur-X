<?php

declare(strict_types=1);

namespace Dendreo\FacturX\Models\CrossIndustryInvoice\DataType;

use Dendreo\FacturX\Models\EN16931\DataType\Identifier\SellerIdentifier;
use Dendreo\FacturX\Models\EN16931\DataType\InternationalCodeDesignator;

/**
 * BT-29-0 & BT-29-1.
 */
class SellerGlobalIdentifier extends SellerIdentifier
{
    protected const XML_NODE = 'ram:GlobalID';

    public function __construct(string $value, InternationalCodeDesignator $scheme)
    {
        parent::__construct($value, $scheme);
    }

    public function toXML(\DOMDocument $document): \DOMElement
    {
        // todo
    }

    public static function fromXML(\DOMXPath $xpath, \DOMElement $currentElement): array
    {
        $sellerGlobalIdentifierElements = $xpath->query(sprintf('./%s', self::XML_NODE), $currentElement);

        if (0 === $sellerGlobalIdentifierElements->count()) {
            return [];
        }

        $sellerGlobalIdentifiers = [];

        foreach ($sellerGlobalIdentifierElements as $sellerGlobalIdentifierElement) {
            $sellerGlobalIdentifier = $sellerGlobalIdentifierElement->nodeValue;
            $scheme                 = '' !== $sellerGlobalIdentifierElement->getAttribute('schemeID') ?
                InternationalCodeDesignator::tryFrom($sellerGlobalIdentifierElement->getAttribute('schemeID')) : null;

            if (null === $scheme) {
                throw new \Exception('Wrong schemeID');
            }

            $sellerGlobalIdentifiers[] = new self($sellerGlobalIdentifier, $scheme);
        }

        return $sellerGlobalIdentifiers;
    }
}
