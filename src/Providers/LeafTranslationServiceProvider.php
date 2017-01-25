<?php namespace CubeSystems\Leaf\Providers;

use Illuminate\Support\NamespacedItemResolver;
use Illuminate\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;
use Waavi\Translation\Repositories\TranslationRepository;
use Waavi\Translation\TranslationServiceProvider;

/**
 * Class LeafTranslationServiceProvider
 * @package CubeSystems\Leaf\Providers
 */
class LeafTranslationServiceProvider extends TranslationServiceProvider
{
    /**
     *
     */
    public function register()
    {
        parent::register();

//        $this->app->extend( 'translator', function ( Translator $translator )
//        {
//            return new class( $translator ) extends NamespacedItemResolver implements TranslatorInterface
//            {
//                /** @var Translator */
//                private $translator;
//
//                public function __construct( Translator $translator )
//                {
//                    $this->translator = $translator;
//                }
//
//                private function addTranslation( $id, $locale )
//                {
//                    list( $namespace, $group, $item ) = $this->parseKey( $id );
//
//                    /* @var $translationRepository TranslationRepository */
//                    $translationRepository = app( TranslationRepository::class );
//
//                    $translationRepository->create( [
//                        'locale' => $locale ?: $this->translator->getLocale(),
//                        'namespace' => $namespace ?: '*',
//                        'group' => $group,
//                        'item' => $item,
//                        'text' => $item,
//                        'unstable' => true
//                    ] );
//                }
//
//                public function trans( $id, array $parameters = array(), $domain = null, $locale = null )
//                {
//                    $result = $this->translator->trans( $id, $parameters, $domain, $locale );
//
//                    if( $result === $id )
//                    {
//                        $this->addTranslation( $id, $locale );
//                    }
//
//                    return $result;
//                }
//
//                public function transChoice( $id, $number, array $parameters = array(), $domain = null, $locale = null )
//                {
//                    $result = $this->translator->transChoice( $id, $number, $parameters, $domain, $locale );
//
//                    if( $result === $id )
//                    {
//                        $this->addTranslation( $id, $locale );
//                    }
//
//                    return $result;
//                }
//
//                public function setLocale( $locale )
//                {
//                    $this->translator->setLocale( $locale );
//                }
//
//                public function getLocale()
//                {
//                    return $this->translator->getLocale();
//                }
//
//                public function addNamespace( $namespace, $hint )
//                {
//                    $this->translator->addNamespace( $namespace, $hint );
//                }
//            };
//        } );
    }
}
