<?php

namespace Amplify\Widget\Controllers;

use Amplify\System\Abstracts\BackpackCustomCrudController;
use Amplify\System\Backend\Models\Tag;
use Amplify\System\Cms\Models\Page;
use Amplify\Widget\Models\Widget;
use Amplify\Widget\Requests\WidgetRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class WidgetCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class WidgetCrudController extends BackpackCustomCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Widget::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/widget');
        CRUD::setEntityNameStrings('widget', 'widgets');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     *
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addFilter(
            [
                'name' => 'placement',
                'type' => 'dropdown',
                'label' => 'Position',
            ],
            function () {
                return Widget::WIDGET_PLACEMENT_TYPE;
            },
            function ($value) {
                // if the filter is active
                $this->crud->addClause('where', 'placement', '=', $value);
            }
        );

        CRUD::column('id')->type('number')->thousands_sep('');
        CRUD::column('name');
        CRUD::column('placement')
            ->type('select_from_array')
            ->options(Widget::WIDGET_PLACEMENT_TYPE);
        CRUD::column('enabled')->type('boolean');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     *
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(WidgetRequest::class);

        CRUD::field('name');
        CRUD::field('model');
        CRUD::field('data');
        CRUD::field('placement');
        CRUD::field('page_types');
        CRUD::field('tags')->type('select_multiple');
        CRUD::field('description');

        $this->data['allPlacements'] = $this->getAllPlacements();
        $this->data['allPageTypes'] = $this->getAllPageTypes();
        $this->data['allTags'] = $this->getAllTags();

        $this->crud->setCreateView('crud::pages.widget.create');
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     *
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->data['widgetData'] = $this->crud->getCurrentEntry();

        $this->crud->setUpdateView('crud::pages.widget.create');

        $this->setupCreateOperation();
    }

    protected function setupShowOperation(): void
    {
        CRUD::addColumn([
            'name' => 'name',
            'type' => 'text',
        ]);
        CRUD::addColumn([
            'name' => 'model',
            'type' => 'text',
        ]);
        CRUD::addColumn([
            'name' => 'data',
            'type' => 'text',
        ]);
        CRUD::addColumn([
            'name' => 'placement',
            'type' => 'select_from_array',
            'options' => Widget::WIDGET_PLACEMENT_TYPE,
        ]);
        CRUD::addColumn([
            'name' => 'page_types',
            'label' => 'Pages Types',
            'type' => 'custom_html',
            'value' => function ($entity) {
                $view = '<div>';
                $page_types = $entity->page_types;
                if (! empty($page_types) && count(json_decode($page_types)) > 0) {
                    $page_types = json_decode($page_types);
                    foreach ($page_types as $value) {
                        $view .= '<span style="font-size: 15px;" class="badge badge-secondary">'
                                 .(Page::PAGE_TYPES[$value] ?? 'N/A').'</span>';
                    }
                } else {
                    $view .= '<span>-</span>';
                }

                $view .= '</div>';

                return $view;
            },
        ]);

        //        CRUD::addColumn([
        //            'name' => 'tags',
        //            'type' => 'select_multiple',
        //            'model' => 'tags'
        //        ]);

        CRUD::addColumn([
            'name' => 'blade',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'description',
            'label' => 'Description',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'template_id',
            'label' => 'Template',
            'type' => 'select',
            'entity' => 'template',
        ]);
        CRUD::addColumn([
            'name' => 'code',
            'label' => 'Code',
            'type' => 'custom_html',
            'value' => function ($entity) {
                return ! empty($entity->code)
                    ? "<textarea disabled='true'  class='form-control' rows='10' style='overflow:scroll; background-color: #0c1021; color: #fff;font-family: Monospace, monospace; border-color: transparent; box-shadow: none'>"
                      .$entity->code
                      .'</textarea>'
                    : '-';
            },
        ]);
    }

    private function getAllPlacements(): array
    {
        $placements = Widget::WIDGET_PLACEMENT_TYPE;
        $all_placements = [];
        foreach ($placements as $key => $placement) {
            $all_placements[] = [
                'id' => $key,
                'label' => $placement,
            ];
        }

        return $all_placements;
    }

    private function getAllPageTypes(): array
    {
        $page_types = Page::PAGE_TYPES;
        $all_page_types = [];
        foreach ($page_types as $key => $page_type) {
            $all_page_types[] = [
                'id' => $key,
                'label' => $page_type,
            ];
        }

        return [
            [
                'id' => 'all',
                'label' => 'All',
                'children' => $all_page_types,
            ],
        ];
    }

    private function getAllTags(): array
    {
        $tags = Tag::all();
        $all_tags = [];
        foreach ($tags as $key => $tag) {
            $all_tags[] = [
                'id' => $tag->id,
                'name' => $tag->name,
            ];
        }

        return $all_tags;
    }
}
