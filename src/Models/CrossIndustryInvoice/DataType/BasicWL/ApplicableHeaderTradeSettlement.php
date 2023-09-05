<?php

declare(strict_types=1);

namespace Dendreo\FacturX\DataType\BasicWL;

use Dendreo\FacturX\DataType\BillingSpecifiedPeriod;
use Dendreo\FacturX\DataType\InvoiceReferencedDocument;
use Dendreo\FacturX\DataType\PayeeTradeParty;
use Dendreo\FacturX\DataType\ReceivableSpecifiedTradeAccountingAccount;
use Dendreo\FacturX\DataType\SpecifiedTradeAllowance;
use Dendreo\FacturX\DataType\SpecifiedTradeCharge;
use Dendreo\FacturX\DataType\SpecifiedTradePaymentTerms;
use Models\EN16931\DataType\CurrencyCode;
use Models\EN16931\DataType\Identifier\BankAssignedCreditorIdentifier;

/**
 * BG-19.
 */
class ApplicableHeaderTradeSettlement extends \Dendreo\FacturX\DataType\Minimum\ApplicableHeaderTradeSettlement
{
    /**
     * BT-90.
     */
    protected ?BankAssignedCreditorIdentifier $creditorReferenceIdentifier;

    /**
     * BT-83.
     */
    protected ?string $paymentReference;

    /**
     * BT-6.
     */
    protected ?CurrencyCode $taxCurrencyCode;

    /**
     * BG-10.
     */
    protected ?PayeeTradeParty $payeeTradeParty;

    /**
     * BG-16.
     */
    protected ?SpecifiedTradeSettlementPaymentMeans $specifiedTradeSettlementPaymentMeans;

    /**
     * BG-23.
     *
     * @var non-empty-array<int, HeaderApplicableTradeTax>
     */
    protected array $applicableTradeTaxes;

    /**
     * BG-14.
     */
    protected ?BillingSpecifiedPeriod $billingSpecifiedPeriod;

    /**
     * BG-20.
     *
     * @var array<int, SpecifiedTradeAllowance>
     */
    protected array $specifiedTradeAllowances;

    /**
     * BG-21.
     *
     * @var array<int, SpecifiedTradeCharge>
     */
    protected array $specifiedTradeCharges;

    /**
     * BT-20-00.
     */
    protected ?SpecifiedTradePaymentTerms $specifiedTradePaymentTerms;

    /**
     * BG-3.
     */
    protected ?InvoiceReferencedDocument $invoiceReferencedDocument;

    /**
     * BT-19-00.
     */
    protected ?ReceivableSpecifiedTradeAccountingAccount $receivableSpecifiedTradeAccountingAccount;

    public function __construct(
        CurrencyCode $invoiceCurrencyCode,
        SpecifiedTradeSettlementHeaderMonetarySummation $specifiedTradeSettlementHeaderMonetarySummation,
        array $applicableTradeTaxes,
        ?SpecifiedTradePaymentTerms $specifiedTradePaymentTerms = null,
    ) {
        $tmpApplicableTradeTaxes = [];

        foreach ($applicableTradeTaxes as $applicableTradeTax) {
            if (!$applicableTradeTax instanceof HeaderApplicableTradeTax) {
                throw new \TypeError();
            }

            $tmpApplicableTradeTaxes[] = $applicableTradeTax;
        }

        if (\count($tmpApplicableTradeTaxes) === 0) {
            throw new \Exception('ApplicableHeaderTradeSettlement should contain at least one HeaderApplicableTradeTax.');
        }

        parent::__construct($invoiceCurrencyCode, $specifiedTradeSettlementHeaderMonetarySummation);

        $this->applicableTradeTaxes                      = $tmpApplicableTradeTaxes;
        $this->creditorReferenceIdentifier               = null;
        $this->paymentReference                          = null;
        $this->taxCurrencyCode                           = null;
        $this->payeeTradeParty                           = null;
        $this->specifiedTradeSettlementPaymentMeans      = null;
        $this->billingSpecifiedPeriod                    = null;
        $this->specifiedTradePaymentTerms                = $specifiedTradePaymentTerms;
        $this->invoiceReferencedDocument                 = null;
        $this->receivableSpecifiedTradeAccountingAccount = null;
        $this->specifiedTradeAllowances                  = [];
        $this->specifiedTradeCharges                     = [];
    }

    public function getCreditorReferenceIdentifier(): ?BankAssignedCreditorIdentifier
    {
        return $this->creditorReferenceIdentifier;
    }

    public function setCreditorReferenceIdentifier(?BankAssignedCreditorIdentifier $creditorReferenceIdentifier): static
    {
        $this->creditorReferenceIdentifier = $creditorReferenceIdentifier;

        return $this;
    }

    public function getPaymentReference(): ?string
    {
        return $this->paymentReference;
    }

    public function setPaymentReference(?string $paymentReference): static
    {
        $this->paymentReference = $paymentReference;

        return $this;
    }

    public function getTaxCurrencyCode(): ?CurrencyCode
    {
        return $this->taxCurrencyCode;
    }

    public function setTaxCurrencyCode(?CurrencyCode $taxCurrencyCode): static
    {
        $this->taxCurrencyCode = $taxCurrencyCode;

        return $this;
    }

    public function getPayeeTradeParty(): ?PayeeTradeParty
    {
        return $this->payeeTradeParty;
    }

    public function setPayeeTradeParty(?PayeeTradeParty $payeeTradeParty): static
    {
        $this->payeeTradeParty = $payeeTradeParty;

        return $this;
    }

    public function getSpecifiedTradeSettlementPaymentMeans(): ?SpecifiedTradeSettlementPaymentMeans
    {
        return $this->specifiedTradeSettlementPaymentMeans;
    }

    public function setSpecifiedTradeSettlementPaymentMeans(?SpecifiedTradeSettlementPaymentMeans $specifiedTradeSettlementPaymentMeans): static
    {
        $this->specifiedTradeSettlementPaymentMeans = $specifiedTradeSettlementPaymentMeans;

        return $this;
    }

    public function getApplicableTradeTaxes(): array
    {
        return $this->applicableTradeTaxes;
    }

    public function getBillingSpecifiedPeriod(): ?BillingSpecifiedPeriod
    {
        return $this->billingSpecifiedPeriod;
    }

    public function setBillingSpecifiedPeriod(?BillingSpecifiedPeriod $billingSpecifiedPeriod): static
    {
        $this->billingSpecifiedPeriod = $billingSpecifiedPeriod;

        return $this;
    }

    public function getSpecifiedTradeAllowances(): array
    {
        return $this->specifiedTradeAllowances;
    }

    public function setSpecifiedTradeAllowances(array $specifiedTradeAllowances): static
    {
        $tmpSpecifiedTradeAllowances = [];

        foreach ($specifiedTradeAllowances as $specifiedTradeAllowance) {
            if (!$specifiedTradeAllowance instanceof SpecifiedTradeAllowance) {
                throw new \TypeError();
            }

            $tmpSpecifiedTradeAllowances[] = $specifiedTradeAllowance;
        }

        $this->specifiedTradeAllowances = $tmpSpecifiedTradeAllowances;

        return $this;
    }

    public function getSpecifiedTradeCharges(): array
    {
        return $this->specifiedTradeCharges;
    }

    public function setSpecifiedTradeCharges(array $specifiedTradeCharges): static
    {
        $tmpSpecifiedTradeCharges = [];

        foreach ($specifiedTradeCharges as $specifiedTradeCharge) {
            if (!$specifiedTradeCharge instanceof SpecifiedTradeCharge) {
                throw new \TypeError();
            }

            $tmpSpecifiedTradeCharges[] = $specifiedTradeCharge;
        }

        $this->specifiedTradeCharges = $tmpSpecifiedTradeCharges;

        return $this;
    }

    public function getSpecifiedTradePaymentTerms(): ?SpecifiedTradePaymentTerms
    {
        return $this->specifiedTradePaymentTerms;
    }

    public function setSpecifiedTradePaymentTerms(?SpecifiedTradePaymentTerms $specifiedTradePaymentTerms): static
    {
        $this->specifiedTradePaymentTerms = $specifiedTradePaymentTerms;

        return $this;
    }

    public function getInvoiceReferencedDocument(): ?InvoiceReferencedDocument
    {
        return $this->invoiceReferencedDocument;
    }

    public function setInvoiceReferencedDocument(?InvoiceReferencedDocument $invoiceReferencedDocument): static
    {
        $this->invoiceReferencedDocument = $invoiceReferencedDocument;

        return $this;
    }

    public function getReceivableSpecifiedTradeAccountingAccount(): ?ReceivableSpecifiedTradeAccountingAccount
    {
        return $this->receivableSpecifiedTradeAccountingAccount;
    }

    public function setReceivableSpecifiedTradeAccountingAccount(?ReceivableSpecifiedTradeAccountingAccount $receivableSpecifiedTradeAccountingAccount): static
    {
        $this->receivableSpecifiedTradeAccountingAccount = $receivableSpecifiedTradeAccountingAccount;

        return $this;
    }

    public function toXML(\DOMDocument $document): \DOMElement
    {
        $currentNode = $document->createElement(self::XML_NODE);

        if ($this->creditorReferenceIdentifier instanceof BankAssignedCreditorIdentifier) {
            $currentNode->appendChild($document->createElement('ram:CreditorReferenceID', $this->creditorReferenceIdentifier->value));
        }

        if (\is_string($this->paymentReference)) {
            $currentNode->appendChild($document->createElement('ram:PaymentReference', $this->paymentReference));
        }

        if ($this->taxCurrencyCode instanceof CurrencyCode) {
            $currentNode->appendChild($document->createElement('ram:TaxCurrencyCode', $this->taxCurrencyCode->value));
        }

        $currentNode->appendChild($document->createElement('ram:InvoiceCurrencyCode', $this->invoiceCurrencyCode->value));

        if ($this->payeeTradeParty instanceof PayeeTradeParty) {
            $currentNode->appendChild($this->payeeTradeParty->toXML($document));
        }

        if ($this->specifiedTradeSettlementPaymentMeans instanceof SpecifiedTradeSettlementPaymentMeans) {
            $currentNode->appendChild($this->specifiedTradeSettlementPaymentMeans->toXML($document));
        }

        foreach ($this->applicableTradeTaxes as $applicableTradeTax) {
            $currentNode->appendChild($applicableTradeTax->toXML($document));
        }

        if ($this->billingSpecifiedPeriod instanceof BillingSpecifiedPeriod) {
            $currentNode->appendChild($this->billingSpecifiedPeriod->toXML($document));
        }

        foreach ($this->specifiedTradeAllowances as $specifiedTradeAllowance) {
            $currentNode->appendChild($specifiedTradeAllowance->toXML($document));
        }

        foreach ($this->specifiedTradeCharges as $specifiedTradeCharge) {
            $currentNode->appendChild($specifiedTradeCharge->toXML($document));
        }

        if ($this->specifiedTradePaymentTerms instanceof SpecifiedTradePaymentTerms) {
            $currentNode->appendChild($this->specifiedTradePaymentTerms->toXML($document));
        }

        $currentNode->appendChild($this->specifiedTradeSettlementHeaderMonetarySummation->toXML($document));

        if ($this->invoiceReferencedDocument instanceof InvoiceReferencedDocument) {
            $currentNode->appendChild($this->invoiceReferencedDocument->toXML($document));
        }

        if ($this->receivableSpecifiedTradeAccountingAccount instanceof ReceivableSpecifiedTradeAccountingAccount) {
            $currentNode->appendChild($this->receivableSpecifiedTradeAccountingAccount->toXML($document));
        }

        return $currentNode;
    }

    public static function fromXML(\DOMXPath $xpath, \DOMElement $currentElement): self
    {
        $applicableHeaderTradeSettlementElements = $xpath->query(sprintf('./%s', self::XML_NODE), $currentElement);

        if ($applicableHeaderTradeSettlementElements->count() !== 1) {
            throw new \Exception('Malformed');
        }

        /** @var \DOMElement $applicableHeaderTradeSettlementElement */
        $applicableHeaderTradeSettlementElement = $applicableHeaderTradeSettlementElements->item(0);

        $creditorReferenceIdentifierElements = $xpath->query('./ram:CreditorReferenceID', $applicableHeaderTradeSettlementElement);
        $paymentReferenceElements            = $xpath->query('./ram:PaymentReference', $applicableHeaderTradeSettlementElement);
        $taxCurrencyCodeElements             = $xpath->query('./ram:TaxCurrencyCode', $applicableHeaderTradeSettlementElement);
        $invoiceCurrencyCodeElements         = $xpath->query('./ram:InvoiceCurrencyCode', $applicableHeaderTradeSettlementElement);

        if ($creditorReferenceIdentifierElements->count() > 1) {
            throw new \Exception('Malformed');
        }

        if ($paymentReferenceElements->count() > 1) {
            throw new \Exception('Malformed');
        }

        if ($taxCurrencyCodeElements->count() > 1) {
            throw new \Exception('Malformed');
        }

        if ($invoiceCurrencyCodeElements->count() !== 1) {
            throw new \Exception('Malformed');
        }

        $taxCurrencyCode = null;

        if ($taxCurrencyCodeElements->count() === 1) {
            $taxCurrencyCode = CurrencyCode::tryFrom($taxCurrencyCodeElements->item(0)->nodeValue);

            if ($taxCurrencyCode === null) {
                throw new \Exception('Wrong TaxCurrencyCode');
            }
        }

        $invoiceCurrencyCode = CurrencyCode::tryFrom($invoiceCurrencyCodeElements->item(0)->nodeValue);

        if ($invoiceCurrencyCode === null) {
            throw new \Exception('Wrong InvoiceCurrencyCode');
        }

        $payeeTradeParty                                 = PayeeTradeParty::fromXML($xpath, $applicableHeaderTradeSettlementElement);
        $specifiedTradeSettlementPaymentMeans            = SpecifiedTradeSettlementPaymentMeans::fromXML($xpath, $applicableHeaderTradeSettlementElement);
        $applicableTradeTaxes                            = HeaderApplicableTradeTax::fromXML($xpath, $applicableHeaderTradeSettlementElement);
        $billingSpecifiedPeriod                          = BillingSpecifiedPeriod::fromXML($xpath, $applicableHeaderTradeSettlementElement);
        $specifiedTradeAllowances                        = SpecifiedTradeAllowance::fromXML($xpath, $applicableHeaderTradeSettlementElement);
        $specifiedTradeCharges                           = SpecifiedTradeCharge::fromXML($xpath, $applicableHeaderTradeSettlementElement);
        $specifiedTradePaymentTerms                      = SpecifiedTradePaymentTerms::fromXML($xpath, $applicableHeaderTradeSettlementElement);
        $specifiedTradeSettlementHeaderMonetarySummation = SpecifiedTradeSettlementHeaderMonetarySummation::fromXML($xpath, $applicableHeaderTradeSettlementElement);
        $invoiceReferencedDocument                       = InvoiceReferencedDocument::fromXML($xpath, $applicableHeaderTradeSettlementElement);
        $receivableSpecifiedTradeAccountingAccount       = ReceivableSpecifiedTradeAccountingAccount::fromXML($xpath, $applicableHeaderTradeSettlementElement);

        $applicableHeaderTradeSettlement = new self($invoiceCurrencyCode, $specifiedTradeSettlementHeaderMonetarySummation, $applicableTradeTaxes);

        if ($creditorReferenceIdentifierElements->count() === 1) {
            $applicableHeaderTradeSettlement->setCreditorReferenceIdentifier($creditorReferenceIdentifierElements->item(0)->nodeValue);
        }

        if ($paymentReferenceElements->count() === 1) {
            $applicableHeaderTradeSettlement->setPaymentReference($paymentReferenceElements->item(0)->nodeValue);
        }

        if ($taxCurrencyCode instanceof CurrencyCode) {
            $applicableHeaderTradeSettlement->setTaxCurrencyCode($taxCurrencyCode);
        }

        if ($payeeTradeParty instanceof PayeeTradeParty) {
            $applicableHeaderTradeSettlement->setPayeeTradeParty($payeeTradeParty);
        }

        if ($specifiedTradeSettlementPaymentMeans instanceof SpecifiedTradeSettlementPaymentMeans) {
            $applicableHeaderTradeSettlement->setSpecifiedTradeSettlementPaymentMeans($specifiedTradeSettlementPaymentMeans);
        }

        if ($billingSpecifiedPeriod instanceof BillingSpecifiedPeriod) {
            $applicableHeaderTradeSettlement->setBillingSpecifiedPeriod($billingSpecifiedPeriod);
        }

        if (\count($specifiedTradeAllowances) > 0) {
            $applicableHeaderTradeSettlement->setSpecifiedTradeAllowances($specifiedTradeAllowances);
        }

        if (\count($specifiedTradeCharges) > 0) {
            $applicableHeaderTradeSettlement->setSpecifiedTradeCharges($specifiedTradeCharges);
        }

        if ($specifiedTradePaymentTerms instanceof SpecifiedTradePaymentTerms) {
            $applicableHeaderTradeSettlement->setSpecifiedTradePaymentTerms($specifiedTradePaymentTerms);
        }

        if ($invoiceReferencedDocument instanceof InvoiceReferencedDocument) {
            $applicableHeaderTradeSettlement->setInvoiceReferencedDocument($invoiceReferencedDocument);
        }

        if ($receivableSpecifiedTradeAccountingAccount instanceof ReceivableSpecifiedTradeAccountingAccount) {
            $applicableHeaderTradeSettlement->setReceivableSpecifiedTradeAccountingAccount($receivableSpecifiedTradeAccountingAccount);
        }

        return $applicableHeaderTradeSettlement;
    }
}
