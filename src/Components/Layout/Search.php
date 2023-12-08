<?php

declare(strict_types=1);

namespace MoonShine\Components\Layout;

use MoonShine\Components\MoonShineComponent;
use MoonShine\MoonShineRouter;

/**
 * @method static static make()
 */
final class Search extends MoonShineComponent
{
    protected string $view = 'moonshine::components.layout.search';

    protected function globalSearchEnabled(): bool
    {
        return filled(
            config('moonshine.global_search', [])
        );
    }

    protected function resourceSearchEnabled(): bool
    {
        $resource = moonshineRequest()->getResource();

        return ! is_null($resource) && method_exists($resource, 'search') && $resource->search();
    }

    protected function viewData(): array
    {
        $action = MoonShineRouter::to('global-search');

        if (! $this->globalSearchEnabled() && $this->resourceSearchEnabled()) {
            $action = to_page(resource: moonshineRequest()->getResource());
        }

        return [
            'enabled' => $this->globalSearchEnabled() || $this->resourceSearchEnabled(),
            'action' => $action,
            'global' => $this->globalSearchEnabled(),
        ];
    }
}