<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historylist extends Model
{
    //Please Note: This is a model for the SAM database, not the local DJLand database
    protected $connection = 'samdb';
    protected $table = 'historylist';
    protected $primaryKey = 'ID';
}
