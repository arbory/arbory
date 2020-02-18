<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Nodes\LanguageLinkedNode;
use Arbory\Base\Repositories\LanguageLinkedNodeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Waavi\Translation\Models\Language;

/**
 * Class LanguageLinkedNodeRelation.
 */
class LanguageLinkedNodeRelation extends Select
{
    private const FIELD_NAME = 'language_linked_node.';

    /**
     * @var Language
     */
    protected $language;

    /**
     * @var Language
     */
    protected $modelLanguage;

    /**
     * @var LanguageLinkedNodeRepository
     */
    protected $relationRepository;

    /**
     * LanguageLinkedNodeRelation constructor.
     * @param Language $language
     * @param Language $modelLanguage
     */
    public function __construct(Language $language, Language $modelLanguage)
    {
        $this->language = $language;
        $this->modelLanguage = $modelLanguage;

        parent::__construct(self::FIELD_NAME . $this->language->locale);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function beforeModelSave(Request $request): void
    {
    }

    /**
     * @param Request $request
     * @return void
     */
    public function afterModelSave(Request $request): void
    {
        $linkedNodeId = $request->input($this->getNameSpacedName());

        $this->relationRepository()
            ->saveLinkedNode($this->getLink(), $this->getLinkedLanguage(), $linkedNodeId);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        if ($this->value === null) {
            $linkedNode = $this->relationRepository()
                ->findLinkedNode($this->getLink(), $this->getLinkedLanguage());

            $this->value = $linkedNode->node_id ?? '';
        }

        return $this->value;
    }

    /**
     * @return LanguageLinkedNode
     */
    protected function getLink(): LanguageLinkedNode
    {
        return $this->relationRepository()
                ->findOrCreateNodeLink($this->getModel(), $this->getModelLanguage());
    }

    /**
     * @return Language
     */
    protected function getLinkedLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @return Language
     */
    protected function getModelLanguage(): Language
    {
        return $this->modelLanguage;
    }

    /**
     * @return LanguageLinkedNodeRepository
     */
    protected function relationRepository(): LanguageLinkedNodeRepository
    {
        if (! $this->relationRepository) {
            $this->relationRepository = app(LanguageLinkedNodeRepository::class);
        }

        return $this->relationRepository;
    }
}
