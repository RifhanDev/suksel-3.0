<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class PetenderPerformanceRequest extends FormRequest
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
     * Custom validator
     */
    public function withValidator(Validator $validator) 
    {
        // Get method action
        $action = $this -> request -> get('action');

        // Check action type
        if ($action == 'store') 
        {
            if ($this -> request -> get('type1') == null OR $this -> request -> get('type1') == 'Lain - lain')
            {
                $validator -> addRules([
                    "type2" => ["required"],
                ]);
            }
            else
            {
                $validator -> addRules([
                    "type1" => ["required"],
                ]);
            }
            
            $validator -> addRules([
                "quantity"         => ["required"],
                "cost"             => ["required"],
                "acquisition_date" => ["required"],
                "opinion"          => ["required"],
                "review"           => ["required"],
                "total_score"      => ["required"],

                // Criteria
                "scale_1"  => ["required"],
                "scale_2"  => ["required"],
                "scale_3"  => ["required"],
                "scale_4"  => ["required"],
                "scale_5"  => ["required"],
                "scale_6"  => ["required"],
                "review_1" => ["nullable"],
                "review_2" => ["nullable"],
                "review_3" => ["nullable"],
                "review_4" => ["nullable"],
                "review_5" => ["nullable"],
                "review_6" => ["nullable"],
            ]);
        }

        // Return error
        $validator -> after(function() {
            return back() -> with('ErrorRequest', 'active');
        });
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules() : array
    {
        return [
            //
        ];
    }

    /**
     * For customizing error message
     */
    public function messages()
    {
        return [
            // Required
            "type1.required"            => "Jenis bekalan wajib diisi",
            "type2.required"            => "Jenis bekalan wajib diisi",
            "quantity.required"         => "Kuantiti wajib diisi",
            "cost.required"             => "Jumlah kos wajib diisi",
            "acquisition_date.required" => "Tarikh perolehan wajib diisi",
            "opinion.required"          => "Cadangan pegawai penilai wajib diisi",
            "review.required"           => "Ulasan keseluruhan wajib diisi",
            "scale_1.required"          => "Kriteria penilaian pertama wajib diisi",
            "scale_2.required"          => "Kriteria penilaian kedua wajib diisi",
            "scale_3.required"          => "Kriteria penilaian ketiga wajib diisi",
            "scale_4.required"          => "Kriteria penilaian keempat wajib diisi",
            "scale_5.required"          => "Kriteria penilaian kelima wajib diisi",
            "scale_6.required"          => "Kriteria penilaian keenam wajib diisi",
        ];
    }
}
