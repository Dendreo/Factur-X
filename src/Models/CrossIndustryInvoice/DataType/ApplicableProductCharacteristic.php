<?php

declare(strict_types=1);

namespace Dendreo\FacturX\DataType;

/**
 * BG-32.
 */
class ApplicableProductCharacteristic
{
    protected const XML_NODE = 'ram:ApplicableProductCharacteristic';

    /**
     * BT-160.
     */
    private string $description;

    /**
     * BT-161.
     */
    private string $value;

    public function __construct(string $description, string $value)
    {
        $this->description = $description;
        $this->value       = $value;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function toXML(\DOMDocument $document): \DOMElement
    {
        $currentNode = $document->createElement(self::XML_NODE);

        $currentNode->appendChild($document->createElement('ram:Description', $this->description));
        $currentNode->appendChild($document->createElement('ram:Value', $this->value));

        return $currentNode;
    }

    public static function fromXML(\DOMXPath $xpath, \DOMElement $currentElement): array
    {
        $applicableProductCharacteristicElements = $xpath->query(sprintf('./%s', self::XML_NODE), $currentElement);

        if (0 === $applicableProductCharacteristicElements->count()) {
            return [];
        }

        $applicableProductCharacteristics = [];

        foreach ($applicableProductCharacteristicElements as $applicableProductCharacteristicElement) {
            $descriptionElements = $xpath->query('./ram:Description', $applicableProductCharacteristicElement);
            $valueElements       = $xpath->query('./ram:Value', $applicableProductCharacteristicElement);

            if (1 !== $descriptionElements->count()) {
                throw new \Exception('Malformed');
            }

            if (1 !== $valueElements->count()) {
                throw new \Exception('Malformed');
            }

            $description = $descriptionElements->item(0)->nodeValue;
            $value       = $valueElements->item(0)->nodeValue;

            $applicableProductCharacteristics[] = new self($description, $value);
        }

        return $applicableProductCharacteristics;
    }
}
