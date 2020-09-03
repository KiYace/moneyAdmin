<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SourceRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SourceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SourceCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Source::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/source');
        CRUD::setEntityNameStrings('источник', 'Источники');
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
          'name' => 'source_name',
          'label' => 'Название источника',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'source_ico',
          'label' => 'Иконка источника',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'color',
          'label' => 'Цвет',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'user_id',
          'label' => 'ИД пользователя',
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
        CRUD::setValidation(SourceRequest::class);

        $this->crud->addField([
          'type' => 'text',
          'name' => 'source_name',
          'label' => 'Название источника',
        ]);
        $this->crud->addField([
          'type' => 'text',
          'name' => 'source_ico',
          'label' => 'Иконка источника',
        ]);
        $this->crud->addField([
          'type' => 'color_picker',
          'name' => 'color',
          'label' => 'Цвет',
        ]);
        $this->crud->addField([
          'type' => 'select2',
          'name' => 'user_id',
          'entity' => 'user',
          'attribute' => 'id',
          'label' => 'ИД пользователя',
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
