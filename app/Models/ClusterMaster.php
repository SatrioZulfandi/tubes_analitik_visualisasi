<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Cluster Master Model
 *
 * Menyimpan master segmentasi hasil Machine Learning K-Means.
 * Menjadi tabel referensi (Dimension Table) untuk Transjakarta Trips.
 */
class ClusterMaster extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'cluster_master';

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
        'cluster_code',
        'cluster_name',
        'description',
        'color',
        'icon',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accessor untuk mendapatkan label cluster (Contoh: C1 - Komuter Cepat Jarak Pendek).
     *
     * @return string
     */
    public function getClusterLabelAttribute(): string
    {
        return "{$this->cluster_code} - {$this->cluster_name}";
    }

    /**
     * Relasi One-to-Many ke TransjakartaTrip.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trips(): HasMany
    {
        return $this->hasMany(TransjakartaTrip::class, 'cluster_id', 'id');
    }
}
