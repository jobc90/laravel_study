<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    //권한확인
    public function authorize(): bool
    {
        // Route::patch('articles/{article}', 'update')->name('articles.update');
        // dd($this->route('article'));
        // dd($this->user());
        // 컨트롤러에 있던 수정권한을 request에 옮김.
        return $this->user()->can("update", $this->route('article'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    //유효성검사
    public function rules(): array
    {
        return [
            'body' => [
                'required',
                'string',
                'max:255'
            ],
        ];
    }
}
