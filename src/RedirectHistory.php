<?php

namespace Spatie\GuzzleRedirectHistoryMiddleware;

class RedirectHistory
{
    protected array $history = [];

    public function add(int $status, string $url)
    {
        $this->history[] = compact('status', 'url');
    }

    public function toArray(): array
    {
        return $this->history;
    }
}
