<?php

declare(strict_types=1);

namespace Dendreo\FacturX\DataType;

use Models\EN16931\DataType\Reference\ContractReference;

/**
 * BT-12-00.
 */
class ContractReferencedDocument
{
    protected const XML_NODE = 'ram:ContractReferencedDocument';

    /**
     * BT-12.
     */
    private ContractReference $issuerAssignedIdentifier;

    public function __construct(ContractReference $issuerAssignedIdentifier)
    {
        $this->issuerAssignedIdentifier = $issuerAssignedIdentifier;
    }

    public function getIssuerAssignedIdentifier(): ContractReference
    {
        return $this->issuerAssignedIdentifier;
    }

    public function toXML(\DOMDocument $document): \DOMElement
    {
        $currentNode = $document->createElement(self::XML_NODE);

        $currentNode->appendChild($document->createElement('ram:IssuerAssignedID', $this->issuerAssignedIdentifier->value));

        return $currentNode;
    }

    public static function fromXML(\DOMXPath $xpath, \DOMElement $currentElement): ?self
    {
        $contractReferencedDocumentElements = $xpath->query(sprintf('./%s', self::XML_NODE), $currentElement);

        if (0 === $contractReferencedDocumentElements->count()) {
            return null;
        }

        if ($contractReferencedDocumentElements->count() > 1) {
            throw new \Exception('Malformed');
        }

        /** @var \DOMElement $contractReferencedDocumentElement */
        $contractReferencedDocumentElement = $contractReferencedDocumentElements->item(0);

        $issuerAssignedIdentifierElements = $xpath->query('./ram:IssuerAssignedID', $contractReferencedDocumentElement);

        if (1 !== $issuerAssignedIdentifierElements->count()) {
            throw new \Exception('Malformed');
        }

        $issuerAssignedIdentifier = $issuerAssignedIdentifierElements->item(0)->nodeValue;

        return new self(new ContractReference($issuerAssignedIdentifier));
    }
}
