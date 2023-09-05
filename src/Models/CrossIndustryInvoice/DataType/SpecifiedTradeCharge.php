<?php

declare(strict_types=1);

namespace Dendreo\FacturX\Models\CrossIndustryInvoice\DataType;

use Dendreo\FacturX\Models\EN16931\BusinessTermsGroup\DocumentLevelCharge;
use Dendreo\FacturX\Models\EN16931\DataType\ChargeReasonCode;
use Dendreo\FacturX\Models\EN16931\SemanticDataType\Amount;
use Dendreo\FacturX\Models\EN16931\SemanticDataType\Percentage;

/**
 * BG-21.
 */
class SpecifiedTradeCharge
{
    protected const XML_NODE = 'ram:SpecifiedTradeAllowanceCharge';

    /**
     * BG-21-0.
     */
    private ChargeIndicator $chargeIndicator;

    /**
     * BT-101.
     */
    private ?Percentage $calculationPercent;

    /**
     * BT-100.
     */
    private ?Amount $basisAmount;

    /**
     * BT-99.
     */
    private Amount $actualAmount;

    /**
     * BT-105.
     */
    private ?ChargeReasonCode $reasonCode;

    /**
     * BT-104.
     */
    private ?string $reason;

    /**
     * BT-102-00.
     */
    private CategoryTradeTax $categoryTradeTax;

    public function __construct(float $actualAmount, CategoryTradeTax $categoryTradeTax)
    {
        $this->chargeIndicator    = new ChargeIndicator();
        $this->actualAmount       = new Amount($actualAmount);
        $this->categoryTradeTax   = $categoryTradeTax;
        $this->calculationPercent = null;
        $this->basisAmount        = null;
        $this->reasonCode         = null;
        $this->reason             = null;
    }

    public function getChargeIndicator(): ChargeIndicator
    {
        return $this->chargeIndicator;
    }

    public function getCalculationPercent(): ?float
    {
        return $this->calculationPercent?->getValueRounded();
    }

    public function setCalculationPercent(?float $calculationPercent): static
    {
        $this->calculationPercent = \is_float($calculationPercent) ? new Percentage($calculationPercent) : null;

        return $this;
    }

    public function getBasisAmount(): ?float
    {
        return $this->basisAmount?->getValueRounded();
    }

    public function setBasisAmount(?float $basisAmount): static
    {
        $this->basisAmount = \is_float($basisAmount) ? new Amount($basisAmount) : null;

        return $this;
    }

    public function getActualAmount(): float
    {
        return $this->actualAmount->getValueRounded();
    }

    public function getReasonCode(): ?ChargeReasonCode
    {
        return $this->reasonCode;
    }

    public function setReasonCode(?ChargeReasonCode $reasonCode): static
    {
        $this->reasonCode = $reasonCode;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }

    public function getCategoryTradeTax(): CategoryTradeTax
    {
        return $this->categoryTradeTax;
    }

    public function toXML(\DOMDocument $document): \DOMElement
    {
        $currentNode = $document->createElement(self::XML_NODE);

        $currentNode->appendChild($this->chargeIndicator->toXML($document));

        if ($this->calculationPercent instanceof Percentage) {
            $currentNode->appendChild($document->createElement('ram:CalculationPercent', $this->calculationPercent->getFormattedValueRounded()));
        }

        if ($this->basisAmount instanceof Amount) {
            $currentNode->appendChild($document->createElement('ram:BasisAmount', $this->basisAmount->getFormattedValueRounded()));
        }

        $currentNode->appendChild($document->createElement('ram:ActualAmount', $this->actualAmount->getFormattedValueRounded()));

        if ($this->reasonCode instanceof ChargeReasonCode) {
            $currentNode->appendChild($document->createElement('ram:ReasonCode', $this->reasonCode->value));
        }

        if (\is_string($this->reason)) {
            $currentNode->appendChild($document->createElement('ram:Reason', $this->reason));
        }

        $currentNode->appendChild($this->categoryTradeTax->toXML($document));

        return $currentNode;
    }

    public static function fromXML(\DOMXPath $xpath, \DOMElement $currentElement): array
    {
        $specifiedTradeChargeElements = $xpath->query(sprintf('./%s[ram:ChargeIndicator/udt:Indicator[text() = \'true\']]', self::XML_NODE), $currentElement);

        if (0 === $specifiedTradeChargeElements->count()) {
            return [];
        }

        $specifiedTradeCharges = [];

        /** @var \DOMElement $specifiedTradeChargeElement */
        foreach ($specifiedTradeChargeElements as $specifiedTradeChargeElement) {
            $calculationPercentElements = $xpath->query('./ram:CalculationPercent', $specifiedTradeChargeElement);
            $basisAmountElements        = $xpath->query('./ram:BasisAmount', $specifiedTradeChargeElement);
            $actualAmountElements       = $xpath->query('./ram:ActualAmount', $specifiedTradeChargeElement);
            $reasonCodeElements         = $xpath->query('./ram:ReasonCode', $specifiedTradeChargeElement);
            $reasonElements             = $xpath->query('./ram:Reason', $specifiedTradeChargeElement);

            if ($calculationPercentElements->count() > 1) {
                throw new \Exception('Malformed');
            }

            if ($basisAmountElements->count() > 1) {
                throw new \Exception('Malformed');
            }

            if (1 !== $actualAmountElements->count()) {
                throw new \Exception('Malformed');
            }

            if ($reasonCodeElements->count() > 1) {
                throw new \Exception('Malformed');
            }

            if ($reasonElements->count() > 1) {
                throw new \Exception('Malformed');
            }

            $actualAmount = $actualAmountElements->item(0)->nodeValue;

            $categoryTradeTax = CategoryTradeTax::fromXML($xpath, $specifiedTradeChargeElement);

            $specifiedTradeCharge = new self((float) $actualAmount, $categoryTradeTax);

            if (1 === $calculationPercentElements->count()) {
                $specifiedTradeCharge->setCalculationPercent((float) $calculationPercentElements->item(0)->nodeValue);
            }

            if (1 === $basisAmountElements->count()) {
                $specifiedTradeCharge->setBasisAmount((float) $basisAmountElements->item(0)->nodeValue);
            }

            if (1 === $reasonCodeElements->count()) {
                $reasonCode = ChargeReasonCode::tryFrom($reasonCodeElements->item(0)->nodeValue);

                if (null === $reasonCode) {
                    throw new \Exception('Wrong ReasonCode');
                }

                $specifiedTradeCharge->setReasonCode($reasonCode);
            }

            if (1 === $reasonElements->count()) {
                $specifiedTradeCharge->setReason($reasonElements->item(0)->nodeValue);
            }

            $specifiedTradeCharges[] = $specifiedTradeCharge;
        }

        return $specifiedTradeCharges;
    }

    public static function fromEN16931(DocumentLevelCharge $charge): self
    {
        $specifiedTradeCharge = new self(
            $charge->getAmount(),
            (new CategoryTradeTax($charge->getVatCategoryCode()))->setRateApplicablePercent($charge->getVatRate())
        );

        $specifiedTradeCharge->setCalculationPercent($charge->getPercentage())
            ->setBasisAmount($charge->getBaseAmount())
            ->setReasonCode($charge->getReasonCode())
            ->setReason($charge->getReason());

        return $specifiedTradeCharge;
    }
}
