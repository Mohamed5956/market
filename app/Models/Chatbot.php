<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatbot extends Model
{
    use HasFactory;
    protected $fillable = [
        'ar_question',
        'en_question',
        'ar_answer',
        'en_answer'
];
}
