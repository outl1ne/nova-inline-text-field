<?php

namespace OptimistDigital\NovaInlineTextField;

use Laravel\Nova\Fields\Text;

class InlineText extends Text
{
    public $component = 'inline-text-field';

    protected function resolveAttribute($resource, $attribute)
    {
        $this->withMeta(['resourceId' => $resource->getKey()]);
        return parent::resolveAttribute($resource, $attribute);
    }
}
