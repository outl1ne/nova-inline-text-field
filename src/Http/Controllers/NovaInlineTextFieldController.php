<?php

namespace OptimistDigital\NovaInlineTextField\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;
use OptimistDigital\NovaInlineTextField\InlineText;

class NovaInlineTextFieldController extends Controller
{
    public function update(NovaRequest $request)
    {
        $modelId = $request->_inlineResourceId;
        $attribute = $request->_inlineAttribute;

        $resourceClass = $request->resource();
        $resourceValidationRules = $resourceClass::rulesForUpdate($request);
        $fieldValidationRules = $resourceValidationRules[$attribute];

        if (!empty($fieldValidationRules)) {
            $request->validate([$attribute => $fieldValidationRules]);
        }

        // Find field in question
        $model = $request->model()->find($modelId);
        $resource = new $resourceClass($model);

        $allFields = collect($resource->fields($request));
        $field = $allFields->first(function ($field) use ($attribute) {
            return get_class($field) === InlineText::class && $field->attribute === $attribute;
        });

        $field->fillInto($request, $model, $attribute);

        ray($model, $attribute, $request->{$attribute}, $model->{$attribute});

        $model->save();
    }
}
