<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteQrCodes extends Model
{
    use HasFactory;
    public function QrType(){

        return $this->belongsTo(QrCodeType::class,'qr_type_id');
    }
}
