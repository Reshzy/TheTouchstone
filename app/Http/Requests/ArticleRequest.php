<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,pending,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published_at' => 'nullable|date',
        ];

        return $rules;
    }
    
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('status') === 'published' && !$this->filled('published_at')) {
            $this->merge([
                'published_at' => now(),
            ]);
        }
    }
}
