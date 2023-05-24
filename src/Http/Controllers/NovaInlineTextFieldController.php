<?php

namespace Outl1ne\NovaInlineTextField\Http\Controllers;

use Exception;
use Illuminate\Routing\Controller;
use Outl1ne\NovaInlineTextField\InlineText;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;

class NovaInlineTextFieldController extends Controller
{
    public function update(NovaRequest $request)
    {
        $modelId = $request->_inlineResourceId;
        $attribute = $request->_inlineAttribute;
        $lensUri = $request->_lensUri;

        $resourceClass = $request->resource();
        $resourceValidationRules = $resourceClass::rulesForUpdate($request);
        $fieldValidationRules = $resourceValidationRules[$attribute] ?? null;

        if (!empty($fieldValidationRules)) {
            $request->validate([$attribute => $fieldValidationRules]);
        }

        // Find field in question
        try {
            $model = $request->model()->find($modelId);
            $resource = new $resourceClass($model);
            if ($lensUri) {
                $resource = collect($resource->lenses($request))
                    ->firstWhere(fn (Lens $lens) => $lens->uriKey() === $lensUri);
            }
            $allFields = collect($resource->fields($request));
            $field = $allFields->first(function ($field) use ($attribute) {
                return get_class($field) === InlineText::class && $field->attribute === $attribute;
            });

            $field->fillInto($request, $model, $attribute);
            $model->save();
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response('', 204);
    }
}
