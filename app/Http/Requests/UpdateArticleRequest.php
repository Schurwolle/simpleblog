<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateArticleRequest extends Request
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
        return [
            'title'      => 'required|max:78|unique:articles,title,'.$this->articles->id,
            'slug'       => 'required|unique:articles,slug,'.$this->articles->id,
            'body'       => 'required|max:64443|ckeimgs',
            'addImgs.*'  => 'image|max:2048',
            'tag_list.*' => 'alpha_num',
            'images'     => 'size:'.count($this->addImgs),
        ];
    }
}
