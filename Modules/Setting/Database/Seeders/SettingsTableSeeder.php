<?php

namespace Modules\Setting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Model::unguard();

      //$faker = \Faker\Factory::create('Modules\Category\Entities\Category');

      DB::table('settings')->insert([
        [
          'name' => 'frontTemplate',
          'value' => 'amy',
          'title' => 'Шаблон сайта',
          'description' => 'Данная опция отвечает за выбор шаблона отображения контента на сайте',
          'created_at' => \Carbon\Carbon::now(),
          'updated_at' => \Carbon\Carbon::now(),
        ],
        [
          'name' => 'backTemplate',
          'value' => 'amy',
          'title' => 'Шаблон панели управления',
          'description' => 'Данная опция отвечает за выбор шаблона отображения контента в панели управления',
          'created_at' => \Carbon\Carbon::now(),
          'updated_at' => \Carbon\Carbon::now(),
        ],
        [
          'name' => 'startPage',
          'value' => '/category/id/1',
          'title' => 'Главная страница',
          'description' => 'Данная опция отвечает за выбор главной страницы, которая будет отображаться при входе на сайт',
          'created_at' => \Carbon\Carbon::now(),
          'updated_at' => \Carbon\Carbon::now(),
        ],
        [
          'name' => 'projectName',
          'value' => 'Smans',
          'title' => 'Название проекта',
          'description' => 'Отображается в главном меню и ведет на главную страницу. Макс. кол-во символов: 20',
          'created_at' => \Carbon\Carbon::now(),
          'updated_at' => \Carbon\Carbon::now(),
        ]
      ]);

    }
}
