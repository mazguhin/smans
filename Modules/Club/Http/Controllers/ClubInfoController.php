<?php

namespace Modules\Club\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use RoleHelper;
use Settings;
use Logs;
use Modules\Club\Entities\Club;
use Modules\Category\Entities\Category;
use Modules\Article\Entities\Article;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ClubInfoController extends Controller
{
  use ValidatesRequests;

  protected $frontTemplate = '';

  public function __construct()
  {
    $this->frontTemplate = Settings::getFrontTemplate();
  }

  public function validateForm(Request $request)
  {
    return ($this->validate($request, [
      'title' => 'required|max:35',
      'description' => 'max:255',
      'editor' => 'required'
    ],[
      'title.required' => 'Заполните заголовок',
      'editor.required' => 'Заполните основной текст',
      'title.max' => 'Макс. кол-во символов: 35 (Заголовок)',
      'description.max' => 'Макс. кол-во символов: 255 (Описание)',
    ]));
  }

  public function show(Request $request, $id_club, $id_article)
  {
    return view('template::front.'.$this->frontTemplate.'.club.info.show', [
      'club' => Club::findOrFail($id_club),
      'article' => Article::findOrFail($id_article)
    ]);
  }

  public function create($id_club)
  {
    // validation permission for this page
    if (!RoleHelper::validatePermissionForClub($id_club))
      return view('template::front.'.$this->frontTemplate.'.club.accsesDenied');

    if (count(Club::findOrFail($id_club)->info->articles)>=2)
      return redirect()->back()->with([
        'result' => 'Достигнут лимит информационных страниц клуба (3)'
      ]);

    return view('template::front.'.$this->frontTemplate.'.club.info.create', [
      'id_club' => $id_club
    ]);
  }

  public function store(Request $request, $id_club)
  {
    // validation permission for this page
    if (!RoleHelper::validatePermissionForClub($id_club))
      return view('template::front.'.$this->frontTemplate.'.club.accsesDenied');

    //validation
    $this->validateForm($request);

    $club = Club::findOrFail($id_club);

    if (count(Club::findOrFail($id_club)->info->articles)>=2)
      return redirect('/club/id/'.$id_club)->with([
        'result' => 'Достигнут лимит информационных страниц клуба (3)'
      ]);

    $article = new Article;
    $article->title = $request->title;
    $article->description = $request->description;
    $article->body = $request->editor;
    $article->role_id = 1;
    $article->category_id = $club->cinfo_id;

    // generation slug
    if (empty($request->slug)) $slug=str_slug($request->title);
      else $slug = $request->slug;

    if (Article::where('slug', $slug)->count()>0)
      $article->slug = $slug.'-'.\Carbon\Carbon::now()->format('d-m-Y-h-m-s');
    else
      $article->slug = $slug;

    if ($request->user()->articles()->save($article)) {
      Logs::set('Добавлена страница в клубе [CLUB: '.$club->id.'] ['.$article->title.']');
      return redirect('/club/id/'.$id_club)->with([
        'result' => 'Страница успешно добавлена',
        'article_id' => $article->id
      ]);
    }
    else
      return redirect()->back()->with('result', 'Возникла ошибка');
  }

  public function edit($id_club,$id_article)
  {
    // validation permission for this page
    if (!RoleHelper::validatePermissionForClub($id_club))
      return view('template::front.'.$this->frontTemplate.'.club.accsesDenied');

    return view('template::front.'.$this->frontTemplate.'.club.info.edit', [
      'id_club' => $id_club,
      'article' => Article::findOrFail($id_article)
    ]);
  }

  public function save(Request $request, $id_club,$id_article)
  {
    // validation permission for this page
    if (!RoleHelper::validatePermissionForClub($id_club))
      return view('template::front.'.$this->frontTemplate.'.club.accsesDenied');

    //validation
    $this->validateForm($request);

    $article = Article::findOrFail($id_article);
    $article->title = $request->title;
    $article->description = $request->description;
    $article->body = $request->editor;

    // generation slug
    if (empty($request->slug)) $slug=str_slug($request->title);
      else $slug = $request->slug;

    if (Article::where('slug', $slug)->count()>0)
      $article->slug = $slug.'-'.\Carbon\Carbon::now()->format('d-m-Y-h-m-s');
    else
      $article->slug = $slug;

    if ($article->save()) {
      Logs::set('Изменена страница в клубе [CLUB: '.$id_club.'] ['.$article->title.']');
      return redirect('/club/id/'.$id_club.'/info/id/'.$article->id)->with([
        'result' => 'Страница успешно обновлена',
        'article_id' => $article->id
      ]);
    }
    else
      return redirect()->back()->with('result', 'Возникла ошибка');
  }

  public function delete(Request $request, $id_club, $id_article)
  {
    // validation permission for this page
    if (!RoleHelper::validatePermissionForClub($id_club))
      return view('template::front.'.$this->frontTemplate.'.club.accsesDenied');

    $article = Article::where('id',$id_article)->firstOrFail();
    if ($article->delete()) {
      Logs::set('Удалена страница в клубе [CLUB: '.$id_club.'] ['.$article->title.']');
      return redirect('/club/id/'.$id_club)->with([
        'result' => 'Страница успешно удалена'
      ]);
    }
  }
}
