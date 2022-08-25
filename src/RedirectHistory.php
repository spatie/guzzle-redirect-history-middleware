<?php

namespace Spatie\GuzzleRedirectHistoryMiddleware;

class RedirectHistory
{
    protected array $history = [];

    public function add(int $status, string $url, string $reason, array $headers)
    {
        $this->history[] = compact('status', 'url', 'reason', 'headers');
    }

    public function toArray(): array
    {
        return $this->history;
    }
}
