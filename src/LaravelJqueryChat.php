<?php
namespace Dieegogd\LaravelJqueryChat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaravelJqueryChat extends Model
{
    use SoftDeletes;
    protected $dates = ['entregado_from_at', 'entregado_to_at', 'deleted_at', 'created_at', 'updated_at'];
    protected $fillname = [
        'message'
    ];
    protected $fillable = [
        'message',
        'user_from_id',
        'user_to_id'
    ];
    public function from()
    {
        return $this->belongsTo('App\User', 'user_from_id');
    }
    public function to()
    {
        return $this->belongsTo('App\User', 'user_to_id');
    }
    /**
     * Dando formato para salida en la vista
     *
     * @return \Illuminate\Http\Response
     */
    public function formatForView()
    {
        $chat = array();
        $chat['id'] = $this->id;
        $chat['message'] = $this->message;
        if ($this->user_from_id == \Auth::user()->id) {
            $chat['user_name'] = $this->to->name;
            $chat['datetime'] = $this->entregado_from_at->format(config('app.format_datetime'));
            $chat['chat_win_id'] = $this->user_to_id;
            $chat['person'] = 'me';
        } else {
            $chat['user_name'] = $this->from->name;
            $chat['datetime'] = $this->entregado_to_at->format(config('app.format_datetime'));
            $chat['chat_win_id'] = $this->user_from_id;
            $chat['person'] = 'you';
        }
        $chat['type'] = 'new';
        return $chat;
    }
}
