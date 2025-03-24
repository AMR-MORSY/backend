<?php

namespace App\Models;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'original_name',
        'mime_type',
        'size',
        'user_id',
        'invoice_id'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    protected function path(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $pdfData = base64_encode(file_get_contents($value));
                $pdfUrl = 'data:application/pdf;base64,' . $pdfData;
                return $pdfUrl;
            }
        );
    }
}
