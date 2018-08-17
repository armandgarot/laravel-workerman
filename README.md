# Laravel Workerman

A Larvel server side alternative implementation of [socket.io](https://github.com/socketio/socket.io) in PHP based on [Workerman](https://github.com/walkor/Workerman).<br>

## Requirements

- php: >=5.3
- Laravel: >= 5.0

## Install

You can install this package via composer using this command:

```bash
composer require armandgarot/laravel-workerman
```

You can publish the config-file with:

```bash
php artisan vendor:publish --provider="ArmandGarot\LaravelWorkerman\LaravelWorkermanServiceProvider" --tag="config"
```

## Commands

```bash
php artisan workerman:server start
```

```bash
php artisan workerman:server stop
```

```bash
php artisan workerman:server status
```

## Examples

### Simple chat

```php
// app/Events/SendChatMessage.php

namespace App\Events;

use Illuminate\Console\Command;

class SendChatMessage extends Command
{
    /**
     * Create a new event instance.
     *
     * @param PHPSocketIO\SocketIO $server
     * @return void
     */
    public function __construct($server)
    {
		$server->on('connection', function($socket) use($server) {
			$socket->on('chat message', function($message) use($server) {
				$server->emit('chat message', $message);
			});
		});
    }
}
```

```php
// resources/views/chat.blade.php

<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Styles -->
		<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8">
					<div class="card mt-5">
						<div class="card-header">{{ config('app.name') }}</div>

						<div class="card-body">
							<ul id="messages" class="list-group"></ul>
				
							<form class="mt-5">
								<div class="input-group mb-3">
									<input type="text" class="form-control" id="message" autocomplete="off" placeholder="Message">
									<div class="input-group-append">
										<button class="btn btn-primary" type="submit">Send</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
		<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
		<script>
			$(function(){
				var socket = io("http://localhost:{{ config('laravel-workerman.server.port') }}");
				
				$('form').submit(function(){
					socket.emit('chat message', $('#message').val());
					$('#message').val('');
					
					return false;
				});
				
				socket.on('chat message', function(message){
					$('#messages').append($('<li class="list-group-item">').text(message));
				});
			});
		</script>
    </body>
</html>
```

```php
// config/laravel-workerman.php

return [

    /**
     * Listen port for SocketIO client.
     */
    'server' => [
		'port' => 3000,
	],
	
	/**
	 * Events dispatched when SocketIO server is running.
	 */
	'events' => [
		App\Events\SendChatMessage::class,
	],
];
```

```php
// routes/web.php

Route::get('/chat', function () {
    return view('chat');
});
```

```bash
php artisan workerman:server start
```

Go to http://your-app/chat

# License
MIT
