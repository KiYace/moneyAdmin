<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ExpensesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ExpensesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ExpensesCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Expenses::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/expenses');
        CRUD::setEntityNameStrings('расход', 'Расходы');
        
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
          'name' => 'user_name',
          'label' => 'Пользователь',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'category_id',
          'label' => 'Категория',
        ]);
        $this->crud->addColumn([
          'type' => 'number',
          'name' => 'sum',
          'label' => 'Сумма',
        ]);
        $this->crud->addColumn([
          'type' => 'number',
          'name' => 'bill_id',
          'label' => 'Счет',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'shop',
          'label' => 'Магазин',
        ]);
        $this->crud->addColumn([
          'type' => 'select_from_array',
          'options' => ['0' => 'Не важно', '1' => 'Важно', '2' => 'Очень важно'],
          'name' => 'important',
          'label' => 'Важность',
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
        CRUD::setValidation(ExpensesRequest::class);
        

        $this->crud->addField([
          'type' => 'select2',
          'name' => 'user_name',
          'entity' => 'user',
          'attribute' => 'name',
          'label' => 'Пользователь',
        ]);
        $this->crud->addField([
          'type' => 'text',
          'name' => 'category_id',
          'label' => 'Категория',
        ]);
        $this->crud->addField([
          'type' => 'number',
          'name' => 'sum',
          'label' => 'Сумма',
        ]);
        $this->crud->addField([
          'type' => 'select2',
          'name' => 'bill_id',
          'entity' => 'bill',
          'attribute' => 'id',
          'label' => 'Счет',
        ]);
        $this->crud->addField([
          'type' => 'text',
          'name' => 'shop',
          'label' => 'Магазин',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['0' => 'Не важно', '1' => 'Важно', '2' => 'Очень важно'],
          'name' => 'important',
          'label' => 'Важность',
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
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
