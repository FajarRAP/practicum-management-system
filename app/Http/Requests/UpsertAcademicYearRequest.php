<?php

namespace App\Http\Requests;

use App\Models\AcademicYear;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpsertAcademicYearRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->method() === 'POST') {
            return $this->user()->can('academic_year.add');
        }

        if ($this->method() === 'PUT') {
            return $this->user()->can('academic_year.edit');
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $academicYear = $this->route('academic_year');
        $academicYearId = $academicYear?->id;

        return [
            'year' => [
                'required',
                'string',
                'max:9',
                "unique:academic_years,year,{$academicYearId},id,semester," . $this->semester,
            ],
            'semester' => ['required', 'in:GANJIL,GENAP'],
            'status' => ['nullable', 'in:DRAFT,ACTIVE,FINISHED'],
        ];
    }


    public function after()
    {
        return [
            // Hook ini berjalan setelah validasi dasar berhasil
            function (Validator $validator) {

                // Ambil status dari data yang sudah tervalidasi
                $status = $this->validated('status');

                if ($status === 'ACTIVE') {
                    $query = AcademicYear::where('status', 'ACTIVE');
                    $academicYear = $this->route('academic_year');

                    // Jika ini adalah proses update, kecualikan ID saat ini dari pengecekan
                    if ($academicYear) {
                        $query->where('id', '!=', $academicYear->id);
                    }

                    // Jika masih ada tahun ajaran lain yang aktif, gagalkan validasi
                    if ($query->exists()) {
                        $validator->errors()->add(
                            'error',
                            'Cannot set two active academic at the same time'
                        );
                    }
                }
            },

        ];
    }
}
