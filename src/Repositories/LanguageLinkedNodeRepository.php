<?php

namespace Arbory\Base\Repositories;

use Arbory\Base\Nodes\Node;
use Arbory\Base\Nodes\LanguageLinkedNode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Waavi\Translation\Models\Language;

/**
 * Class LanguageLinkedNodeRepository.
 */
class LanguageLinkedNodeRepository extends AbstractModelsRepository
{
    /**
     * @var string
     */
    protected $modelClass = LanguageLinkedNode::class;

    /**
     * @param Node|Model $node
     * @param Language $language
     * @return LanguageLinkedNode
     */
    public function findOrCreateNodeLink(Node $node, Language $language): LanguageLinkedNode
    {
        return $this->newQuery()->firstOrCreate(
            [
                'node_id' => $node->getKey()
            ],
            [
                'language_id' => $language->getKey(),
                'link' => $this->getNextAvailableLink(),
            ]
        );
    }

    /**
     * @param LanguageLinkedNode $languageLinkedNode
     * @param Language $language
     * @return LanguageLinkedNode|null
     */
    public function findLinkedNode(LanguageLinkedNode $languageLinkedNode, Language $language): ?LanguageLinkedNode
    {
        return $this->newQuery()
            ->where('link', $languageLinkedNode->link)
            ->where('language_id', $language->getKey())
            ->first();
    }

    /**
     * @param LanguageLinkedNode $languageLinkedNode
     * @param Language $language
     * @param string|null $nodeId
     * @return void
     */
    public function saveLinkedNode(LanguageLinkedNode $languageLinkedNode, Language $language, ?string $nodeId): void
    {
        $this->newQuery()->updateOrCreate(
            [
                'link' => $languageLinkedNode->link,
                'language_id' => $language->getKey()
            ],
            [
                'node_id' => $nodeId
            ]
        );

        $this->newQuery()
            ->where('node_id', $nodeId)
            ->where('link', '!=', $languageLinkedNode->link)
            ->delete();
    }

    /**
     * @return int
     */
    public function getNextAvailableLink(): int
    {
        return $this->newQuery()->max('link') + 1;

    }
}
