@extends ('template::back.amy.layouts.main')

@section ('content')
 <div class="panel panel-default">
   <div class="panel-heading">
     <div class="panel-title">Редактирование категории</div>
   </div>

  @if (session('result'))
   <div class="alert alert-info" role="alert">
     {{ session('result') }}

     @if (session('slug'))
      <div class="btn-group">
        <a href="/category/{{ session('slug') }}" type="button" class="btn btn-default">Просмотр</a>
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
          <li><a href="/category/{{ session('slug') }}" target="_blank">В новом окне</a></li>
        </ul>
      </div>
     @endif

   </div>
  @endif

  @include ('template::back.amy.category.errors')

  <ol class="breadcrumb">
    <li><a href="/dashboard/category">Категории</a></li>
    <li class="active">Редактирование категории</li>
  </ol>

   <div class="panel-body">
     <form role="form" method="POST" action="/dashboard/category/edit/id/{{ $category->id }}">
       <div class="form-group">
        <label for="name">Название*</label>
        <input type="text" value="{{ $category->name }}" class="form-control" id="name" name="name" placeholder="Введите название">
       </div>

       <div class="form-group">
        <label for="description">Описание</label>
        <input type="text" value="{{ $category->description }}" class="form-control" id="description" name="description" placeholder="Введите описание">
        <p class="help-block">Описание может видеть только администратор</p>
       </div>

       <div class="form-group">
        <label for="slug">URL</label>
        <input type="text" class="form-control" id="slug" value="{{ $category->slug }}" name="slug" placeholder="Введите URL">
        <p class="help-block">Если вы не знаете предназначение данного поля, то оставьте его неизменным</p>
       </div>

       <div class="form-group">
         <label for="role">Доступ*</label>
         <select class="form-control" id="role" name="role">
           @foreach ($roles as $role)
           <option value="{{ $role->id }}"
             @if ($role->id==$category->role->id) selected @endif
           >{{ $role->title }}</option>
           @endforeach
          </select>
       </div>

       {{ csrf_field() }}
       <button type="submit" class="btn btn-success">Применить</button>
      </form>
   </div>
 </div>
@stop
