<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Transjakarta Trip Model
 *
 * Menyimpan data transaksi perjalanan (Fact Table) dari dataset
 * yang telah diproses menggunakan algoritma K-Means Clustering.
 */
class TransjakartaTrip extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'transjakarta_trips';

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
        'trans_id',
        'pay_card_bank',
        'pay_card_type',
        'pay_card_sex',
        'pay_card_birth_date',
        'corridor_name',
        'tap_in_time',
        'tap_out_time',
        'pay_amount',
        'age',
        'age_group',
        'travel_duration',
        'tap_in_hour',
        'time_category',
        'peak_hour',
        'day_name',
        'day_type',
        'stops_passed',
        'travel_type',
        'cluster_id',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pay_amount'          => 'decimal:2',
        'travel_duration'     => 'decimal:2',
        'peak_hour'           => 'boolean',
        'pay_card_birth_date' => 'integer',
        'age'                 => 'integer',
        'tap_in_hour'         => 'integer',
        'stops_passed'        => 'integer',
        'tap_in_time'         => 'datetime',
        'tap_out_time'        => 'datetime',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
    ];

    /**
     * Relasi Many-to-One ke ClusterMaster.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cluster(): BelongsTo
    {
        return $this->belongsTo(ClusterMaster::class, 'cluster_id', 'id');
    }

    /**
     * Query Scope untuk memfilter transaksi pada jam sibuk (Peak Hour).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePeakHour(Builder $query): Builder
    {
        return $query->where('peak_hour', true);
    }

    /**
     * Query Scope untuk memfilter transaksi pada hari kerja (Weekday).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWeekday(Builder $query): Builder
    {
        return $query->where('day_type', 'Weekday');
    }

    /**
     * Query Scope untuk memfilter transaksi pada akhir pekan (Weekend).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWeekend(Builder $query): Builder
    {
        return $query->where('day_type', 'Weekend');
    }

    /**
     * Query Scope untuk memfilter transaksi berdasarkan Cluster tertentu.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $clusterId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCluster(Builder $query, int $clusterId): Builder
    {
        return $query->where('cluster_id', $clusterId);
    }
}
