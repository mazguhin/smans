@extends ('template::back.nova.layouts.main')

@section ('content')

@if (session('result'))
<div class="alert alert-info" role="alert">
  {{ session('result') }}

  @if (session('slug'))
  <div class="btn-group">
    <a href="/staff/category/{{ session('slug') }}" type="button" class="btn btn-default">Просмотр</a>
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <span class="caret"></span>
      <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="/staff/category/{{ session('slug') }}" target="_blank">В новом окне</a></li>
    </ul>
  </div>
  @endif

</div>
@endif

 <div class="panel panel-default">
   <div class="panel-heading">
     <div class="panel-title">Создать новую категорию</div>
   </div>


  @include ('template::back.nova.staff.category.errors')
  <ol class="breadcrumb">
    <li><a href="/dashboard/staff/category">Категории сотрудников</a></li>
    <li class="active">Создание категории</li>
  </ol>

   <div class="panel-body">
     <form role="form" method="POST" action="/dashboard/staff/category/create">
       <div class="form-group">
        <label for="name">Название*</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Введите название" required>
       </div>

       <div class="form-group">
        <label for="description">Описание</label>
        <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}" placeholder="Введите описание">
        <p class="help-block">Описание может видеть только администратор</p>
       </div>

       <div class="form-group">
        <label for="slug">URL</label>
        <input type="text" class="form-control" id="slug" value="{{ old('slug') }}" name="slug" placeholder="Введите URL">
        <p class="help-block">Если вы не знаете предназначение данного поля, то оставьте его неизменным</p>
       </div>

       {{ csrf_field() }}
       <button type="submit" class="btn btn-success">Создать</button>
      </form>
   </div>
 </div>
@stop
