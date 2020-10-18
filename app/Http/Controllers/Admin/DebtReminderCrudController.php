<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DebtReminderRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DebtReminderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DebtReminderCrudController extends CrudController
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
        CRUD::setModel(\App\Models\DebtReminder::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/debtreminder');
        CRUD::setEntityNameStrings('напоминание долга', 'напоминания долгов');
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
          'label' => 'ID напоминания',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'debt_id',
          'label' => 'ID долга',
        ]);
        $this->crud->addColumn([
            'type' => 'text',
            'name' => 'debt_type',
            'label' => 'Тип долга',
          ]);
        $this->crud->addColumn([
          'type' => 'nubmber',
          'name' => 'debt_reminder',
          'label' => 'Режим напоминания',
        ]);
        $this->crud->addColumn([
          'type' => 'datetime',
          'name' => 'debt_reminder_date',
          'label' => 'Дата завершения долга',
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
        CRUD::setValidation(DebtReminderRequest::class);

        $this->crud->addField([
          'type' => 'select2',
          'name' => 'debt_id',
          'entity' => 'debt',
          'attribute' => 'id',
          'label' => 'ИД долга',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['1' => 'Заем', '2' => 'Долг'],
          'name' => 'debt_type',
          'label' => 'Тип долга',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['0' => '', '1' => 'Каждый день', '2' => 'Каждую неделю', '3' => 'Каждый месяц'],
          'name' => 'debt_reminder',
          'label' => 'Режим напоминания',
        ]);
        $this->crud->addField([
          'type' => 'datetime',
          'name' => 'debt_reminder_date',
          'label' => 'Дата завершения долга',
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
