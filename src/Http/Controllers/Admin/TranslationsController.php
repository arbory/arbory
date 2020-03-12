<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Admin\Filter\FilterItem;
use Arbory\Base\Admin\Filter\FilterManager;
use Arbory\Base\Admin\Filter\Parameters\Transformers\DefaultValueTransformer;
use Arbory\Base\Admin\Filter\Parameters\Transformers\QueryStringTransformer;
use Arbory\Base\Admin\Filter\Types\MultiCheckboxFilterType;
use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Admin\Traits\Crudify;
use Arbory\Base\Admin\Traits\HasHighlightedText;
use Arbory\Base\Admin\Traits\InlineEdit;
use Arbory\Base\Translations\Admin\Grid\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Waavi\Translation\Cache\SimpleRepository;
use Waavi\Translation\Models\Language;
use Waavi\Translation\Models\Translation;
use Waavi\Translation\Repositories\LanguageRepository;
use Waavi\Translation\Repositories\TranslationRepository;
use Waavi\Translation\Cache\CacheRepositoryInterface;

/**
 * Class TranslationsController.
 */
class TranslationsController extends Controller
{
    use Crudify;
    use InlineEdit;
    use HasHighlightedText;

    protected const TRANSLATION_FIELD = 'text';
    protected const KEY_FIELDS = [
        'namespace',
        'group',
        'item',
    ];

    /**
     * @var string
     */
    protected $resource = Translation::class;

    /**
     * @var LanguageRepository
     */
    protected $languageRepository;

    /**
     * @var TranslationRepository
     */
    protected $translationRepository;

    /**
     * @var SimpleRepository
     */
    protected $translationCache;

    /**
     * TranslationsController constructor.
     * @param LanguageRepository $languageRepository
     * @param TranslationRepository $translationRepository
     */
    public function __construct(
        LanguageRepository $languageRepository,
        TranslationRepository $translationRepository
    ) {
        $this->languageRepository = $languageRepository;
        $this->translationRepository = $translationRepository;
        $this->translationCache = app('translation.cache.repository');
    }

    /**
     * @param Grid $grid
     * @return Grid
     */
    public function grid(Grid $grid)
    {
        $search = request()->get('search');

        $this->buildResourceQuery($grid->getFilter()->getQuery());
        $this->buildFilterManager($grid->getFilterManager());
        $languageFilter = $this->buildLanguageFilter($grid->getFilterManager());

        $grid->setColumns(function (Grid $grid) use ($search, $languageFilter) {
            $grid->column('group', trans('arbory::translations.group'))
                ->display($this->highlightedTextDisplay($search))
                ->setCustomSearchQuery(function (Builder $query, string $string) use ($grid) {
                    $this->buildSearchQuery($grid->getFilter()->getQuery(), $string);
                })
                ->addFilter(MultiCheckboxFilterType::class, ['options' => $this->getGroupFilterOptions()]);

            $grid->column('item', trans('arbory::translations.item'))
                ->display($this->highlightedTextDisplay($search))
                ->searchable(false);

            $languageIds = $grid->getFilterManager()
                ->getParameters()
                ->getFromFilter($languageFilter);

            foreach ($this->getLanguages($languageIds) as $language) {
                $grid->column($this->fieldName($language), $this->fieldLabel($language))
                    ->display($this->highlightedTextDisplay($search))
                    ->setCustomSearchQuery(function (Builder $query) use ($language) {
                        $query->orWhere('locale', $language->locale);
                    })
                    ->inlineEditable();
            }
        });

        $grid->tools(['search', 'filter']);

        return $grid;
    }

    /**
     * @param ToolboxMenu $tools
     */
    protected function toolbox(ToolboxMenu $tools)
    {
        $model = $tools->model();

        $tools->add('edit', $this->url('edit', $model->getKey()));
    }

    /**
     * @param Form $form
     * @return Form
     */
    protected function form(Form $form)
    {
        $translation = $form->getModel();

        $form->title($translation->code);
        $form->setFields(function (FieldSet $fields) use ($translation) {
            foreach ($this->getLanguages() as $language) {
                $fields->text($this->fieldName($language))
                    ->setLabel($this->fieldLabel($language))
                    ->setDefaultValue($translation->code)
                    ->rules('required');
            }
        });

        $form->addEventListener('update.before', function (Request $request) use ($form) {
            $this->beforeUpdate($request, $form);
        });

        return $form;
    }

    /**
     * @param Form $form
     * @return Form
     */
    protected function inlineForm(Form $form): Form
    {
        $form->addEventListener('update.before', function (Request $request) use ($form) {
            $this->beforeUpdate($request, $form);
        });

        return $form;
    }

    /**
     * @param FieldSet $fields
     * @return FieldSet
     */
    protected function inlineFormFields(FieldSet $fields): FieldSet
    {
        foreach ($this->getLanguages() as $language) {
            $fields->text($this->fieldName($language))
                ->rules('required');
        }

        return $fields;
    }

    /**
     * @param Request $request
     * @param Form $form
     */
    protected function beforeUpdate(Request $request, Form $form): void
    {
        $translation = $form->getModel();
        $resource = $request->get($form->getNamespace(), []);

        foreach ($this->getLanguages() as $language) {
            $fieldName = $this->fieldName($language);
            unset($translation->$fieldName);

            if (!Arr::has($resource, $fieldName)) {
                continue;
            }

            $langTranslation = $this->findOrCreate($language, $translation);
            $this->translationRepository->updateAndLock(
                $langTranslation->getKey(),
                Arr::get($resource, $fieldName)
            );

            $this->translationCache->flush($language->locale, $translation->group, $translation->namespace);
        }
    }

    /**
     * @param mixed $resourceId
     * @return Model
     */
    protected function findOrNew($resourceId): Model
    {
        $resource = $this->resource();

        $query = $resource->newQuery();
        $this->buildResourceQuery($query);
        $this->buildLanguageQuery($query);
        $query->having($resource->getKeyName(), $resourceId);


        return $query->first() ?? $resource->setAttribute($resource->getKeyName(), $resourceId);
    }

    /**
     * @param FilterManager $filterManager
     */
    protected function buildFilterManager(FilterManager $filterManager): void
    {
        $filterManager
            ->addTransformer(QueryStringTransformer::class)
            ->addTransformer(new DefaultValueTransformer($filterManager));
    }

    /**
     * @param FilterManager $filterManager
     * @return FilterItem
     */
    protected function buildLanguageFilter(FilterManager $filterManager): FilterItem
    {
        $options = $this->getLanguageFilterOptions();
        return $filterManager
            ->addFilter(
                'language',
                trans('arbory::translations.language'),
                MultiCheckboxFilterType::class,
                ['options' => $options]
            )
            ->setExecutor(function (FilterItem $filterItem, Builder $query) {
                $this->buildLanguageQuery($query, $filterItem->getType()->getValue());
            })
            ->setDefaultValue(array_keys($options));
    }

    /**
     * @param Builder|QueryBuilder $query
     */
    protected function buildResourceQuery(Builder $query)
    {
        $query->selectRaw('min(`id`) as `id`')
            ->addSelect(self::KEY_FIELDS)
            ->groupBy(self::KEY_FIELDS);
    }

    /**
     * @param Builder|QueryBuilder $query
     * @param string $search
     */
    protected function buildSearchQuery(Builder $query, string $search)
    {
        $searchBy = "%$search%";

        foreach(self::KEY_FIELDS as $field) {
            $query->orHaving($field, 'like', $searchBy);
        }

        $field = self::TRANSLATION_FIELD;
        $query->orHavingRaw("group_concat(`$field`) like ?", [$searchBy]);
    }

    /**
     * @param Builder $query
     * @param array|null $languageIds
     */
    protected function buildLanguageQuery(Builder $query, ?array $languageIds = [])
    {
        foreach($this->getLanguages($languageIds) as $language) {
            $locale = $language->locale;
            $field = $this->fieldName($language);

            $query->addSelect(DB::raw("group_concat(if(`locale` = '$locale', `text`, null)) as $field"));
        }
    }

    /**
     * @return array
     */
    protected function getLanguageFilterOptions(): array
    {
        return $this->getLanguages()->pluck('locale', 'id')->toArray();
    }

    /**
     * @return array
     */
    protected function getGroupFilterOptions(): array
    {
        return $this->translationRepository->getModel()
            ->groupBy('group')
            ->pluck('group', 'group')
            ->toArray();
    }

    /**
     * @param array|null $languageIds
     * @return Language[]|Collection
     */
    protected function getLanguages(?array $languageIds = []): Collection
    {
        $languages = $this->languageRepository->all();

        if (!empty($languageIds)) {
            return $languages->whereIn('id', $languageIds);
        }

        return $languages;
    }

    /**
     * @param Language $language
     * @return string
     */
    protected function fieldName(Language $language): string
    {
        return sprintf('%s_%s', $language->locale, self::TRANSLATION_FIELD);
    }

    /**
     * @param Language $language
     * @return string
     */
    protected function fieldLabel(Language $language): string
    {
        return trans('arbory::translations.text', ['locale' => $language->locale]);
    }

    /**
     * @param Language $language
     * @param Translation $translation
     * @return Translation
     */
    protected function findOrCreate(Language $language, Translation $translation): Translation
    {
        $fields = ['locale' => $language->locale];
        foreach(self::KEY_FIELDS as $field) {
            $fields[$field] = $translation->$field;
        }

        return $this->translationRepository->getModel()
            ->firstOrCreate(
                $fields,
                [self::TRANSLATION_FIELD => $translation->code]
            );
    }
}
