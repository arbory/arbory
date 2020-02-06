<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Files\ArboryFile;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Arbory\Base\Admin\Traits\Crudify;
use Arbory\Base\Admin\Form\Fields\Text;
use Arbory\Base\Admin\Settings\Setting;
use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Services\SettingFactory;
use Arbory\Base\Services\SettingRegistry;
use Arbory\Base\Admin\Form\Fields\Translatable;
use Arbory\Base\Admin\Settings\SettingDefinition;
use Arbory\Base\Admin\Settings\SettingTranslation;

class SettingsController extends Controller
{
    use Crudify;

    /**
     * @var string
     */
    protected $resource = Setting::class;

    /**
     * @var SettingRegistry
     */
    protected $settingRegistry;

    /**
     * @param SettingRegistry $settingRegistry
     */
    public function __construct(
        SettingRegistry $settingRegistry
    ) {
        $this->settingRegistry = $settingRegistry;
        $this->settingRegistry->importFromDatabase();
    }

    /**
     * @param Form $form
     * @return Form
     */
    protected function form(Form $form)
    {
        $definition = $this->settingRegistry->find($form->getModel()->getKey());

        $form->setFields(function (Form\FieldSet $fields) use ($definition) {
            $fields->add($this->getField($fields, $definition));
            $fields->hidden('type')->setValue($definition->getType());
        });

        return $form;
    }

    /**
     * @param Grid $grid
     * @return Grid
     */
    public function grid(Grid $grid)
    {
        $grid->setColumns(function (Grid $grid) {
            $grid->column('name');
            $grid->column('value')->display(function ($value, $column, Setting $setting) {
                $container = Html::span();
                $definition = $setting->getDefinition();

                if ($definition->isFile()) {
                    /** @var ArboryFile $file */
                    $file = $setting->file;

                    if (! $file) {
                        return;
                    }

                    if ($definition->isImage()) {
                        return $container->append(Html::image()->addAttributes([
                            'src' => $file->getUrl(),
                            'width' => 64,
                            'height' => 64,
                        ]));
                    }

                    return $container->append(
                        Html::link($file->getOriginalName())->addAttributes([
                            'href' => $file->getUrl(),
                        ])
                    );
                }

                return $container->append($value);
            });
        });

        return $grid
            ->tools([])
            ->items($this->getSettings())
            ->paginate(false);
    }

    /**
     * @param Form\FieldSet $fields
     * @param SettingDefinition $definition
     * @return Form\Fields\AbstractField|Translatable
     */
    protected function getField(Form\FieldSet $fields, SettingDefinition $definition)
    {
        /**
         * @var Form\Fields\AbstractField
         * @var Form\Fields\AbstractField $innerField
         */
        $type = $definition->getType();

        if ($type === Translatable::class) {
            $inner = array_get($definition->getConfigEntry(), 'value');
            $innerType = $inner['type'] ?? Text::class;
            $innerField = new $innerType('value');

            $field = new Translatable($innerField);
            $field->setFieldSet($fields);

            if (! $field->getValue() || $field->getValue()->isEmpty()) {
                $localized = array_get($inner, 'value', []);
                $fieldValue = new Collection();

                foreach ($localized as $locale => $value) {
                    $fieldValue->push(new SettingTranslation([
                        'locale' => $locale,
                        'value' => $value,
                    ]));
                }

                $field->setValue($fieldValue);
            }
        } else {
            $field = new $type('value');
            $field->setValue($definition->getValue());
        }

        return $field;
    }

    /**
     * @return array
     */
    protected function getSettings()
    {
        /** @var SettingFactory $factory */
        $factory = \App::make(SettingFactory::class);
        $result = [];

        foreach ($this->settingRegistry->getSettings()->keys() as $key) {
            $result[$key] = $factory->build($key);
        }

        return $result;
    }

    /**
     * @param \Arbory\Base\Admin\Tools\ToolboxMenu $tools
     */
    protected function toolbox(ToolboxMenu $tools)
    {
        $model = $tools->model();

        $tools->add('edit', $this->url('edit', $model->getKey()));
    }
}
