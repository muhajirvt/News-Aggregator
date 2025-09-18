<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source_id' => [
                'nullable',
                'integer',
                Rule::exists('sources', 'id')->where(function ($query) {
                    $query->where('is_active', 1);
                }),
            ],
            'search'    => 'nullable|string|max:100',
            'date_from' => 'nullable|date_format:Y-m-d H:i:s',
            'date_to'   => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:date_from',
            'source'    => 'nullable|string',
            'category'  => 'nullable|string',
            'author'    => 'nullable|string',
            'page'      => 'nullable|integer|min:1',
            'per_page'  => 'nullable|integer|min:1|max:200'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
