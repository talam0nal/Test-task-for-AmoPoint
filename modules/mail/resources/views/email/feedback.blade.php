<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="gray-bg">
Получен запрос от пользователя {{ $message_author }} ({{ $message_author_email }})
<ul>
    @if(isset($message_text))
        <li>Сообщение: {{ $message_text }};</li>
    @endif
</ul>
<h3>Служебная информация</h3>
<ul>
    <li>Дата: {{ date('d.m.Y, H:i') }};</li>
    <li>IP: {{ \Illuminate\Support\Facades\Request::ip() }};</li>
</ul>
</body>
</html>