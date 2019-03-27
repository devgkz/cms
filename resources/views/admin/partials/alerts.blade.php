@if (count($errors) > 0)
    <div class="alert danger dismissible mb-2">
        
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(Session::has('info'))
    <div class="alert info dismissible mb-2">
        
        {{Session::get('info')}}
    </div>
@endif

@if(Session::has('warning'))
    <div class="alert warning dismissible mb-2">
        
        {{Session::get('warning')}}
    </div>
@endif

@if(Session::has('success'))
    <div class="alert success dismissible mb-2">
        
        {{Session::get('success')}}
    </div>
@endif

@if(Session::has('error'))
    <div class="alert danger dismissible mb-2">
        
        {{Session::get('error')}}
    </div>
@endif