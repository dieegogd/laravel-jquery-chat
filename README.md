# laravel-jquery-chat
Chat developed in jQuery for Laravel php7<br>
This chat works in Localhost without Internet, it works in Intranet using jQuery

![licence](https://img.shields.io/badge/licencia-MIT-red.svg?style=plastic)
![version](https://img.shields.io/badge/version-v0.4.2-blue.svg?style=plastic)
![testscase](https://img.shields.io/badge/testcase-0-green.svg?style=plastic)
![coverage](https://img.shields.io/badge/coverage-%250-yellow.svg?style=plastic)

![php](https://img.shields.io/badge/php->=7.1-yellow.svg?style=plastic)
![laravel](https://img.shields.io/badge/laravel-5.7.28-yellow.svg?style=plastic)
![date](https://img.shields.io/date/1557925074.svg?style=plastic)
![status](https://img.shields.io/badge/status-down-green.svg?style=plastic)

### INSTALLATION

1. The first step is get by composer, put in root directory of your project and execute:
```bash
composer require dieegogd/laravel-jquery-chat @dev
```

2. Add the Service Container in your file config/app.php
```bash
  Dieegogd\LaravelJqueryChat\LaravelJqueryChatServiceProvider::class,
```

3. Publish assets with the next command:
```bash
php artisan vendor:publish --tag=public --force
```

4. Migrate table with command:
```bash
php artisan migrate
```

5. Edit your resources/js/app.js and add this line at the end of file:
```bash
require('./laravel-jquery-chat.js');
```

6. Edit your resources/sass/app.scss and add this line at the end of file:
```bash
@import 'laravel-jquery-chat.scss';
```

7. You need execute NPM with the command:
```bash
npm run dev
```

8. In your master page, inmediatly after body put the next lines:
```bash
<body>
    @if (Auth::check())
        @include('laravel-jquery-chat.init')
    @endif
    ...
    ...
    ...
</body>
```

9. For open a window chat, you need to do a button
```bash
@if ($user->id != \Auth::user()->id)
    <button
        class="chatInit"
        data-chat_win_id="{{$user->id}}"
        data-user_name="{{$user->name}}"
    >
        Chat with {{$user->name}}
    </button>
@endif
```


### DEVELOPER

Diego García > https://github.com/dieegogd/

<a rel="nofollow" target="_blank" href="https://fb.me/dgadmin.com.ar">https://fb.me/dgadmin.com.ar</a> |
<a rel="nofollow" target="_blank" href="https://m.me/dgadmin.com.ar">https://m.me/dgadmin.com.ar</a>

Powered by DGAdmin

<a target="_blank" href="https://www.dgadmin.com.ar">https://www.dgadmin.com.ar</a>

<img src="https://www.dgadmin.com.ar/images/logo-slider-old.png" width="200" alt="DGAdmin">
