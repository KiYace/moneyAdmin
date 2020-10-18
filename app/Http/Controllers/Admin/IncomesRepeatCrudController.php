<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\IncomesRepeatRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class IncomesRepeatCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class IncomesRepeatCrudController extends CrudController
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
        CRUD::setModel(\App\Models\IncomesRepeat::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/incomesrepeat');
        CRUD::setEntityNameStrings('повтор дохода', 'повторы доходов');
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
          'name' => 'income_id',
          'label' => 'ID дохода',
        ]);
        $this->crud->addColumn([
          'type' => 'nubmber',
          'name' => 'income_repeat',
          'label' => 'Режим повтора',
        ]);
        $this->crud->addColumn([
          'type' => 'datetime',
          'name' => 'income_repeat_date',
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
        CRUD::setValidation(IncomesRepeatRequest::class);

        $this->crud->addField([
          'type' => 'select2',
          'name' => 'income_id',
          'entity' => 'income',
          'attribute' => 'id',
          'label' => 'ИД дохода',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['0' => '', '1' => 'Каждый день', '2' => 'Каждую неделю', '3' => 'Каждый месяц'],
          'name' => 'income_repeat',
          'label' => 'Режим повтора',
        ]);
        $this->crud->addField([
          'type' => 'datetime',
          'name' => 'income_repeat_date',
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
