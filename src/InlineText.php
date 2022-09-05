<?php

namespace Outl1ne\NovaInlineTextField;

use Laravel\Nova\Fields\Text;

class InlineText extends Text
{
    public $component = 'inline-text-field';

    protected function resolveAttribute($resource, $attribute)
    {
        $this->withMeta(['resourceId' => $resource->getKey()]);
        return parent::resolveAttribute($resource, $attribute);
    }

    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);

        /** @var NovaRequest */
        $novaRequest = app()->make(NovaRequest::class);
        if ($novaRequest->isFormRequest()) $this->component = 'text-field';
    }
}
