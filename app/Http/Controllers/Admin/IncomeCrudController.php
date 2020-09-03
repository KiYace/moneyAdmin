<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\IncomeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class IncomeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class IncomeCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Income::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/income');
        CRUD::setEntityNameStrings('доход', 'Доходы');
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
          'label' => 'ID',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'user_id',
          'label' => 'ИД пользователя',
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
          'name' => 'source',
          'label' => 'Источник дохода',
        ]);
        $this->crud->addColumn([
          'type' => 'select_from_array',
          'options' => ['0' => 'Не важно', '1' => 'Важно', '2' => 'Очень важно'],
          'name' => 'important',
          'label' => 'Важность',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'tags_id',
          'label' => 'Теги',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'notice',
          'label' => 'Примечание',
        ]);
        $this->crud->addColumn([
          'type' => 'select_from_array',
          'options' => ['0' => '', '1' => 'Каждый день', '2' => 'Каждую неделю', '3' => 'Каждый месяц'],
          'name' => 'repeat',
          'label' => 'Повтор',
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
        CRUD::setValidation(IncomeRequest::class);

        $this->crud->addField([
          'type' => 'select2',
          'name' => 'user_id',
          'entity' => 'user',
          'attribute' => 'id',
          'label' => 'ИД пользователя',
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
          'type' => 'select2',
          'name' => 'source',
          'entity' => 'source',
          'attribute' => 'source_name',
          'label' => 'Источник',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['0' => 'Не важно', '1' => 'Важно', '2' => 'Очень важно'],
          'name' => 'important',
          'label' => 'Важность',
        ]);
        $this->crud->addField([
          'type' => 'relationship',
          'name' => 'tags_id',
          'entity' => 'tag',
          'attribute' => 'tag_name',
          'label' => 'Теги',
        ]);
        $this->crud->addField([
          'type' => 'text',
          'name' => 'notice',
          'label' => 'Примечание',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['0' => '', '1' => 'Каждый день', '2' => 'Каждую неделю', '3' => 'Каждый месяц'],
          'name' => 'repeat',
          'label' => 'Повтор',
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
