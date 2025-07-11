<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'sold' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'category_id.required' => 'يجب اختيار القسم',
            'category_id.exists' => 'القسم المختار غير موجود',
            'name.required' => 'اسم المنتج مطلوب',
            'name.max' => 'اسم المنتج يجب أن لا يتجاوز 255 حرف',
            'description.required' => 'وصف المنتج مطلوب',
            'sold.required' => 'عدد المباع مطلوب',
            'sold.integer' => 'عدد المباع يجب أن يكون رقم صحيح',
            'sold.min' => 'عدد المباع يجب أن يكون أكبر من أو يساوي صفر',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقم',
            'price.min' => 'السعر يجب أن يكون أكبر من أو يساوي صفر',
            'weight.required' => 'الوزن مطلوب',
            'weight.numeric' => 'الوزن يجب أن يكون رقم',
            'weight.min' => 'الوزن يجب أن يكون أكبر من أو يساوي صفر',
            'quantity.required' => 'الكمية مطلوبة',
            'quantity.integer' => 'الكمية يجب أن تكون رقم صحيح',
            'quantity.min' => 'الكمية يجب أن تكون أكبر من أو يساوي صفر',
            'image.image' => 'الملف يجب أن يكون صورة',
            'image.mimes' => 'نوع الصورة يجب أن يكون: jpeg, png, jpg, gif',
            'image.max' => 'حجم الصورة يجب أن لا يتجاوز 2 ميجابايت',
        ];
    }
}
