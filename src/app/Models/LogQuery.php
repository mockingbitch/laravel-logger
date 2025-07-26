<?php

namespace phongtran\Logger\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogQuery extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * Fillable fields for a Profile.
     *
     * @var array
     */
    protected $fillable = [
        'query',
        'execution_time',
    ];

    /**
     * The attributes that should be mutated.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Create a new instance to set the table and connection.
     *
     * @return void
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('logger.query_table');
        $this->connection = config('logger.connection');
    }

    /**
     * Get the database connection.
     */
    public function getConnectionName()
    {
        return $this->connection;
    }

    /**
     * Get the database connection.
     */
    public function getTableName()
    {
        return $this->table;
    }
}
