<?php

namespace Modules\Article\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Auth;
use RoleHelper;
use Settings;
use Modules\Article\Entities\Article;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BackArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

     use ValidatesRequests;

     protected $backTemplate = '';

     public function __construct()
     {
       $this->backTemplate = Settings::getBackTemplate();
     }

    public function index()
    {
    }

    public function validateForm(Request $request)
    {
      return ($this->validate($request, [
        'title' => 'required|max:255',
        'description' => 'max:255',
        'editor' => 'required',
        'role' => 'required|max:255',
        'category' => 'required|max:255',
        'slug' => 'max:255',
      ],[
        'title.required' => 'Заполните заголовок',
        'editor.required' => 'Заполните основной текст',
        'category.required' => 'Выберите категорию',
        'role.required' => 'Назначьте доступ',
        'max' => 'Макс. кол-во символов: 255 (Заголовок, Описание, URL)'
      ]));
    }

    public function show()
    {
      $arrayFromStartPageString = explode("/",Settings::get('startPage'));
      if ($arrayFromStartPageString[1]!='article')
        $arrayFromStartPageString[3]=0;

      return view('template::back.'.$this->backTemplate.'.article.show',[
        'articles' => Article::orderBy('created_at', 'desc')->paginate(10),
        'startPageId' => $arrayFromStartPageString[3]
      ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
      return view('template::back.'.$this->backTemplate.'.article.create',[
        'categories' => \Modules\Category\Entities\Category::all(),
        'roles' => \Modules\Dashboard\Entities\Role::all()
      ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
      //validation
      $this->validateForm($request);

      $article = new Article;
      $article->title = $request->title;
      $article->description = $request->description;
      $article->body = $request->editor;
      $article->role_id = $request->role;
      $article->category_id = $request->category;

      // generation slug
      if (empty($request->slug)) $slug=str_slug($request->title);
        else $slug = $request->slug;

      if (Article::where('slug', $slug)->count()>0)
        $article->slug = $slug.'-'.\Carbon\Carbon::now()->format('d-m-Y-h-m-s');
      else
        $article->slug = $slug;

      if ($request->user()->articles()->save($article))
        return redirect()->back()->with([
          'result' => 'Статья успешно добавлена',
          'slug' => $slug
        ]);
      else
        return redirect()->back()->with('result', 'Возникла ошибка');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */

    public function editById($id_article)
    {
      return view('template::back.'.$this->backTemplate.'.article.edit',[
        'categories' => \Modules\Category\Entities\Category::all(),
        'article' => Article::where('id',$id_article)->firstOrFail(),
        'roles' => \Modules\Dashboard\Entities\Role::all()
      ]);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id_article)
    {
      // validation
      $this->validateForm($request);

      $article = Article::where('id',$id_article)->firstOrFail();
      $article->title = $request->title;
      $article->description = $request->description;
      $article->body = $request->editor;
      $article->role_id = $request->role;
      $article->category_id = $request->category;

      // generation slug
      if (empty($request->slug)) $slug=str_slug($request->title);
        else $slug = $request->slug;

      if ($slug!=$article->slug) {
        if (Article::where('slug', $slug)->count()>0)
          $article->slug = $slug.'-'.\Carbon\Carbon::now()->format('d-m-Y-h-m-s');
        else
         $article->slug = $slug;
      }

      if ($article->save())
        return redirect()->back()->with([
          'result' => 'Статья успешно обновлена',
          'slug' => $article->slug
        ]);
      else
        return redirect()->back()->with('result', 'Возникла ошибка');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */

    public function destroy(Request $request, $id_article)
    {
      // check start page
      $arrayFromStartPageString = explode("/",Settings::get('startPage'));
      if ($arrayFromStartPageString[1]!='article')
        $arrayFromStartPageString[3]=0;

      if ($arrayFromStartPageString[3]==$id_article)
        return redirect()->back()->with(['result'=>'Нельзя удалить главную страницу']); 

      $article = Article::where('id',$id_article)->firstOrFail();
      if (str_contains($request->server('HTTP_REFERER'),'dashboard')) {
        // request from dashboard
        $article->delete();
        return redirect()->back()->with(['result'=>'Статья успешно удалена']);
      } else {
        // request from front
        $category = $article->category->id;
        $article->delete();
        return redirect('/category/id/'.$category)->with(['result'=>'Статья успешно удалена']);
      }
    }
}
