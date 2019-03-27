@component('mail::message')
# Здравствуйте, {{ $name }}!

Вы зарегистрированы как {{ $role }}.

Для того, чтобы войти в личный кабинет используйте ссылку: http://{{ $_SERVER['HTTP_HOST']}}{{config('cms.admin_uri')}}

E-mail: {{ $email }}

Пароль: {{ $password }}

@component('mail::button', ['url' => ('http://'.$_SERVER['HTTP_HOST'].config('cms.admin_uri'))])
Перейти в кабинет
@endcomponent

C уважением,<br>
CMSBot.
@endcomponent
