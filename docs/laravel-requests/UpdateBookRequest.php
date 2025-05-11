<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'author_id' => 'nullable|exists:authors,id',
            'author' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'pdf' => 'nullable|file|mimes:pdf|max:10240',
            'cover_image' => 'nullable|image|max:2048',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'book name',
            'author_id' => 'author',
            'author' => 'author name',
            'category' => 'category',
            'description' => 'description',
            'quantity' => 'quantity',
            'price' => 'price',
            'pdf' => 'PDF file',
            'cover_image' => 'cover image',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The book name is required.',
            'author.required' => 'The author name is required.',
            'category.required' => 'The category is required.',
            'quantity.min' => 'The quantity must be at least 0.',
            'price.min' => 'The price must be at least 0.',
            'pdf.mimes' => 'The file must be a PDF.',
            'pdf.max' => 'The PDF file may not be greater than 10MB.',
            'cover_image.image' => 'The cover image must be an image file.',
            'cover_image.max' => 'The cover image may not be greater than 2MB.',
        ];
    }
}