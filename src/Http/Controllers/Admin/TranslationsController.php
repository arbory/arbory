<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Illuminate\View\View;
use Arbory\Base\Html\Html;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Query\Builder;
use Waavi\Translation\Models\Language;
use Illuminate\Database\Query\JoinClause;
use Waavi\Translation\Models\Translation;
use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Arbory\Base\Admin\Widgets\SearchField;
use Waavi\Translation\Cache\CacheRepositoryInterface;
use Arbory\Base\Http\Requests\TranslationStoreRequest;
use Waavi\Translation\Repositories\LanguageRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Waavi\Translation\Repositories\TranslationRepository;

/**
 * Class TranslationsController.
 */
class TranslationsController extends Controller
{
    /**
     * @var TranslationRepository
     */
    protected $translationsRepository;

    /**
     * @var LanguageRepository
     */
    protected $languagesRepository;

    /**
     * @var Request
     */
    protected $request;

    /** @noinspection PhpMissingParentConstructorInspection */

    /**
     * @param TranslationRepository $translationRepository
     * @param LanguageRepository $languagesRepository
     */
    public function __construct(
        TranslationRepository $translationRepository,
        LanguageRepository $languagesRepository
    ) {
        $this->translationsRepository = $translationRepository;
        $this->languagesRepository = $languagesRepository;
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $this->request = $request;

        $languages = $this->languagesRepository->all();

        /** @noinspection PhpUndefinedMethodInspection */
        /* @var $allItems Builder */
        $allItems = Translation::distinct()->select('item', 'group', 'namespace');

        $translationsQuery = \DB::table(\DB::raw('('.$allItems->toSql().') as d1'));

        $translationsQuery->addSelect('d1.*');

        $translationsTableName = (new Translation())->getTable();

        foreach ($languages as $language) {
            $locale = $language->locale;

            $joinAlias = 'l_'.$locale;

            $translationsQuery->addSelect($joinAlias.'.text AS '.$locale.'_text');
            $translationsQuery->addSelect($joinAlias.'.locked AS '.$locale.'_locked');
            $translationsQuery->addSelect($joinAlias.'.unstable AS '.$locale.'_unstable');

            $translationsQuery->leftJoin(
                $translationsTableName.' as l_'.$locale,
                function (JoinClause $join) use ($joinAlias, $locale) {
                    $join
                        ->on($joinAlias.'.group', '=', 'd1.group')
                        ->on($joinAlias.'.item', '=', 'd1.item')
                        ->on($joinAlias.'.locale', '=', \DB::raw('\''.$locale.'\''));
                }
            );
        }

        $searchString = $request->get('search');

        if ($searchString) {
            $translationsQuery->where('d1.group', 'LIKE', '%'.$searchString.'%');
            $translationsQuery->orWhere('d1.namespace', 'LIKE', '%'.$searchString.'%');
            $translationsQuery->orWhere('d1.item', 'LIKE', '%'.$searchString.'%');

            foreach ($languages as $language) {
                $translationsQuery->orWhere('l_'.$language->locale.'.text', 'LIKE', '%'.$searchString.'%');
            }
        }

        $paginatedItems = $this->getPaginatedItems($translationsQuery);

        return view(
            'arbory::controllers.translations.index',
            [
                'header' => Html::header([$this->getIndexBreadcrumbs(), (new SearchField(''))->render()]),
                'languages' => $languages,
                'translations' => $paginatedItems,
                'paginator' => $paginatedItems,
                'search' => $request->get('search'),
                'highlight' => function ($text) use ($searchString) {
                    $format = '<span style="background-color: lime; font-weight:bold">%s</span>';
                    $resultHtml = sprintf($format, htmlentities($searchString));

                    return str_replace($searchString, $resultHtml, htmlentities($text));
                },
            ]
        );
    }

    /**
     * @param Request $request
     * @param string $namespace
     * @param string $group
     * @param string $item
     * @return View
     */
    public function edit(Request $request, $namespace, $group, $item)
    {
        $group = str_replace('.', '/', $group);
        $translationKey = $namespace.'::'.$group.'.'.$item;
        $this->request = $request;

        /* @var $languages Language[] */
        $languages = $this->languagesRepository->all();

        $translations = [];
        foreach ($languages as $language) {
            /** @noinspection PhpUndefinedFieldInspection */
            $locale = $language->locale;

            $translation = $this->translationsRepository->findByCode(
                $locale,
                $namespace,
                $group,
                $item
            );

            if (! $translation) {
                $translation = new Translation([
                    'locale' => $locale,
                    'namespace' => $namespace,
                    'group' => $group,
                    'item' => $item,
                    'text' => $translationKey,
                ]);
                $translation->save();
            }

            $translations[$locale] = $translation;
        }

        return view(
            'arbory::controllers.translations.edit',
            [
                'header' => Html::header([$this->getEditBreadcrumbs($translationKey)]),
                'input' => $request,
                'languages' => $languages,
                'namespace' => $namespace,
                'group' => $group,
                'item' => $item,
                'translations' => $translations,
                'back_to_index_url' => route('admin.translations.index', $this->getContext()),
                'update_url' => route('admin.translations.update', $this->getContext()),
            ]
        );
    }

    /**
     * @param TranslationStoreRequest $request
     * @return RedirectResponse|Redirector
     */
    public function store(TranslationStoreRequest $request)
    {
        $this->request = $request;

        /* @var $languages Language[] */
        $languages = $this->languagesRepository->all();

        /** @var CacheRepositoryInterface $cache */
        $cache = \App::make('translation.cache.repository');

        foreach ($languages as $language) {
            /** @noinspection PhpUndefinedFieldInspection */
            $locale = $language->locale;

            $translation = $this->translationsRepository->findByCode(
                $locale,
                $request->get('namespace'),
                $request->get('group'),
                $request->get('item')
            );

            /* @noinspection PhpUndefinedFieldInspection */
            $this->translationsRepository->updateAndLock(
                $translation->id,
                $request->get('text_'.$locale)
            );

            $cache->flush($locale, $request->get('group'), $request->get('namespace'));
        }

        return redirect(route('admin.translations.index', $this->getContext()));
    }

    /**
     * @return Breadcrumbs
     */
    protected function getIndexBreadcrumbs(): Breadcrumbs
    {
        $module = \Admin::modules()->findModuleByController($this);

        return (new Breadcrumbs)->addItem(
            $module->getConfiguration()->getName(),
            route('admin.translations.index', $this->getContext())
        );
    }

    /**
     * @param string $editTitle
     * @return Breadcrumbs
     */
    protected function getEditBreadcrumbs(string $editTitle): Breadcrumbs
    {
        $breadcrumbs = $this->getIndexBreadcrumbs();
        $breadcrumbs->addItem($editTitle, '');

        return $breadcrumbs;
    }

    /**
     * @param \stdClass $item
     * @param LengthAwarePaginator $paginator
     * @return string
     */
    private function getEditUrl($item, LengthAwarePaginator $paginator)
    {
        return route(
            'admin.translations.edit',
            [
                'namespace' => $item->namespace,
                'group' => str_replace('/', '.', $item->group),
                'item' => $item->item,
                'page' => $paginator->currentPage(),
                'search' => $this->request->get('search'),
            ]
        );
    }

    /**
     * @param Builder $translationsQueryBuilder
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private function getPaginatedItems(Builder $translationsQueryBuilder)
    {
        $paginator = $translationsQueryBuilder->paginate(10000);

        foreach ($paginator->items() as $item) {
            $item->edit_url = $this->getEditUrl($item, $paginator);
        }

        return $paginator;
    }

    /**
     * @return array
     */
    private function getContext()
    {
        return ['page' => $this->request->get('page'), 'search' => $this->request->get('search')];
    }
}
