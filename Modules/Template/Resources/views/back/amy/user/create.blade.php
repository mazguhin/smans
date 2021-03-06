@extends ('template::back.amy.layouts.main')

@section ('content')
 <div class="panel panel-default">
   <div class="panel-heading">
     <div class="panel-title">Создать нового пользователя</div>
   </div>

  @if (session('result'))
   <div class="alert alert-info" role="alert">
     {{ session('result') }}

     @if (session('user_id'))
      <div class="btn-group">
        <a href="/user/{{ session('user_id') }}" type="button" class="btn btn-default">Просмотр</a>
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
          <li><a href="/user/id/{{ session('user_id') }}" target="_blank">В новом окне</a></li>
        </ul>
      </div>
     @endif

   </div>
  @endif

  @include ('template::back.amy.user.errors')
  <ol class="breadcrumb">
    <li><a href="/dashboard/user">Пользователи</a></li>
    <li class="active">Создание пользователя</li>
  </ol>

   <div class="panel-body">
     <form role="form" method="POST" action="/dashboard/user/create">
       <div class="form-group">
        <label for="name">Имя*</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Введите имя" required>
       </div>

       <div class="form-group">
        <label for="email">E-mail*</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Введите e-mail">
       </div>

       <div class="form-group">
        <label for="password">Пароль*</label>
        <input type="password" class="form-control" id="password" value="{{ old('password') }}" name="password" placeholder="Введите пароль">
       </div>

       <div class="form-group">
         <label for="role">Права*</label>
         <select class="form-control" id="role" name="role">
           @foreach ($roles as $role)
             <option value="{{ $role->id }}">{{ $role->title }}</option>
           @endforeach
          </select>
       </div>

       {{ csrf_field() }}
       <button type="submit" class="btn btn-success">Создать</button>
      </form>
   </div>
 </div>
@stop
