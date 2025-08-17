<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Parse products JSON string back to array if it's a string
        if ($this->has('products') && is_string($this->products)) {
            $this->merge([
                'products' => json_decode($this->products, true)
            ]);
        }
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
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'products' => 'required|array',
            'total' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,processing,completed,cancelled',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'name.string' => 'الاسم يجب أن يكون نص',
            'name.max' => 'الاسم يجب أن لا يتجاوز 255 حرف',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.string' => 'رقم الهاتف يجب أن يكون نص',
            'phone.max' => 'رقم الهاتف يجب أن لا يتجاوز 20 حرف',
            'address.required' => 'العنوان مطلوب',
            'address.string' => 'العنوان يجب أن يكون نص',
            'products.required' => 'المنتجات مطلوبة',
            'products.array' => 'المنتجات يجب أن تكون مصفوفة',
            'total.required' => 'المجموع مطلوب',
            'total.numeric' => 'المجموع يجب أن يكون رقم',
            'total.min' => 'المجموع يجب أن يكون أكبر من أو يساوي صفر',
            'status.required' => 'الحالة مطلوبة',
            'status.string' => 'الحالة يجب أن تكون نص',
            'status.in' => 'الحالة يجب أن تكون: معلق، قيد المعالجة، مكتمل، ملغي',
        ];
    }
}
