<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $currentDate = date('Y-m-d H:i:s', time());

      /*  /// PROMOTIONS PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех промоакций',
        ], [
            'name'       => 'Просмотр всех промоакций',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'PromotionController',
            'method'     => 'index',
            'group'      => 'Промокоды',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр промоакции',
        ], [
            'name'       => 'Просмотр промоакции',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'PromotionController',
            'method'     => 'show',
            'group'      => 'Промокоды',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание промоакции',
        ], [
            'name'       => 'Создание промоакции',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'PromotionController',
            'method'     => 'store',
            'group'      => 'Промокоды',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение промоакции',
        ], [
            'name'       => 'Изменение промоакции',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'PromotionController',
            'method'     => 'update',
            'group'      => 'Промокоды',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
                \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
                    'name' => 'Получить колличество персонала в переиод месяца',
                ], [
                    'name'       => 'Получить колличество персонала в переиод месяца',
                    'namespace'  => 'App/Http/Controllers/CRM/System',
                    'controller' => 'WorkScheduleController',
                    'method'     => 'getCountStaffByDate',
                    'group'      => 'Графики работ',
                    'status'     => 1,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ]);
        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Получить колличество персонала в переиод месяца',
        ], [
            'name'       => 'Получить колличество персонала в переиод месяца',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'WorkScheduleController',
            'method'     => 'getCountStaffByDate',
            'group'      => 'Графики работ',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);


        /// USER PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех пользователей',
        ], [
            'name'       => 'Просмотр пользователей',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'UserController',
            'method'     => 'index',
            'group'      => 'Пользователи',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Редактирование пользователя',
        ], [
            'name'       => 'Просмотр пользователя',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'UserController',
            'method'     => 'show',
            'group'      => 'Пользователи',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание пользователя',
        ], [
            'name'       => 'Создание пользователя',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'UserController',
            'method'     => 'store',
            'group'      => 'Пользователи',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение пользователя',
        ], [
            'name'       => 'Изменение пользователя',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'UserController',
            'method'     => 'update',
            'group'      => 'Пользователи',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление пользователя',
        ], [
            'name'       => 'Удаление пользователя',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'UserController',
            'method'     => 'destroy',
            'group'      => 'Пользователи',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);


        ///  Clients

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех клиентов',
        ], [
            'name'       => 'Просмотр клиентов',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'ClientController',
            'method'     => 'index',
            'group'      => 'Клиенты',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Редактирование клиента',
        ], [
            'name'       => 'Просмотр клиента',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'ClientController',
            'method'     => 'show',
            'group'      => 'Клиенты',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание клиента',
        ], [
            'name'       => 'Создание клиента',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'ClientController',
            'method'     => 'store',
            'group'      => 'Клиенты',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение клиента',
        ], [
            'name'       => 'Изменение клиента',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'ClientController',
            'method'     => 'update',
            'group'      => 'Клиенты',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление клиента',
        ], [
            'name'       => 'Удаление клиента',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'ClientController',
            'method'     => 'destroy',
            'group'      => 'Клиенты',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Добавить клиента в черный список',
        ], [
            'name'       => 'Добавить клиента в черный список',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'ClientController',
            'method'     => 'addToBlackList',
            'group'      => 'Клиенты',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);


        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Исключить клиента из черного списка',
        ], [
            'name'       => 'Исключить клиента из черного списка',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'ClientController',
            'method'     => 'removeFromBlackList',
            'group'      => 'Клиенты',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Добавить адрес клиента',
        ], [
            'name'       => 'Добавить адрес клиента',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'ClientController',
            'method'     => 'addClientAddress',
            'group'      => 'Клиенты',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Получить актуальную коризину клиента в CRM',
        ], [
            'name'       => 'Получить актуальную коризину клиента в CRM',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'getClientCrmActiveBasket',
            'group'      => 'Клиенты',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        // PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр прав доступа',
        ], [
            'name'       => 'Просмотр прав доступа',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'PermissionController',
            'method'     => 'index',
            'group'      => 'Права доступа',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        /// CATEGORY PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех категорий',
        ], [
            'name'       => 'Просмотр категорий',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'CategoryController',
            'method'     => 'index',
            'group'      => 'Категории',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр категории',
        ], [
            'name'       => 'Просмотр категории',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'CategoryController',
            'method'     => 'show',
            'group'      => 'Категории',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание категории',
        ], [
            'name'       => 'Создание категории',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'CategoryController',
            'method'     => 'store',
            'group'      => 'Категории',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение категории',
        ], [
            'name'       => 'Изменение категории',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'CategoryController',
            'method'     => 'update',
            'group'      => 'Категории',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление категории',
        ], [
            'name'       => 'Удаление категории',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'CategoryController',
            'method'     => 'destroy',
            'group'      => 'Категории',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        /// FILIAL PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех филиалов',
        ], [
            'name'       => 'Просмотр филиалов',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'FilialController',
            'method'     => 'index',
            'group'      => 'Филиалы',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр филиала',
        ], [
            'name'       => 'Просмотр филиала',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'FilialController',
            'method'     => 'show',
            'group'      => 'Филиалы',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание филиала',
        ], [
            'name'       => 'Создание филиала',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'FilialController',
            'method'     => 'store',
            'group'      => 'Филиалы',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение филиала',
        ], [
            'name'       => 'Изменение филиала',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'FilialController',
            'method'     => 'update',
            'group'      => 'Филиалы',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление филиала',
        ], [
            'name'       => 'Удаление филиала',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'FilialController',
            'method'     => 'destroy',
            'group'      => 'Филиалы',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        /// FILIAL DEFAULT SETTINGS PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр все настройки филиала',
        ], [
            'name'       => 'Просмотр все настройки филиала',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'SettingController',
            'method'     => 'index',
            'group'      => 'Настройки филиалов',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр настройки филиала',
        ], [
            'name'       => 'Просмотр настройки филиала',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'SettingController',
            'method'     => 'show',
            'group'      => 'Настройки филиалов',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание настройки филиала',
        ], [
            'name'       => 'Создание настройки филиала',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'SettingController',
            'method'     => 'store',
            'group'      => 'Настройки филиалов',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение настройки филиала',
        ], [
            'name'       => 'Изменение настройки филиала',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'SettingController',
            'method'     => 'update',
            'group'      => 'Настройки филиалов',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление настройки филиала',
        ], [
            'name'       => 'Удаление филиала',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'SettingController',
            'method'     => 'destroy',
            'group'      => 'Настройки филиалов',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        /// POLYGONs PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех полигонов',
        ], [
            'name'       => 'Просмотр полигонов',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'PolygonController',
            'method'     => 'index',
            'group'      => 'Полигоны',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр полигона',
        ], [
            'name'       => 'Просмотр полигона',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'PolygonController',
            'method'     => 'show',
            'group'      => 'Полигоны',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание полигона',
        ], [
            'name'       => 'Создание полигона',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'PolygonController',
            'method'     => 'store',
            'group'      => 'Полигоны',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение полигона',
        ], [
            'name'       => 'Изменение полигона',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'PolygonController',
            'method'     => 'update',
            'group'      => 'Полигоны',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление полигона',
        ], [
            'name'       => 'Удаление полигона',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'PolygonController',
            'method'     => 'destroy',
            'group'      => 'Полигоны',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);


        /// Collection PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр  коллекций',
        ], [
            'name'       => 'Просмотр коллекций',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'CollectionController',
            'method'     => 'index',
            'group'      => 'Коллекции',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр коллекции',
        ], [
            'name'       => 'Просмотр коллекции',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'CollectionController',
            'method'     => 'show',
            'group'      => 'Коллекции',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание коллекции',
        ], [
            'name'       => 'Создание коллекции',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'CollectionController',
            'method'     => 'store',
            'group'      => 'Коллекции',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение коллекции',
        ], [
            'name'       => 'Изменение коллекции',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'CollectionController',
            'method'     => 'update',
            'group'      => 'Коллекции',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление коллекции',
        ], [
            'name'       => 'Удаление коллекции',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'CollectionController',
            'method'     => 'destroy',
            'group'      => 'Коллекции',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        /// TECHNICAL CARDS PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех технический карт',
        ], [
            'name'       => 'Просмотр всех технический карт',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'TechnicalCardController',
            'method'     => 'index',
            'group'      => 'Тех.карты',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр технической карты',
        ], [
            'name'       => 'Просмотр технической карты',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'TechnicalCardController',
            'method'     => 'show',
            'group'      => 'Тех.карты',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание технической карты',
        ], [
            'name'       => 'Создание технической карты',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'TechnicalCardController',
            'method'     => 'store',
            'group'      => 'Тех.карты',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение технической карты',
        ], [
            'name'       => 'Изменение технической карты',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'TechnicalCardController',
            'method'     => 'update',
            'group'      => 'Тех.карты',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление технической карты',
        ], [
            'name'       => 'Удаление технической карты',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'TechnicalCardController',
            'method'     => 'destroy',
            'group'      => 'Тех.карты',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        /// MENU ITEMS PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех эллементов меню',
        ], [
            'name'       => 'Просмотр эллементов меню',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'MenuItemController',
            'method'     => 'index',
            'group'      => 'Эллементы меню',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр эллемента меню',
        ], [
            'name'       => 'Просмотр эллемента меню',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'MenuItemController',
            'method'     => 'show',
            'group'      => 'Эллементы меню',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание эллемента меню',
        ], [
            'name'       => 'Создание эллемента меню',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'MenuItemController',
            'method'     => 'store',
            'group'      => 'Эллементы меню',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение эллемента меню',
        ], [
            'name'       => 'Изменение эллемента меню',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'MenuItemController',
            'method'     => 'update',
            'group'      => 'Эллементы меню',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление эллемента меню',
        ], [
            'name'       => 'Удаление эллемента меню',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'MenuItemController',
            'method'     => 'destroy',
            'group'      => 'Эллементы меню',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Сохранение модификаций эллемента меню',
        ], [
            'name'       => 'Сохранение модификаций эллемента меню',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'MenuItemController',
            'method'     => 'saveModifications',
            'group'      => 'Эллементы меню',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        /// MODIFICATION PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех модификаций',
        ], [
            'name'       => 'Просмотр модификаций',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'ModificationController',
            'method'     => 'index',
            'group'      => 'Модификации',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр модификации',
        ], [
            'name'       => 'Просмотр модификации',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'ModificationController',
            'method'     => 'show',
            'group'      => 'Модификации',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание модификации',
        ], [
            'name'       => 'Создание модификации',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'ModificationController',
            'method'     => 'store',
            'group'      => 'Модификации',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение модификации',
        ], [
            'name'       => 'Изменение модификации',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'ModificationController',
            'method'     => 'update',
            'group'      => 'Модификации',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление модификации',
        ], [
            'name'       => 'Удаление модификации',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'ModificationController',
            'method'     => 'destroy',
            'group'      => 'Модификации',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        /// Basket PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех корзин',
        ], [
            'name'       => 'Просмотр всех корзин',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'index',
            'group'      => 'Корзины',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр корзины',
        ], [
            'name'       => 'Просмотр корзины',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'show',
            'group'      => 'Корзины',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание корзины',
        ], [
            'name'       => 'Создание корзины',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'store',
            'group'      => 'Корзины',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение корзины',
        ], [
            'name'       => 'Изменение корзины',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'update',
            'group'      => 'Корзины',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление корзины',
        ], [
            'name'       => 'Удаление корзины',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'destroy',
            'group'      => 'Корзины',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);


        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменить адрес доставки корзины',
        ], [
            'name'       => 'Изменить адрес доставки корзины',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'changeCourier',
            'group'      => 'Корзины',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);


        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменить адрес самовывоза корзины',
        ], [
            'name'       => 'Изменить адрес самовывоза корзины',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'changePickUp',
            'group'      => 'Корзины',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменить тип оплаты и сумму размена корзины',
        ], [
            'name'       => 'Изменить тип оплаты и сумму размена корзины',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'changePaymentType',
            'group'      => 'Корзины',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменить или добавить промокод корзины',
        ], [
            'name'       => 'Изменить или добавить промокод корзины',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'addPromoCode',
            'group'      => 'Корзины',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Добавить товар в корзину',
        ], [
            'name'       => 'Добавить товар в корзину',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'addBasketItem',
            'group'      => 'Корзины',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Обновить товар в корзине',
        ], [
            'name'       => 'Обновить товар в корзине',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'updateBasketItem',
            'group'      => 'Корзины',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удалить товар из корзины',
        ], [
            'name'       => 'Удалить товар из корзины',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'deleteItem',
            'group'      => 'Корзины',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удалить все товары из корзины',
        ], [
            'name'       => 'Удалить все товары из корзины',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'deleteItems',
            'group'      => 'Корзины',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        /// Basket PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр статусов заказов',
        ], [
            'name'       => 'Просмотр статусов заказов',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'OrderController',
            'method'     => 'getStatuses',
            'group'      => 'Заказы',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех заказов',
        ], [
            'name'       => 'Просмотр всех заказов',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'OrderController',
            'method'     => 'index',
            'group'      => 'Заказы',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр заказа',
        ], [
            'name'       => 'Просмотр заказа',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'OrderController',
            'method'     => 'show',
            'group'      => 'Заказы',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание заказа',
        ], [
            'name'       => 'Создание заказа',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'OrderController',
            'method'     => 'store',
            'group'      => 'Заказы',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение заказа',
        ], [
            'name'       => 'Изменение заказа',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'OrderController',
            'method'     => 'update',
            'group'      => 'Заказы',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление заказа',
        ], [
            'name'       => 'Удаление заказа',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'OrderController',
            'method'     => 'destroy',
            'group'      => 'Заказы',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);


        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Сменить статус заказа на следующий',
        ], [
            'name'       => 'Сменить статус заказа на следующий',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'OrderController',
            'method'     => 'changeOnNextStatus',
            'group'      => 'Заказы',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);


        /// Roles PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех ролей',
        ], [
            'name'       => 'Просмотр всех ролей',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'RoleController',
            'method'     => 'index',
            'group'      => 'Роли',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр роли',
        ], [
            'name'       => 'Просмотр роли',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'RoleController',
            'method'     => 'show',
            'group'      => 'Роли',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание роли',
        ], [
            'name'       => 'Создание роли',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'RoleController',
            'method'     => 'store',
            'group'      => 'Роли',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение роли',
        ], [
            'name'       => 'Изменение роли',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'RoleController',
            'method'     => 'update',
            'group'      => 'Роли',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление роли',
        ], [
            'name'       => 'Удаление роли',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'RoleController',
            'method'     => 'destroy',
            'group'      => 'Роли',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        /// CLIENT ADDRESS PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех клиентских адресов',
        ], [
            'name'       => 'Просмотр всех клиентских адресов',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'ClientAddressController',
            'method'     => 'index',
            'group'      => 'Адреса клиентов',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр адреса клиента',
        ], [
            'name'       => 'Просмотр адреса клиента',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'ClientAddressController',
            'method'     => 'show',
            'group'      => 'Адреса клиентов',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание адреса клиента',
        ], [
            'name'       => 'Создание адреса клиента',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'ClientAddressController',
            'method'     => 'store',
            'group'      => 'Адреса клиентов',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение адреса клиента',
        ], [
            'name'       => 'Изменение адреса клиента',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'ClientAddressController',
            'method'     => 'update',
            'group'      => 'Адреса клиентов',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление адреса клиента',
        ], [
            'name'       => 'Удаление адреса клиента',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'ClientAddressController',
            'method'     => 'destroy',
            'group'      => 'Адреса клиентов',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        /// TRANSACTIONS PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех транзакций',
        ], [
            'name'       => 'Просмотр всех транзакций',
            'namespace'  => 'App/Http/Controllers/CRM/Courier',
            'controller' => 'CourierTransactionController',
            'method'     => 'index',
            'group'      => 'Транзакции',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр транзакции',
        ], [
            'name'       => 'Просмотр транзакции',
            'namespace'  => 'App/Http/Controllers/CRM/Courier',
            'controller' => 'CourierTransactionController',
            'method'     => 'show',
            'group'      => 'Транзакции',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание транзакции',
        ], [
            'name'       => 'Создание транзакции',
            'namespace'  => 'App/Http/Controllers/CRM/Courier',
            'controller' => 'CourierTransactionController',
            'method'     => 'store',
            'group'      => 'Транзакции',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение транзакции',
        ], [
            'name'       => 'Изменение транзакции',
            'namespace'  => 'App/Http/Controllers/CRM/Courier',
            'controller' => 'CourierTransactionController',
            'method'     => 'update',
            'group'      => 'Транзакции',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление транзакции',
        ], [
            'name'       => 'Удаление транзакции',
            'namespace'  => 'App/Http/Controllers/CRM/Courier',
            'controller' => 'CourierTransactionController',
            'method'     => 'destroy',
            'group'      => 'Транзакции',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Перевести транзакцию в режим ожидания курьера',
        ], [
            'name'       => 'Перевести транзакцию в режим ожидания курьера',
            'namespace'  => 'App/Http/Controllers/CRM/Courier',
            'controller' => 'CourierTransactionController',
            'method'     => 'waitTransaction',
            'group'      => 'Транзакции',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Потвердить и завершить транзакцию',
        ], [
            'name'       => 'Потвердить и завершить транзакцию',
            'namespace'  => 'App/Http/Controllers/CRM/Courier',
            'controller' => 'CourierTransactionController',
            'method'     => 'completeTransaction',
            'group'      => 'Транзакции',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Отменить транзакцию',
        ], [
            'name'       => 'Отменить транзакцию',
            'namespace'  => 'App/Http/Controllers/CRM/Courier',
            'controller' => 'CourierTransactionController',
            'method'     => 'cancelTransaction',
            'group'      => 'Транзакции',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);


        /// WORK SCHEDULE PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех графиков работ',
        ], [
            'name'       => 'Просмотр всех графиков работ',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'WorkScheduleController',
            'method'     => 'index',
            'group'      => 'Графики работ',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр графика работы',
        ], [
            'name'       => 'Просмотр графика работы',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'WorkScheduleController',
            'method'     => 'show',
            'group'      => 'Графики работ',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание графика работы',
        ], [
            'name'       => 'Создание графика работы',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'WorkScheduleController',
            'method'     => 'store',
            'group'      => 'Графики работ',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение графика работы',
        ], [
            'name'       => 'Изменение графика работы',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'WorkScheduleController',
            'method'     => 'update',
            'group'      => 'Графики работ',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление графика работы',
        ], [
            'name'       => 'Удаление графика работы',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'WorkScheduleController',
            'method'     => 'destroy',
            'group'      => 'Графики работ',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);



        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Получить схему для заполнения',
        ], [
            'name'       => 'Получить схему для заполнения',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'WorkScheduleController',
            'method'     => 'getScheme',
            'group'      => 'Графики работ',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);


        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Заполнить рабочий день по схеме',
        ], [
            'name'       => 'Заполнить рабочий день по схеме',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'WorkScheduleController',
            'method'     => 'setDay',
            'group'      => 'Графики работ',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);


        /// WORK SPACE PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех рабочих мест',
        ], [
            'name'       => 'Просмотр всех рабочих мест',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'WorkSpaceController',
            'method'     => 'index',
            'group'      => 'Рабочие места',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр рабочего места',
        ], [
            'name'       => 'Просмотр рабочего места',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'WorkSpaceController',
            'method'     => 'show',
            'group'      => 'Рабочие места',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание рабочего места',
        ], [
            'name'       => 'Создание рабочего места',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'WorkSpaceController',
            'method'     => 'store',
            'group'      => 'Рабочие места',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение рабочего места',
        ], [
            'name'       => 'Изменение рабочего места',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'WorkSpaceController',
            'method'     => 'update',
            'group'      => 'Рабочие места',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление рабочего места',
        ], [
            'name'       => 'Удаление рабочего места',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'WorkSpaceController',
            'method'     => 'destroy',
            'group'      => 'Рабочие места',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);


       /// PROMO CODE PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех промокодов',
        ], [
            'name'       => 'Просмотр всех промокодов',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'PromoCodeController',
            'method'     => 'index',
            'group'      => 'Промокоды',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр промокода',
        ], [
            'name'       => 'Просмотр промокода',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'PromoCodeController',
            'method'     => 'show',
            'group'      => 'Промокоды',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание промокода',
        ], [
            'name'       => 'Создание промокода',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'PromoCodeController',
            'method'     => 'store',
            'group'      => 'Промокоды',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение промокода',
        ], [
            'name'       => 'Изменение промокода',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'PromoCodeController',
            'method'     => 'update',
            'group'      => 'Промокоды',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление промокода',
        ], [
            'name'       => 'Удаление промокода',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'PromoCodeController',
            'method'     => 'destroy',
            'group'      => 'Промокоды',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
 /// NEWS PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех новостей',
        ], [
            'name'       => 'Просмотр всех новостей',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'NewsController',
            'method'     => 'index',
            'group'      => 'Новости',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр новости',
        ], [
            'name'       => 'Просмотр новости',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'NewsController',
            'method'     => 'show',
            'group'      => 'Новости',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание новости',
        ], [
            'name'       => 'Создание новости',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'NewsController',
            'method'     => 'store',
            'group'      => 'Новости',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение новости',
        ], [
            'name'       => 'Изменение новости',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'NewsController',
            'method'     => 'update',
            'group'      => 'Новости',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление новости',
        ], [
            'name'       => 'Удаление новости',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'NewsController',
            'method'     => 'destroy',
            'group'      => 'Новости',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);





        /// NEWS PERMISSION

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр всех слайдеров',
        ], [
            'name'       => 'Просмотр всех слайдеров',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'SliderController',
            'method'     => 'index',
            'group'      => 'Слайдеры',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Просмотр слайдера',
        ], [
            'name'       => 'Просмотр слайдера',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'SliderController',
            'method'     => 'show',
            'group'      => 'Слайдеры',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Создание слайдера',
        ], [
            'name'       => 'Создание слайдера',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'SliderController',
            'method'     => 'store',
            'group'      => 'Слайдеры',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменение слайдера',
        ], [
            'name'       => 'Изменение слайдера',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'SliderController',
            'method'     => 'update',
            'group'      => 'Слайдеры',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Удаление слайдера',
        ], [
            'name'       => 'Удаление слайдера',
            'namespace'  => 'App/Http/Controllers/CRM/Store',
            'controller' => 'SliderController',
            'method'     => 'destroy',
            'group'      => 'Слайдеры',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Получить баланс пользователя',
        ], [
            'name'       => 'Получить баланс пользователя',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'UserController',
            'method'     => 'getBalance',
            'group'      => 'Пользователи',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

      */
/*
        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Краткая информация за день',
        ], [
            'name'       => 'Краткая информация за день',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'FilialCashBoxController',
            'method'     => 'getShortInfo',
            'group'      => 'Администрирование',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Баланс и информация',
        ], [
            'name'       => 'Баланс и информация',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'FilialCashBoxController',
            'method'     => 'getBalanceAndInformationByOpenCashBox',
            'group'      => 'Администрирование',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Открыть день',
        ], [
            'name'       => 'Открыть день',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'FilialCashBoxController',
            'method'     => 'openCashBox',
            'group'      => 'Администрирование',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Закрыть день',
        ], [
            'name'       => 'Закрыть день',
            'namespace'  => 'App/Http/Controllers/CRM/System',
            'controller' => 'FilialCashBoxController',
            'method'     => 'closeCashBox',
            'group'      => 'Администрирование',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);*/


   /*     \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Отправить ссылку на оплату клиенту',
        ], [
            'name'       => 'Отправить ссылку на оплату клиенту',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'OrderController',
            'method'     => 'sendLinkOnPayment',
            'group'      => 'Заказы',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);*/

/*        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Потвердить заказ',
        ], [
            'name'       => 'Потвердить заказ',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'OrderController',
            'method'     => 'confirmOrder',
            'group'      => 'Заказы',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Отменить заказ',
        ], [
            'name'       => 'Отменить заказ',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'OrderController',
            'method'     => 'cancelOrder',
            'group'      => 'Заказы',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);*/


        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменить доставку на курьеров яндекса',
        ], [
            'name'       => 'Изменить доставку на курьеров яндекса',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'changeOnYandexDelivery',
            'group'      => 'Корзины',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert([
            'name' => 'Изменить доставку на курьеров деливери клаб',
        ], [
            'name'       => 'Изменить доставку на курьеров деливери клаб',
            'namespace'  => 'App/Http/Controllers/CRM/Order',
            'controller' => 'BasketController',
            'method'     => 'changeOnDeliveryClub',
            'group'      => 'Корзины',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);


    }
}
