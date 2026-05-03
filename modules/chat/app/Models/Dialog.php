<?php
/**
 * Created by Velgir
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Dialog extends Model
{
protected $table = "dialogs";

    public $timestamps = false;

    protected $fillable = [
        "sender_id", "reader_id", "last_message"
    ];
    
    public function sender()
    {
        return $this->belongsTo(User::class,
                "sender_id", "id");
    }
    
    public function reader()
    {
        return $this->belongsTo(User::class,
                "reader_id", "id");
    }
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}