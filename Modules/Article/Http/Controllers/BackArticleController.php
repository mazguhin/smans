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

     protected $frontTemplate = '';

     public function __construct()
     {
       $this->frontTemplate = Settings::getFrontTemplate();
     }

    public function index()
    {
    }

    public function show()
    {
      return view('template::back.'.$this->frontTemplate.'.article.show',[
        'articles' => Article::orderBy('created_at', 'desc')->paginate(10)
      ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
      return view('template::back.'.$this->frontTemplate.'.article.create',[
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
      $this->validate($request, [
        'title' => 'required|max:255',
        'description' => 'max:255',
        'editor' => 'required',
        'permission' => 'required|max:255',
        'category' => 'required|max:255',
      ],[
        'title.required' => 'Заполните заголовок',
        'editor.required' => 'Заполните основной текст',
        'category.required' => 'Выберите категорию',
        'permission.required' => 'Назначьте доступ',
        'max' => 'Макс. кол-во символов: 255 (Заголовок, Описание)'
      ]);

      $article = new Article;
      $article->title = $request->title;
      $article->description = $request->description;
      $article->body = $request->editor;
      $article->permission = $request->permission;
      $article->category_id = $request->category;

      // generation slug
      $slug = str_slug($request->title);
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
      return view('template::back.'.$this->frontTemplate.'.article.edit',[
        'categories' => \Modules\Category\Entities\Category::all(),
        'article' => Article::where('id',$id_article)->firstOrFail(),
        'roles' => \Modules\Dashboard\Entities\Role::all()
      ]);
    }

    public function editBySlug($slug_article)
    {
      return view('template::back.'.$this->frontTemplate.'.article.edit',[
        'categories' => \Modules\Category\Entities\Category::all(),
        'article' => Article::where('slug',$slug_article)->firstOrFail(),
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
      $article = Article::where('id',$id_article)->firstOrFail();
      $article->title = $request->title;
      $article->description = $request->description;
      $article->body = $request->editor;
      $article->permission = $request->permission;
      $article->category_id = $request->category;

      // generation slug
      $slug = str_slug($request->title);
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

    //  TODO: do do
    public function destroy($id_article)
    {
      Article::where('id',$id_article)->firstOrFail()->delete();
      return redirect()->back()->with(['result'=>'Статья успешно удалена']);
    }
}
