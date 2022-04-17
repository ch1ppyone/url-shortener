<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (array_is_list($this->all()))
            return [
                '*.long_url' => 'required|url|max:255',
                '*.title' => 'sometimes|string|max:255',
                '*.tags' => 'sometimes|array',
                '*.tags.*' => 'string|max:255',
            ];
        else
            return [
                'long_url' => 'required|url|max:255',
                'title' => 'sometimes|string|max:255',
                'tags' => 'sometimes|array',
                'tags.*' => 'string|max:255',

            ];
    }
}
