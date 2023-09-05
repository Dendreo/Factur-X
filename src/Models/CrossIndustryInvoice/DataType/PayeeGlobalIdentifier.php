<?php

declare(strict_types=1);

namespace Dendreo\FacturX\Models\CrossIndustryInvoice\DataType;

use Dendreo\FacturX\Models\EN16931\DataType\Identifier\PayeeIdentifier;
use Dendreo\FacturX\Models\EN16931\DataType\InternationalCodeDesignator;

/**
 * BT-60-0 & BT-60-1.
 */
class PayeeGlobalIdentifier extends PayeeIdentifier
{
    protected const XML_NODE = 'ram:GlobalID';

    public function __construct(string $value, InternationalCodeDesignator $scheme)
    {
        parent::__construct($value, $scheme);
    }

    public static function fromXML(\DOMXPath $xpath, \DOMElement $currentElement): ?self
    {
        $payeeGlobalIdentifierElements = $xpath->query(sprintf('./%s', self::XML_NODE), $currentElement);

        if (0 === $payeeGlobalIdentifierElements->count()) {
            return null;
        }

        if ($payeeGlobalIdentifierElements->count() > 1) {
            throw new \Exception('Malformed');
        }

        /** @var \DOMElement $payeeGlobalIdentifierElement */
        $payeeGlobalIdentifierElement = $payeeGlobalIdentifierElements->item(0);

        $identifier = $payeeGlobalIdentifierElement->nodeValue;
        $scheme     = '' !== $payeeGlobalIdentifierElement->getAttribute('schemeID') ?
            InternationalCodeDesignator::tryFrom($payeeGlobalIdentifierElement->getAttribute('schemeID')) : null;

        if (null === $scheme) {
            throw new \Exception('Wrong schemeID');
        }

        return new self($identifier, $scheme);
    }
}
