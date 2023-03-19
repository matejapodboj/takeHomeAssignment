<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RealEstateEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'real_estate_entries';

    protected $primaryKey = 'real_estate_entry_id';

    protected $fillable = [
        'real_estate_entry_id',
        'real_estate_entries_type',
        'real_estate_entries_address',
        'real_estate_entries_size',
        'real_estate_entries_number_of_bedrooms',
        'real_estate_entries_price',
        'real_estate_entries_latitude',
        'real_estate_entries_longitude'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'real_estate_entries_deleted_date'
    ];

    const DELETED_AT = 'real_estate_entries_deleted_date';

    public function searchWithinRadius($latitude, $longitude, $radius)
    {
        // Build the query to search for entries within the radius
        $query = RealEstateEntry::query()
        ->select('real_estate_entries.*')
        ->selectRaw(sprintf('(6371 * acos(cos(radians(%s)) * cos(radians(real_estate_entries_latitude)) * cos(radians(real_estate_entries_longitude) - radians(%s)) + sin(radians(%s)) * sin(radians(real_estate_entries_latitude)))) AS distance', $latitude, $longitude, $latitude))
        ->whereRaw(sprintf('ST_Within(point(real_estate_entries_longitude, real_estate_entries_latitude), ST_Buffer(PointFromText("POINT(%s %s)"), %s))', $longitude, $latitude, $radius))
        ->whereRaw(sprintf('(6371 * acos(cos(radians(%s)) * cos(radians(real_estate_entries_latitude)) * cos(radians(real_estate_entries_longitude) - radians(%s)) + sin(radians(%s)) * sin(radians(real_estate_entries_latitude)))) <= %s', $latitude, $longitude, $latitude, $radius));
    
        // Get the results
        $results = $query->get();
    
        // Return the results
        return $results;
    }
}
