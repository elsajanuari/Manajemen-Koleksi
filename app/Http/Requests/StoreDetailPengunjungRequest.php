<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDetailPengunjungRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $pemesananTiket = $this->route('pemesananTiket');
        return auth()->check() && auth()->user()->id === $pemesananTiket->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $pemesananTiket = $this->route('pemesananTiket');
        $ticket = $pemesananTiket->ticket;
        $jumlahTiket = $pemesananTiket->jumlah_tiket;

        $isKelompok = strtolower((string) $ticket->jenis_tiket) === 'event' &&
                      strtolower((string) $ticket->sub_jenis) === 'sunday painting' &&
                      (string) $ticket->kategori_pengunjung === 'Kelompok';

        $isWorkshop = strtolower((string) $ticket->jenis_tiket) === 'workshop';
        $isPelajar = strtolower((string) $ticket->kategori_pengunjung) === 'pelajar';

        if ($isKelompok) {
            return [
                'nama_kelompok' => ['required', 'string', 'max:255'],
                'daftar_anggota' => ['required', 'string'],
                'nama_penanggung_jawab' => ['required', 'string', 'max:255'],
                'alamat_penanggung_jawab' => ['required', 'string'],
                'nomor_ponsel_penanggung_jawab' => ['required', 'regex:/^(\+62|0)[0-9]{9,12}$/'],
                'email_penanggung_jawab' => ['required', 'email'],
            ];
        }

        $rules = [];
        if ($isPelajar) {
            $rules['bukti_pelajar'] = ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'];
        }

        for ($i = 1; $i <= $jumlahTiket; $i++) {
            $rules["pengunjung.$i.email"] = ['required', 'email'];
            $rules["pengunjung.$i.nomor_ponsel"] = ['required', 'regex:/^(\+62|0)[0-9]{9,12}$/'];
            $rules["pengunjung.$i.alamat"] = ['required', 'string'];
            $rules["pengunjung.$i.nama_lengkap"] = ['required', 'string', 'max:255'];

            if ($isWorkshop) {
                $rules["pengunjung.$i.pendidikan"] = ['required', 'string', 'max:255'];
            }
        }

        return $rules;
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'required' => 'Field :attribute harus diisi.',
            'email' => 'Field :attribute harus berupa email yang valid.',
            'regex' => 'Format :attribute tidak valid. Gunakan format 08xxxxxxxxxx atau +62xxxxxxxxxx',
            'string' => 'Field :attribute harus berupa teks.',
            'max' => 'Field :attribute tidak boleh lebih dari :max karakter.',
            'file' => 'Field :attribute harus berupa file yang valid.',
            'mimes' => 'Field :attribute hanya menerima format JPG, JPEG, PNG, atau PDF.',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        $attributes = [
            'nama_kelompok' => 'Nama Kelompok',
            'daftar_anggota' => 'Daftar Anggota',
            'nama_penanggung_jawab' => 'Nama Penanggung Jawab',
            'alamat_penanggung_jawab' => 'Alamat Penanggung Jawab',
            'nomor_ponsel_penanggung_jawab' => 'Nomor Ponsel Penanggung Jawab',
            'email_penanggung_jawab' => 'Email Penanggung Jawab',
            'bukti_pelajar' => 'Bukti Pelajar',
        ];

        $jumlahTiket = $this->route('pemesananTiket')->jumlah_tiket;
        for ($i = 1; $i <= $jumlahTiket; $i++) {
            $attributes["pengunjung.$i.nama_lengkap"] = "Nama Lengkap Pengunjung #$i";
            $attributes["pengunjung.$i.pendidikan"] = "Pendidikan Pengunjung #$i";
            $attributes["pengunjung.$i.alamat"] = "Alamat Pengunjung #$i";
            $attributes["pengunjung.$i.nomor_ponsel"] = "Nomor Ponsel Pengunjung #$i";
            $attributes["pengunjung.$i.email"] = "Email Pengunjung #$i";
        }

        return $attributes;
    }
}
