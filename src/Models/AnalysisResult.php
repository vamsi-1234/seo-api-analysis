<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalysisResult extends Model
{
    protected $table = 'analysis_results';
    public $timestamps = false;
    
    protected $fillable = [
        'keyword_density',
        'readability_score',
        'headlines',
        'meta_description'
    ];
    
    protected $casts = [
        'keyword_density' => 'array',
        'headlines' => 'array',
        'meta_description' => 'array'
    ];
}