<?php

namespace Dieegogd\LaravelJqueryChat\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dieegogd\LaravelJqueryChat\LaravelJqueryChat;
use Auth;

class LaravelJqueryChatController extends Controller
{
    /**
     * Interface of actions of Chat.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $chats_json = array();
        // guardar mensajes que se envían
        if ($request->fbChatPostCollector) {
            foreach ($request->fbChatPostCollector as $coll) {
                $oStore = new LaravelJqueryChat([
                    'message' => trim($coll['message']),
                    'user_from_id' => Auth::user()->id,
                    'user_to_id' => $coll['user_to_id']
                ]);
                $oStore->save();
            }
        }
        $chats_unread = LaravelJqueryChat::
            where(function ($query) {
                $query
                    ->where(function ($q) {
                        $q
                            ->where('user_from_id', '=', Auth::user()->id)
                            ->where('entregado_from_at', '=', null)
                        ;
                    })
                    ->orWhere(function ($q) {
                        $q
                            ->where('user_to_id', '=', Auth::user()->id)
                            ->where('entregado_to_at', '=', null)
                        ;
                    })
                ;
            })
            ->orderBy('id')
            ->get()
        ;
        // procesar los datos de chats
        foreach ($chats_unread as $chat) {
            // modificar la fecha de leído
            if ($chat->user_from_id == Auth::user()->id) {
                $chat->entregado_from_at = date('Y-m-d H:i:s');
            } else {
                $chat->entregado_to_at = date('Y-m-d H:i:s');
            }
            $chat->save();
            // dar formato a los campos a mostrar
            $chats_json[] = $chat->formatForView();
            // agregar en sesión la ventana
            if ($chat->user_from_id == Auth::user()->id) {
                $request->session()->put('chats.'.$chat->user_to_id, 'max');
            } else {
                $request->session()->put('chats.'.$chat->user_from_id, 'max');
            }
        }
        if ($request->start == 'true') {
            $windows = $request->session()->get('chats');
            if (is_array($windows) and count($windows) > 0) {
                foreach ($windows as $win => $status) {
                    $chats_open = LaravelJqueryChat::where('user_from_id', '=', $win)
                        ->orWhere('user_to_id', '=', $win)
                        ->limit(10)
                        ->orderBy('id', 'desc')
                        ->get()
                    ;
                    foreach ($chats_open as $chat) {
                        // dar formato a los campos a mostrar
                        $chats_tmp = $chat->formatForView();
                        $chats_tmp['type'] = 'old';
                        array_unshift($chats_json, $chats_tmp);
                    }
                }
            }
            // devolver JSON para jQuery
            print json_encode([
                'windows' => $windows,
                'chats' => $chats_json
            ]);
        } else {
            // guardar estados de las ventanas
            $newWin = [];
            if ($request->windows) {
                foreach ($request->windows as $win) {
                    $newWin[$win['chat_win_id']] = $win['status'];
                }
            }
            $request->session()->put('chats', $newWin);
            // devolver JSON para jQuery
            print json_encode([
                'chats' => $chats_json
            ]);
        }
    }
}