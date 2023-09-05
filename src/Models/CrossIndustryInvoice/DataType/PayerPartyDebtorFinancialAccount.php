<?php

declare(strict_types=1);

namespace Dendreo\FacturX\Models\CrossIndustryInvoice\DataType;

use Dendreo\FacturX\Models\EN16931\DataType\Identifier\DebitedAccountIdentifier;

/**
 * BT-91-00.
 */
class PayerPartyDebtorFinancialAccount
{
    protected const XML_NODE = 'ram:PayerPartyDebtorFinancialAccount';

    /**
     * BT-91.
     */
    private DebitedAccountIdentifier $ibanIdentifier;

    public function __construct(DebitedAccountIdentifier $ibanIdentifier)
    {
        $this->ibanIdentifier = $ibanIdentifier;
    }

    public function getIbanIdentifier(): DebitedAccountIdentifier
    {
        return $this->ibanIdentifier;
    }

    public function toXML(\DOMDocument $document): \DOMElement
    {
        $element = $document->createElement(self::XML_NODE);

        $element->appendChild($document->createElement('ram:IBANID', $this->ibanIdentifier->value));

        return $element;
    }

    public static function fromXML(\DOMXPath $xpath, \DOMElement $currentElement): ?self
    {
        $payerPartyDebtorFinancialAccountElements = $xpath->query(sprintf('./%s', self::XML_NODE), $currentElement);

        if (0 === $payerPartyDebtorFinancialAccountElements->count()) {
            return null;
        }

        if ($payerPartyDebtorFinancialAccountElements->count() > 1) {
            throw new \Exception('Malformed');
        }

        /** @var \DOMElement $payerPartyDebtorFinancialAccountElement */
        $payerPartyDebtorFinancialAccountElement = $payerPartyDebtorFinancialAccountElements->item(0);

        $ibanIdentifierElements = $xpath->query('./ram:IBANID', $payerPartyDebtorFinancialAccountElement);

        if (1 !== $ibanIdentifierElements->count()) {
            throw new \Exception('Malformed');
        }

        $ibanIdentifier = $ibanIdentifierElements->item(0)->nodeValue;

        return new self(new DebitedAccountIdentifier($ibanIdentifier));
    }
}
