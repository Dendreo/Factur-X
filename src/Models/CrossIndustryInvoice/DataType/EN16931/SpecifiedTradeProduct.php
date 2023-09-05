<?php

declare(strict_types=1);

namespace Dendreo\FacturX\DataType\EN16931;

use Dendreo\FacturX\DataType\ApplicableProductCharacteristic;
use Dendreo\FacturX\DataType\ClassCode;
use Dendreo\FacturX\DataType\DesignatedProductClassification;
use Dendreo\FacturX\DataType\OriginTradeCountry;
use Models\EN16931\BusinessTermsGroup\ItemInformation;
use Models\EN16931\DataType\Identifier\BuyerItemIdentifier;
use Models\EN16931\DataType\Identifier\SellerItemIdentifier;
use Models\EN16931\DataType\Identifier\StandardItemIdentifier;
use Models\EN16931\DataType\InternationalCodeDesignator;

/**
 * BG-31.
 */
class SpecifiedTradeProduct extends \Dendreo\FacturX\DataType\Basic\SpecifiedTradeProduct
{
    /**
     * BT-155.
     */
    private ?SellerItemIdentifier $sellerAssignedIdentifier;

    /**
     * BT-156.
     */
    private ?BuyerItemIdentifier $buyerAssignedIdentifier;

    /**
     * BT-154.
     */
    private ?string $description;

    /**
     * BG-32.
     *
     * @var array<int, ApplicableProductCharacteristic>
     */
    private array $applicableProductCharacteristics;

    /**
     * BT-158-00.
     *
     * @var array<int, DesignatedProductClassification>
     */
    private array $designatedProductClassifications;

    /**
     * BT-159-00.
     */
    private ?OriginTradeCountry $originTradeCountry;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->applicableProductCharacteristics = [];
        $this->designatedProductClassifications = [];
        $this->sellerAssignedIdentifier         = null;
        $this->buyerAssignedIdentifier          = null;
        $this->description                      = null;
        $this->originTradeCountry               = null;
    }

    public function getSellerAssignedIdentifier(): ?SellerItemIdentifier
    {
        return $this->sellerAssignedIdentifier;
    }

    public function setSellerAssignedIdentifier(?SellerItemIdentifier $sellerAssignedIdentifier): static
    {
        $this->sellerAssignedIdentifier = $sellerAssignedIdentifier;

        return $this;
    }

    public function getBuyerAssignedIdentifier(): ?BuyerItemIdentifier
    {
        return $this->buyerAssignedIdentifier;
    }

    public function setBuyerAssignedIdentifier(?BuyerItemIdentifier $buyerAssignedIdentifier): static
    {
        $this->buyerAssignedIdentifier = $buyerAssignedIdentifier;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getApplicableProductCharacteristics(): array
    {
        return $this->applicableProductCharacteristics;
    }

    public function setApplicableProductCharacteristics(array $applicableProductCharacteristics): static
    {
        $tmpApplicableProductCharacteristics = [];

        foreach ($applicableProductCharacteristics as $applicableProductCharacteristic) {
            if (!$applicableProductCharacteristic instanceof ApplicableProductCharacteristic) {
                throw new \TypeError();
            }

            $tmpApplicableProductCharacteristics[] = $applicableProductCharacteristic;
        }

        $this->applicableProductCharacteristics = $tmpApplicableProductCharacteristics;

        return $this;
    }

    public function getDesignatedProductClassifications(): array
    {
        return $this->designatedProductClassifications;
    }

    public function setDesignatedProductClassifications(array $designatedProductClassifications): static
    {
        $tmpDesignatedProductClassifications = [];

        foreach ($designatedProductClassifications as $designatedProductClassification) {
            if (!$designatedProductClassification instanceof DesignatedProductClassification) {
                throw new \TypeError();
            }

            $tmpDesignatedProductClassifications[] = $designatedProductClassification;
        }

        $this->designatedProductClassifications = $tmpDesignatedProductClassifications;

        return $this;
    }

    public function getOriginTradeCountry(): ?OriginTradeCountry
    {
        return $this->originTradeCountry;
    }

    public function setOriginTradeCountry(?OriginTradeCountry $originTradeCountry): static
    {
        $this->originTradeCountry = $originTradeCountry;

        return $this;
    }

    public function toXML(\DOMDocument $document): \DOMElement
    {
        $currentNode = $document->createElement(self::XML_NODE);

        if ($this->globalIdentifier instanceof StandardItemIdentifier) {
            $identifierElement = $document->createElement('ram:GlobalID', $this->globalIdentifier->value);
            $identifierElement->setAttribute('schemeID', $this->globalIdentifier->scheme->value);
            $currentNode->appendChild($identifierElement);
        }

        if ($this->sellerAssignedIdentifier instanceof SellerItemIdentifier) {
            $currentNode->appendChild($document->createElement('ram:SellerAssignedID', $this->sellerAssignedIdentifier->value));
        }

        if ($this->buyerAssignedIdentifier instanceof BuyerItemIdentifier) {
            $currentNode->appendChild($document->createElement('ram:BuyerAssignedID', $this->buyerAssignedIdentifier->value));
        }

        $currentNode->appendChild($document->createElement('ram:Name', $this->name));

        if (\is_string($this->description)) {
            $currentNode->appendChild($document->createElement('ram:Description', $this->description));
        }

        foreach ($this->applicableProductCharacteristics as $applicableProductCharacteristic) {
            $currentNode->appendChild($applicableProductCharacteristic->toXML($document));
        }

        foreach ($this->designatedProductClassifications as $designatedProductClassification) {
            $currentNode->appendChild($designatedProductClassification->toXML($document));
        }

        if ($this->originTradeCountry instanceof OriginTradeCountry) {
            $currentNode->appendChild($this->originTradeCountry->toXML($document));
        }

        return $currentNode;
    }

    public static function fromXML(\DOMXPath $xpath, \DOMElement $currentElement): self
    {
        $specifiedTradeProductElements = $xpath->query(sprintf('./%s', self::XML_NODE), $currentElement);

        if (1 !== $specifiedTradeProductElements->count()) {
            throw new \Exception('Malformed');
        }

        /** @var \DOMElement $specifiedTradeProductElement */
        $specifiedTradeProductElement = $specifiedTradeProductElements->item(0);

        $globalIdentifierElements         = $xpath->query('./ram:GlobalID', $specifiedTradeProductElement);
        $sellerAssignedIdentifierElements = $xpath->query('./ram:SellerAssignedID', $specifiedTradeProductElement);
        $buyerAssignedIdentifierElements  = $xpath->query('./ram:BuyerAssignedID', $specifiedTradeProductElement);
        $nameElements                     = $xpath->query('./ram:Name', $specifiedTradeProductElement);
        $descriptionElements              = $xpath->query('./ram:Description', $specifiedTradeProductElement);

        if ($globalIdentifierElements->count() > 1) {
            throw new \Exception('Malformed');
        }

        if ($sellerAssignedIdentifierElements->count() > 1) {
            throw new \Exception('Malformed');
        }

        if ($buyerAssignedIdentifierElements->count() > 1) {
            throw new \Exception('Malformed');
        }

        if (1 !== $nameElements->count()) {
            throw new \Exception('Malformed');
        }

        if ($descriptionElements->count() > 1) {
            throw new \Exception('Malformed');
        }

        $name = $nameElements->item(0)->nodeValue;

        $applicableProductCharacteristics = ApplicableProductCharacteristic::fromXML($xpath, $specifiedTradeProductElement);
        $designatedProductClassifications = DesignatedProductClassification::fromXML($xpath, $specifiedTradeProductElement);
        $originTradeCountry               = OriginTradeCountry::fromXML($xpath, $specifiedTradeProductElement);

        $specifiedTradeProduct = new self($name);

        if (1 === $globalIdentifierElements->count()) {
            $globalIdentifierItem = $globalIdentifierElements->item(0);
            $scheme               = null;

            if (!$globalIdentifierItem->hasAttribute('schemeID')) {
                throw new \Exception('Malformed');
            }

            if ($globalIdentifierItem->hasAttribute('schemeID')) {
                $scheme = InternationalCodeDesignator::tryFrom($globalIdentifierItem->getAttribute('schemeID'));

                if (!$scheme instanceof InternationalCodeDesignator) {
                    throw new \Exception('Wrong schemeID');
                }
            }

            $specifiedTradeProduct->setGlobalIdentifier(new StandardItemIdentifier($globalIdentifierElements->item(0)->nodeValue, $scheme));
        }

        if (1 === $sellerAssignedIdentifierElements->count()) {
            $specifiedTradeProduct->setSellerAssignedIdentifier(new SellerItemIdentifier($sellerAssignedIdentifierElements->item(0)->nodeValue));
        }

        if (1 === $buyerAssignedIdentifierElements->count()) {
            $specifiedTradeProduct->setBuyerAssignedIdentifier($buyerAssignedIdentifierElements->item(0)->nodeValue);
        }

        if ($descriptionElements->count() > 1) {
            $specifiedTradeProduct->setDescription($descriptionElements->item(0)->nodeValue);
        }

        if (\count($applicableProductCharacteristics) > 0) {
            $specifiedTradeProduct->setApplicableProductCharacteristics($applicableProductCharacteristics);
        }

        if (\count($designatedProductClassifications) > 0) {
            $specifiedTradeProduct->setDesignatedProductClassifications($designatedProductClassifications);
        }

        if ($originTradeCountry instanceof OriginTradeCountry) {
            $specifiedTradeProduct->setOriginTradeCountry($originTradeCountry);
        }

        return $specifiedTradeProduct;
    }

    public static function fromEN16931(ItemInformation $itemInformation): self
    {
        $characteristics = [];
        $classifications = [];

        foreach ($itemInformation->getItemAttributes() as $attribute) {
            $characteristics[] = new ApplicableProductCharacteristic($attribute->getName(), $attribute->getValue());
        }

        foreach ($itemInformation->getClassificationIdentifiers() as $classificationIdentifier) {
            $classCode = (new ClassCode($classificationIdentifier->value, $classificationIdentifier->scheme))
                ->setListVersionIdentifier($classificationIdentifier->version);

            $classifications[] = (new DesignatedProductClassification())->setClassCode($classCode);
        }

        return (new self($itemInformation->getName()))
            ->setGlobalIdentifier($itemInformation->getStandardIdentifier())
            ->setSellerAssignedIdentifier($itemInformation->getSellerIdentifier())
            ->setBuyerAssignedIdentifier($itemInformation->getBuyerIdentifier())
            ->setDescription($itemInformation->getDescription())
            ->setApplicableProductCharacteristics($characteristics)
            ->setDesignatedProductClassifications($classifications)
            ->setOriginTradeCountry($itemInformation->getItemCountryOfOrigin());
    }
}
