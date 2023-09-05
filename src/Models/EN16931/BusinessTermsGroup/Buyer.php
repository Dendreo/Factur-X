<?php

declare(strict_types=1);

namespace Dendreo\FacturX\Models\EN16931\BusinessTermsGroup;

use Dendreo\FacturX\Models\EN16931\DataType\Identifier\BuyerIdentifier;
use Dendreo\FacturX\Models\EN16931\DataType\Identifier\ElectronicAddressIdentifier;
use Dendreo\FacturX\Models\EN16931\DataType\Identifier\LegalRegistrationIdentifier;
use Dendreo\FacturX\Models\EN16931\DataType\Identifier\VatIdentifier;

/**
 * BG-7
 * A group of business terms providing information about the Buyer.
 */
class Buyer
{
    /**
     * BT-44
     * The full name of the Buyer.
     */
    private string $name;

    /**
     * BT-45
     * A name by which the Buyer is known, other than Buyer name (also known as Business name).
     */
    private ?string $tradingName;

    /**
     * BT-46
     * An identifier of the buyer.
     */
    private ?BuyerIdentifier $identifier;

    /**
     * BT-47
     * An identifier issued by an official registrar that identifies the buyer as a legal entity or person.
     */
    private ?LegalRegistrationIdentifier $legalRegistrationIdentifier;

    /**
     * BT-48
     * The Buyer's VAT identifier (also known as Buyer VAT identification number).
     */
    private ?VatIdentifier $vatIdentifier;

    /**
     * BT-49
     * Identifies the buyer's electronic address to which the invoice is delivered.
     */
    private ?ElectronicAddressIdentifier $electronicAddress;

    /**
     * BG-8
     * A group of business terms providing information about the postal address for the Buyer.
     */
    private BuyerPostalAddress $address;

    /**
     * BG-9
     * A group of business terms providing contact information relevant for the Buyer.
     */
    private ?BuyerContact $contact;


    public function __construct(
        string $name,
        BuyerPostalAddress $address,
        ?VatIdentifier $vatIdentifier = null,
        ?LegalRegistrationIdentifier $legalRegistrationIdentifier = null
    ) {
        $this->name = $name;
        $this->address = $address;
        $this->vatIdentifier = $vatIdentifier;
        $this->legalRegistrationIdentifier = $legalRegistrationIdentifier;
        $this->tradingName = null;
        $this->identifier = null;
        $this->electronicAddress = null;
        $this->contact = null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTradingName(): ?string
    {
        return $this->tradingName;
    }

    public function setTradingName(?string $tradingName): self
    {
        $this->tradingName = $tradingName;

        return $this;
    }

    public function getIdentifier(): ?BuyerIdentifier
    {
        return $this->identifier;
    }

    public function setIdentifier(?BuyerIdentifier $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getLegalRegistrationIdentifier(): ?LegalRegistrationIdentifier
    {
        return $this->legalRegistrationIdentifier;
    }

    public function getVatIdentifier(): ?VatIdentifier
    {
        return $this->vatIdentifier;
    }

    public function getElectronicAddress(): ?ElectronicAddressIdentifier
    {
        return $this->electronicAddress;
    }

    public function setElectronicAddress(?ElectronicAddressIdentifier $electronicAddress): self
    {
        $this->electronicAddress = $electronicAddress;

        return $this;
    }

    public function getAddress(): BuyerPostalAddress
    {
        return $this->address;
    }

    public function getContact(): ?BuyerContact
    {
        return $this->contact;
    }

    public function setContact(?BuyerContact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }
}
