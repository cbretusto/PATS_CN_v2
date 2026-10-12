<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreAssemblyDetailsRequest extends FormRequest
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
            'pre_assy_station_code' => 'required',
            'po_no'                 => '',
            'lot_qty'               => '',
            'other_docs_number'     => 'required',
            'revision'              => 'required',
            'remarks'               => 'required'
        ];
    }
}
