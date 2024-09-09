<?php

namespace Outl1ne\NovaInlineTextField\Http\Controllers;

use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Outl1ne\NovaInlineTextField\InlineText;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Panel;

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
            $field = $this->findField($allFields, $attribute);

            $field->fillInto($request, $model, $attribute);
            $model->save();
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response('', 204);
    }

	/**
	 * Recursively search for the field, including nested Stack fields.
	 */
	protected function findField(Collection $fields, String $attribute): InlineText|null
	{
		foreach ($fields as $field) {
			// Direct match with InlineText field
			if ($this->isCorrectInlineTextField($field, $attribute)) {
				return $field;
			}

			// Search within Stack fields
			if (get_class($field) === Stack::class) {
				foreach ($field->lines as $nestedField) {
					if ($this->isCorrectInlineTextField($nestedField, $attribute)) {
						return $nestedField;
					}
				}
			}

            // Search within Panel fields
			if (get_class($field) === Panel::class) {
				foreach ($field->data as $nestedField) {
					if ($this->isCorrectInlineTextField($nestedField, $attribute)) {
						return $nestedField;
					}
				}
			}
		}

		return null;
	}

	/**
	 * Check if the field is the InlineText field.
	 */
	protected function isCorrectInlineTextField($field, $attribute): bool
	{
		return get_class($field) === InlineText::class && $field->attribute === $attribute;
	}
}
