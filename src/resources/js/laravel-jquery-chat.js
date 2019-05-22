$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

// OOP Way
var fbChatPostCollector = new Array();
fbChat = {
  bootChat: function() {
    var myChat = this;

    // cargar mensajes cada X segundos
    myChat.getMessages();
  },
  addWindow: function(chat_win_id, user_name) {
    var myChat = this;

    var vWinId = 'chat_win_id_' + chat_win_id;
    // agregar ventana
    if ($('#' + vWinId).length == 0) {
      $('#chatsIndex > div').append(
        '<div id="' + vWinId + '" class="chat_win">' +
          '<div id="' +
            vWinId +
            '_header" class="chat_mje_header">' +
            user_name +
            '<button class="btn">X</button>' +
          '</div>' +
          '<div id="' +
            vWinId +
            '_mjes" class="chat_mjes"' +
          '></div>' +
          '<div class="chat_mje_inputs inactive">' +
            '<form id="' +
              vWinId +
              '_form" action="' +
              $('#chatsIndex').data('action') +
              '" method="post">' +
              '<input type="hidden" name="user_to_id" value="' +
                chat_win_id +
              '" />' +
              '<input ' +
                'type="text" ' +
                'class="chat_mje_inputs_message" ' +
                'name="message" ' +
                'maxlength="199" ' +
                'value="" ' +
                'placeholder="Escriba su mensaje" ' +
                'alt="Escriba su mensaje y presione Enter o el botón Enviar" ' +
                'title="Escriba su mensaje y presione Enter o el botón Enviar" ' +
                'autocomplete="off" ' +
              '/>' +
              '<input type="submit" class="chat_mje_inputs_send" name="send" value="Enviar" />' +
            '</form>' +
          '</div>' +
        '</div>'
      );
      $('#' + vWinId + ' .chat_mje_inputs_message').focus();
      $('#' + vWinId + ' .chat_mje_inputs_message').keyup(function(){
        if ($(this).val().trim()) {
          $(this).parent().parent().removeClass('inactive');
          $(this).parent().parent().addClass('active');
        } else {
          $(this).val('');
          $(this).parent().parent().removeClass('active');
          $(this).parent().parent().addClass('inactive');
        }
      });
      $('#' + vWinId + '_header').click(function(event){
        var status = 'max';
        if ($(this).next().is(':visible')) {
          status = 'min';
        }
        $(this).removeAttr('style');
        $(this).next().toggle();
        $(this).next().next().toggle();
      });
      $('#' + vWinId + '_header > button.btn').click(function(event){
        $(this).parent().parent().remove();
      });
      $('#' + vWinId + '_form').submit(function(event){
        var form = $(this);
        event.preventDefault();
        var mje = form.find('.chat_mje_inputs_message').val().trim();
        form.find('.chat_mje_inputs_message').val('');
        if (mje) {
          fbChatPostCollector.push({
            'user_to_id': form.find('input[name="user_to_id"]').val(),
            'message': mje
          });
          $('#' + vWinId + ' > .chat_mjes').append(
            '<div class="chat_mje me doDelete">' +
              '<div class="chat_mje_message">' +
                mje +
                ' <i class="fas fa-check"></i>' +
              '</div>' +
              '<div class="clearfloat"></div>' +
            '</div>'
          );
          // mover el scroll al final
          var scroll=$('#' + vWinId + ' > .chat_mjes');
          scroll.animate({scrollTop: scroll.prop("scrollHeight")}, 250);
        }
      });
    }
  },
  getMessages: function() {
    var myChat = this;

    const now = new Date();
    var start = $('#chatsIndex').data('start');
    var windows = new Array();
    var i = 0;
    if ($('#chatsIndex > div > div').length > 0) {
      $('#chatsIndex > div > div').each(function(){
        id = $(this).attr('id').replace('chat_win_id_', '');
        if ($('#chat_win_id_' + id + '_mjes').is(':visible')) {
          windows[i] = {'chat_win_id': id, 'status': 'max'};
        } else {
          windows[i] = {'chat_win_id': id, 'status': 'min'};
        }
        i++;
      });
    }
    $('#chatsIndex').data('start', 'false');
    fbChatPostTemp = fbChatPostCollector;
    fbChatPostCollector = new Array();
    $.ajax({
      url: $('#chatsIndex').data('action'),
      method: 'POST',
      data: {
        'start': start,
        'time': now.getTime(),
        'windows': windows,
        'fbChatPostCollector': fbChatPostTemp
      },
      success: function(data) {
        var jsonData = JSON.parse(data);
        // iterar para cada mensaje
        for (i = 0; i < jsonData.chats.length; i++) {
          // agregar ventana
          var vWinId = 'chat_win_id_' + jsonData.chats[i].chat_win_id;
          var vMjeId = 'chat_mje_id_' + jsonData.chats[i].id;
          myChat.addWindow(jsonData.chats[i].chat_win_id, jsonData.chats[i].user_name);

          // agregar mensaje
          if ($('#' + vMjeId).length == 0) {
            $('#' + vWinId + ' > .chat_mjes .doDelete').first().remove();
            $('#' + vWinId + ' > .chat_mjes').append(
              '<div id="' + vMjeId + '" class="chat_mje ' +
                jsonData.chats[i].person +
                '" alt="' + jsonData.chats[i].datetime + 'hs." title="' + jsonData.chats[i].datetime + 'hs."' +
              '>' +
                '<div class="chat_mje_message">' +
                  jsonData.chats[i].message +
                  ' <i class="fas fa-check-double"></i>' +
                '</div>' +
                '<div class="clearfloat"></div>' +
              '</div>'
            );

            // abrir la ventana si está cerrada
            if (jsonData.chats[i].type == 'new' && !$('#' + vWinId + ' > .chat_mjes').is(':visible')) {
              $('#' + vWinId + ' > .chat_mje_header').attr('style', 'background-color: #c33;');
            }

            // mover el scroll al final
            var scroll=$('#' + vWinId + ' > .chat_mjes');
            scroll.animate({scrollTop: scroll.prop("scrollHeight")}, 250);
          }
        }
        if (jsonData.windows) {
          $.map(jsonData.windows, function(val, i){
            if (val == 'min') {
              $('#chat_win_id_' + i + '_mjes').hide();
              $('#chat_win_id_' + i + '_mjes').next().hide();
            } else {
              $('#chat_win_id_' + i + '_mjes').show();
              $('#chat_win_id_' + i + '_mjes').next().show();
            }
          });
        }
        setTimeout(function() {
          myChat.getMessages();
        }, 2 * 1000);
      }
    });
  }
};

$(function(){
  if ($('#chatsIndex > div').length > 0) {
    // Initialize the chat
    fbChat.bootChat();
    $('.chatInit').click(function(event){
      event.preventDefault();
      fbChat.addWindow($(this).data('chat_win_id'), $(this).data('user_name'));
    });
  }
});
