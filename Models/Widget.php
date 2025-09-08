<?php

namespace Amplify\Widget\Models;

use Amplify\System\Backend\Traits\HasTags;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Widget extends Model implements Auditable
{
    use CrudTrait, HasTags;
    use \OwenIt\Auditing\Auditable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    public const WIDGET_PLACEMENT_TYPE = [
        'page' => 'Page',
        'global' => 'Global',
        'topbar' => 'Topbar',
        'footer' => 'Footer',
    ];

    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $table = 'widgets';

    protected $guarded = ['id'];

    // protected $hidden = [];
    protected $fillable = ['name', 'model', 'data', 'blade', 'template_id', 'placement', 'page_types', 'code', 'description'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function template(): BelongsTo
    {
        return $this->belongsTo(\Amplify\System\Cms\Models\Template::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
