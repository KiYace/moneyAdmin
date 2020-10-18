<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ExpensesRepeatRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ExpensesRepeatCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ExpensesRepeatCrudController extends CrudController
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
        CRUD::setModel(\App\Models\ExpensesRepeat::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/expensesrepeat');
        CRUD::setEntityNameStrings('потвор расхода', 'повторы расходов');
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
          'type' => 'number',
          'name' => 'id',
          'label' => 'ID повтора',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'expense_id',
          'label' => 'ID расхода',
        ]);
        $this->crud->addColumn([
          'type' => 'nubmber',
          'name' => 'expense_repeat',
          'label' => 'Режим повтора',
        ]);
        $this->crud->addColumn([
          'type' => 'datetime',
          'name' => 'expense_repeat_date',
          'label' => 'Дата повтора',
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
        CRUD::setValidation(ExpensesRepeatRequest::class);

        $this->crud->addField([
          'type' => 'select2',
          'name' => 'expense_id',
          'entity' => 'expense',
          'attribute' => 'id',
          'label' => 'ИД расхода',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['0' => '', '1' => 'Каждый день', '2' => 'Каждую неделю', '3' => 'Каждый месяц'],
          'name' => 'expense_repeat',
          'label' => 'Режим повтора',
        ]);
        $this->crud->addField([
          'type' => 'datetime',
          'name' => 'expense_repeat_date',
          'label' => 'Дата повтора',
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
