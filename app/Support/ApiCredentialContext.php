<?php

namespace App\Support;

/**
 * Identifies the machine credential that authenticated an API request.
 * This is deliberately separate from a human User identity.
 */
final class ApiCredentialContext
{
    public function __construct(
        public string $mechanism,
        public int $credentialId,
        public int $organizationUnitId,
        public ?int $clientId = null
    ) {
    }

    public function auditIdentifier(): string
    {
        return $this->mechanism . ':' . $this->credentialId;
    }
}
