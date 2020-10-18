<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GoalReminderRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class GoalReminderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class GoalReminderCrudController extends CrudController
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
        CRUD::setModel(\App\Models\GoalReminder::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/goalreminder');
        CRUD::setEntityNameStrings('напоминание цели', 'напоминания целей');
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
          'name' => 'goal_id',
          'label' => 'ID цели',
        ]);
        $this->crud->addColumn([
          'type' => 'nubmber',
          'name' => 'goal_reminder',
          'label' => 'Режим напоминания',
        ]);
        $this->crud->addColumn([
          'type' => 'datetime',
          'name' => 'goal_reminder_date',
          'label' => 'Дата завершения цели',
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
        CRUD::setValidation(GoalReminderRequest::class);

        $this->crud->addField([
          'type' => 'select2',
          'name' => 'goal_id',
          'entity' => 'goal',
          'attribute' => 'id',
          'label' => 'ИД цели',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['0' => '', '1' => 'Каждый день', '2' => 'Каждую неделю', '3' => 'Каждый месяц'],
          'name' => 'goal_reminder',
          'label' => 'Режим напоминания',
        ]);
        $this->crud->addField([
          'type' => 'datetime',
          'name' => 'goal_reminder_date',
          'label' => 'Дата завершения цели',
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
