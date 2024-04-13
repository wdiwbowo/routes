<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stuff extends Model
{
    // jika di migrationnya menggunakan $table->sofDeletes
    use SoftDeletes;

    // fillable / guarded
    // menentukan colum wajib diisi (colum yang bisa diisi di luar)
    protected $fillable = ["name", "category"];
    // protected $guarded = ['id']

    // property opsional :
    // kalau primary key bukan id : public $primarykey = 'no'
    // kalu misal gapake timestamps di migration : public $timestamps = false 

    // relasi
    // nama function : samain kaya model, kata pertama huruf kecil
    // model yang PK : hasOne / hasMany
    // panggil namaModel::class
    public function StuffStock()
    {
        return $this->hasOne(StuffStock::class);
    }

    // relasi hasMany : nama func jamak
    public function InboundStuff()
    {
        return $this->hasMany(InboundStuff::class);
    }

    public function lendings()
    {
        return $this->hasMany(Lending::class);
    }
}