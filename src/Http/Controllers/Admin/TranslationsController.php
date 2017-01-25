<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Breadcrumbs;
use CubeSystems\Leaf\Http\Requests\TranslationStoreRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Waavi\Translation\Models\Language;
use Waavi\Translation\Models\Translation;
use Waavi\Translation\Repositories\LanguageRepository;
use Waavi\Translation\Repositories\TranslationRepository;

/**
 * Class TranslationsController
 * @package CubeSystems\Leaf\Http\Controllers\Admin
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

    /** @noinspection PhpMissingParentConstructorInspection */
    /**
     * @param TranslationRepository $translationRepository
     * @param LanguageRepository $languagesRepository
     */
    public function __construct( TranslationRepository $translationRepository, LanguageRepository $languagesRepository )
    {
        $this->translationsRepository = $translationRepository;
        $this->languagesRepository = $languagesRepository;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $languages = $this->languagesRepository->all();

        /** @noinspection PhpUndefinedMethodInspection */
        /* @var $allItems Builder */
        $allItems = Translation::distinct()->select( 'item', 'group', 'namespace' );

        $translationsQuery = \DB::table( \DB::raw( '(' . $allItems->toSql() . ') as d1' ) );

        $translationsQuery->addSelect( 'd1.*' );

        $translationsTableName = ( new Translation() )->getTable();

        foreach( $languages as $language )
        {
            $locale = $language->locale;

            $joinAlias = 'l_' . $locale;

            $translationsQuery->addSelect( $joinAlias . '.text AS ' . $locale . '_text' );
            $translationsQuery->addSelect( $joinAlias . '.locked AS ' . $locale . '_locked' );
            $translationsQuery->addSelect( $joinAlias . '.unstable AS ' . $locale . '_unstable' );

            $translationsQuery->leftJoin(
                $translationsTableName . ' as l_' . $locale,
                function ( JoinClause $join ) use ( $joinAlias, $locale )
                {
                    $join
                        ->on( $joinAlias . '.group', '=', 'd1.group' )
                        ->on( $joinAlias . '.item', '=', 'd1.item' )
                        ->on( $joinAlias . '.locale', '=', \DB::raw( '\'' . $locale . '\'' ) );
                }
            );
        }

        $breadcrumbs = $this->getBreadcrumbs();

        $paginatedItems = $this->getPaginatedItems( $translationsQuery );

        return view(
            'leaf::controllers.translations.index',
            [
                'breadcrumbs' => $breadcrumbs->get(),
                'languages' => $languages,
                'translations' => $paginatedItems,
                'paginator' => $paginatedItems
            ]
        );
    }

    /**
     * @param Request $request
     * @param string $namespace
     * @param string $group
     * @param string $item
     * @param string $page
     * @return View
     */
    public function edit( Request $request, $namespace, $group, $item, $page )
    {
        $breadcrumbs = $this->getBreadcrumbs();

        /* @var $languages Language[] */
        $languages = $this->languagesRepository->all();

        $translations = [];
        foreach( $languages as $language )
        {
            /** @noinspection PhpUndefinedFieldInspection */
            $locale = $language->locale;

            $translation = $this->translationsRepository->findByCode(
                $locale,
                $namespace,
                $group,
                $item
            );

            if( !$translation )
            {
                $translation = new Translation( [
                    'locale' => $locale,
                    'namespace' => $namespace,
                    'group' => $group,
                    'item' => $item,
                    'text' => $namespace . '::' . $group . '.' . $item
                ] );
                $translation->save();
            }

            $translations[$locale] = $translation;
        }

        return view(
            'leaf::controllers.translations.edit',
            [
                'input' => $request,
                'breadcrumbs' => $breadcrumbs->get(),
                'languages' => $languages,
                'namespace' => $namespace,
                'group' => $group,
                'item' => $item,
                'page' => $page,
                'translations' => $translations
            ]
        );
    }

    public function store( TranslationStoreRequest $request )
    {
        /* @var $languages Language[] */
        $languages = $this->languagesRepository->all();

        foreach( $languages as $language )
        {
            /** @noinspection PhpUndefinedFieldInspection */
            $locale = $language->locale;

            $translation = $this->translationsRepository->findByCode(
                $locale,
                $request->get( 'namespace' ),
                $request->get( 'group' ),
                $request->get( 'item' )
            );

            /** @noinspection PhpUndefinedFieldInspection */
            $this->translationsRepository->updateAndLock(
                $translation->id,
                $request->get( 'text_' . $locale )
            );
        }

        return redirect( route( 'admin.translations.index' ) . '?page=' . $request->get( 'page' ) );
    }



    /**
     * @param \stdClass $item
     * @param LengthAwarePaginator $paginator
     * @return string
     */
    private function getEditUrl( $item, LengthAwarePaginator $paginator )
    {
        return route(
            'admin.translations.edit',
            [
                'namespace' => $item->namespace,
                'group' => $item->group,
                'item' => $item->item,
                'page' => $paginator->currentPage()
            ] );
    }

    /**
     * @param Builder $translationsQueryBuilder
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private function getPaginatedItems( Builder $translationsQueryBuilder )
    {
        $paginator = $translationsQueryBuilder->paginate( 10 );

        foreach( $paginator->items() as $item )
        {
            $item->edit_url = $this->getEditUrl( $item, $paginator );
        }

        return $paginator;
    }

    /**
     * @return Breadcrumbs
     */
    private function getBreadcrumbs()
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add(
            trans( 'leaf.breadcrumbs.home' ),
            route( 'admin.dashboard' )
        );
        $breadcrumbs->add(
            trans( 'leaf.translations.index' ),
            route( 'admin.translations.index' )
        );

        return $breadcrumbs;
    }
}
