<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CategoryRequest extends FormRequest
{
    /**
     * Bag terpisah agar jendela form kategori bisa dibuka lagi beserta pesannya.
     *
     * @var string
     */
    protected $errorBag = 'category';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Slug kosong dibuat dari nama; slug isian dirapikan ke format URL.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->filled('slug') ? (string) $this->input('slug') : (string) $this->input('name')),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $category = $this->editedCategory();

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category)],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->whereNull('parent_id'),
                Rule::when($category !== null, [Rule::notIn([$category?->id])]),
            ],
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $category = $this->editedCategory();

                if ($category !== null && $this->filled('parent_id') && $category->children()->exists()) {
                    $validator->errors()->add('parent_id', 'Kategori ini punya sub-kategori, jadi harus tetap menjadi kategori utama.');
                }
            },
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
            'name.unique' => 'Nama kategori ini sudah dipakai.',
            'slug.required' => 'Slug tidak bisa dibuat dari nama ini. Isi slug secara manual.',
            'slug.max' => 'Slug maksimal 255 karakter.',
            'slug.unique' => 'Slug ini sudah dipakai kategori lain.',
            'parent_id.integer' => 'Induk kategori tidak valid.',
            'parent_id.exists' => 'Induk harus berupa kategori utama.',
            'parent_id.not_in' => 'Kategori tidak bisa menjadi induk dirinya sendiri.',
        ];
    }

    private function editedCategory(): ?Category
    {
        $category = $this->route('category');

        return $category instanceof Category ? $category : null;
    }
}
