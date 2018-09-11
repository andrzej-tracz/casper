@if(!empty($errors) && count($errors))
    <div class="container">
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $key => $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@if($message = session('message'))
    <div class="container">
        <div class="alert alert-success">
            <ul>
                <li>
                    {{ $message }}
                </li>
            </ul>
        </div>
    </div>
@endif
