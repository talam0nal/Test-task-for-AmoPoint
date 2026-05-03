<?php
/**
 * Created by Velgir
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Message extends Model
{
protected $table = "messages";

    protected $fillable = [
        "text", "dialog_id", "author_id"
    ];
    
    public function dialog()
    {
        return $this->belongsTo(Dialog::class,
                "dialog_id", "id");
    }
    
    public function author()
    {
        return $this->belongsTo(User::class,
                "author_id", "id");
    }
    

}