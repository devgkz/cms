@extends('admin/base')

@section('content')
<div class="container" style="max-width:340px; margin: 1rem auto;">
    <div class="card" style="box-shadow: 0 1px 8px rgba(0,0,0,0.2);">
        
        <div class="card-block">        
            <h6>Требуется авторизация</h6>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                @if ($errors->has('email'))
                    <div class="alert danger mb-2" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </div>
                @endif
                @if ($errors->has('password'))
                    <div class="alert danger mb-2" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </div>
                @endif
                
                <div class="form-group top-label">
                    <input type="text" name="email" id="email" class="input{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="E-mail" value="{{ old('email') }}">                     
                </div>
                <div class="form-group top-label">
                    <input type="password" name="password" id="password" class="input{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Пароль">                    
                </div>
                
                <div class="form-group top-label">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>

                        <label class="form-check-label" for="remember">
                            Запомнить меня
                        </label>
                    </div>
                </div>
                
                <div class="form-group text-center">
                    <button class="btn primary" style="width:100%" type="submit"><i class="ico left fa fa-key"></i>Войти</button> 
                    <!--a class="btn ml-2" href="{{ route('password.request') }}">Забыли пароль?</a-->
                </div>
                
            </form>
        </div>
    </div>
</div>
@endsection
