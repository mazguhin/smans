<?php

namespace Modules\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Entities\MenuItem;
use Settings;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BackMenuItemController extends Controller
{
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
     'url' => 'required|max:255',
     'role' => 'required|max:255',
     'target' => 'required|max:255',
     'activated' => 'required|max:255',
   ],[
     'required' => 'Заполните все обязательные поля (*)',
     'max' => 'Макс. кол-во символов: 255'
   ]));
 }

 public function show($id_menu)
 {
   return view('template::back.'.$this->backTemplate.'.menu.item.show',[
     'items' => Menu::where('id',$id_menu)->firstOrFail()->menuAllItems,
     'id_menu' => $id_menu,
   ]);
 }

 public function create($id_menu)
 {
   return view('template::back.'.$this->backTemplate.'.menu.item.create',[
     'articles' => \Modules\Article\Entities\Article::all(),
     'categories' => \Modules\Category\Entities\Category::all(),
     'roles' => \Modules\Dashboard\Entities\Role::all(),
     'id_menu' => $id_menu,
   ]);
 }

 public function store(Request $request, $id_menu)
 {
   //validation
   $this->validateForm($request);

   $itemMenu = new MenuItem;
   $itemMenu->title = $request->title;
   $itemMenu->description = $request->description;
   $itemMenu->role_id = $request->role;
   $itemMenu->activated = $request->activated;
   $itemMenu->url = $request->url;
   $itemMenu->target = $request->target;

   if (Menu::where('id',$id_menu)->firstOrFail()->menuAllItems()->save($itemMenu))
     return redirect('/dashboard/menu/item/id/'.$id_menu)->with([
       'result' => 'Пункт меню успешно добавлен'
     ]);
   else
     return redirect()->back()->with('result', 'Возникла ошибка');
 }

 public function editById($id_item)
 {
   $item = MenuItem::where('id',$id_item)->firstOrFail();
   $arrayItemUrl = explode("/",$item->url);

   return view('template::back.'.$this->backTemplate.'.menu.item.edit',[
     'item' => $item,
     'arrayItemUrl' => $arrayItemUrl,
     'articles' => \Modules\Article\Entities\Article::all(),
     'categories' => \Modules\Category\Entities\Category::all(),
     'roles' => \Modules\Dashboard\Entities\Role::all(),
   ]);
 }

 public function update(Request $request, $id_item)
 {
   // validation
   $this->validateForm($request);

   $itemMenu = MenuItem::where('id',$id_item)->firstOrFail();
   $itemMenu->title = $request->title;
   $itemMenu->description = $request->description;
   $itemMenu->role_id = $request->role;
   $itemMenu->activated = $request->activated;
   $itemMenu->url = $request->url;
   $itemMenu->target = $request->target;

   if ($itemMenu->save())
     return redirect()->back()->with([
       'result' => 'Меню успешно обновлено'
     ]);
   else
     return redirect()->back()->with('result', 'Возникла ошибка');
 }

 public function destroy(Request $request, $id_item)
 {
     if (MenuItem::where('id',$id_item)->firstOrFail()->delete())
      return redirect()->back()->with('result', 'Пункт меню успешно удален');
     else
      return redirect()->back()->with('result', 'Возникла ошибка');
 }
}