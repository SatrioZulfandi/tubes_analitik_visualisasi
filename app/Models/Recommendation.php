<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Recommendation Model
 *
 * Menyimpan daftar rekomendasi operasional dan rencana aksi
 * berdasarkan analisis data dan profil segmen penumpang.
 */
class Recommendation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'recommendations';

    /**
     * Indikator penggunaan timestamps bawaan Laravel.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'display_order',
        'is_active',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active'     => 'boolean',
        'display_order' => 'integer',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];
}
