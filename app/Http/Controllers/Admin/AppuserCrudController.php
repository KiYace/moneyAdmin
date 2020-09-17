<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AppuserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AppuserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AppuserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Appuser::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/appuser');
        CRUD::setEntityNameStrings('клиента', 'Клиенты');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */

        $this->crud->addColumn([
          'type' => 'number',
          'name' => 'id',
          'label' => 'ID',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'name',
          'label' => 'Ник',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'sec_name',
          'label' => 'Имя',
        ]);
        $this->crud->addColumn([
          'type' => 'email',
          'name' => 'email',
          'label' => 'Электронная почта',
        ]);
        $this->crud->addColumn([
          'type' => 'number',
          'name' => 'salary',
          'label' => 'Запрлата',
        ]);
        $this->crud->addColumn([
          'type' => 'number',
          'name' => 'salary',
          'label' => 'Запрлата'
        ]);
        $this->crud->addColumn([
          'type' => 'number',
          'name' => 'income',
          'label' => 'Доход',
        ]);
        $this->crud->addColumn([
          'type' => 'number',
          'name' => 'expenses',
          'label' => 'Расход',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'token',
          'label' => 'Токен авторизации',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'push_token',
          'label' => 'Токен для отправки PUSH',
        ]);
        $this->crud->addColumn([
          'type' => 'check',
          'name' => 'push_enabled',
          'label' => 'PUSH уведомления',
        ]);
        $this->crud->addColumn([
          'type' => 'datetime',
          'name' => 'created_at',
          'label' => 'Создан',
        ]);
        $this->crud->addColumn([
          'type' => 'datetime', 
          'name' => 'updated_at',
          'label' => 'Обновлен',
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AppuserRequest::class);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */

        $this->crud->addField([
          'type' => 'text',
          'name' => 'name',
          'label' => 'Ник',
        ]);
        $this->crud->addField([
          'type' => 'text',
          'name' => 'sec_name',
          'label' => 'Имя',
        ]);
        $this->crud->addField([
          'type' => 'email',
          'name' => 'email',
          'label' => 'Электронная почта',
        ]);
        $this->crud->addField([
          'type' => 'number',
          'name' => 'salary',
          'label' => 'Запрлата',
        ]);
        $this->crud->addField([
          'type' => 'number',
          'name' => 'salary',
          'label' => 'Запрлата'
        ]);
        $this->crud->addField([
          'type' => 'number',
          'name' => 'income',
          'label' => 'Доход',
        ]);
        $this->crud->addField([
          'type' => 'number',
          'name' => 'expenses',
          'label' => 'Расход',
        ]);
        $this->crud->addField([
          'type' => 'text',
          'name' => 'token',
          'label' => 'Токен авторизации',
        ]);
        $this->crud->addField([
          'type' => 'text',
          'name' => 'push_token',
          'label' => 'Токен для отправки PUSH',
        ]);
        // Убрать перед публикацией
        $this->crud->addField([
          'type' => 'text',
          'name' => 'password',
          'label' => 'Пароль',
        ]);
        $this->crud->addField([
          'type' => 'checkbox',
          'name' => 'push_enabled',
          'label' => 'PUSH уведомления',
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
