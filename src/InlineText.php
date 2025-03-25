<?php

namespace Outl1ne\NovaInlineTextField;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class InlineText extends Text
{
    public $component = 'inline-text-field';

    protected function resolveAttribute($resource, string $attribute): mixed
    {
        $this->withMeta(['resourceId' => $resource->getKey()]);
        return parent::resolveAttribute($resource, $attribute);
    }

    public function resolve($resource, ?string $attribute = null): void
    {
        parent::resolve($resource, $attribute);

        /** @var NovaRequest */
        $novaRequest = app()->make(NovaRequest::class);
        if ($novaRequest->isFormRequest()) $this->component = 'text-field';
    }

    public function maxWidth(int|null $maxWidthPx = null)
    {
        return $this->withMeta(['maxWidth' => $maxWidthPx]);
    }
}
