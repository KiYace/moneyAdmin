<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BillRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BillCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BillCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Bill::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/bill');
        CRUD::setEntityNameStrings('счет', 'Счета');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'type' => 'text',
            'name' => 'bill_name',
            'label' => 'Название счета',
          ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'user_id',
          'label' => 'ИД пользователя',
        ]);
        $this->crud->addColumn([
          'type' => 'number',
          'name' => 'balance',
          'label' => 'Баланс',
        ]);
        $this->crud->addColumn([
          'type' => 'select_from_array',
          'options' => ['RUB' => 'Рубли', 'USD' => 'Доллары', 'EUR' => 'Евро'],
          'name' => 'currency',
          'label' => 'Валюта',
        ]);
        $this->crud->addColumn([
          'type' => 'number',
          'name' => 'limit',
          'label' => 'Ограничения в месяц',
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
        CRUD::setValidation(BillRequest::class);
        $this->crud->addField([
          'type' => 'text',
          'name' => 'bill_name',
          'label' => 'Название счета',
        ]);
        $this->crud->addField([
          'type' => 'select2',
          'name' => 'user_id',
          'entity' => 'user',
          'attribute' => 'id',
          'label' => 'ИД пользователя',
        ]);
        $this->crud->addField([
          'type' => 'number',
          'name' => 'balance',
          'label' => 'Баланс',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['RUB' => 'RUB', 'USD' => 'USD', 'EUR' => 'EUR'],
          'name' => 'currency',
          'label' => 'Валюта',
        ]);
        $this->crud->addField([
          'type' => 'number',
          'name' => 'limit',
          'label' => 'Ограничения в месяц',
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
