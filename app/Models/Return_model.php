<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Return_model extends Model
{
    use HasFactory;

    protected $table = 'returns';
    protected $fillable = ['borrowing_id', 'return_date', 'quantity_returned', 'condition', 'notes'];

    protected $casts = [
        'return_date' => 'date',
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }
}
