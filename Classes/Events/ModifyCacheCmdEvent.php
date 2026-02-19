<?php

declare(strict_types=1);

namespace Opsone\Varnish\Events;

final class ModifyCacheCmdEvent
{
    public function __construct(
        private array $cacheCmd,
        private readonly array $params,
    ) {}

    public function getCacheCmd(): array
    {
        return $this->cacheCmd;
    }

    public function setCacheCmd(array $cacheCmd): void
    {
        $this->cacheCmd = $cacheCmd;
    }

    public function addCacheCmd(string $cmd): void
    {
        $this->cacheCmd[] = $cmd;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getTable(): ?string
    {
        return $this->params['table'] ?? null;
    }

    public function getUid(): ?int
    {
        return isset($this->params['uid']) ? (int)$this->params['uid'] : null;
    }

    public function getPageUid(): ?int
    {
        return isset($this->params['uid_page']) ? (int)$this->params['uid_page'] : null;
    }
}
