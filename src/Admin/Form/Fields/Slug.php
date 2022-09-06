<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Nodes\Node;
use Arbory\Base\Repositories\NodesRepository;
use Arbory\Base\Admin\Form\Fields\Renderer\SlugFieldRenderer;

/**
 * Class Slug.
 */
class Slug extends Text
{
    protected string $rendererClass = SlugFieldRenderer::class;

    /**
     * @param  string  $name
     * @param  string  $fromFieldName
     * @param  string  $apiUrl
     */
    public function __construct($name, protected $fromFieldName, protected $apiUrl)
    {
        parent::__construct($name);
    }

    /**
     * @return string
     */
    public function getLinkHref()
    {
        $urlToSlug = $this->getUriToSlug();

        if ($urlToSlug) {
            $urlToSlug .= '/';
        }

        return url($urlToSlug.$this->getValue());
    }

    /**
     * @return string
     */
    public function getPreviewLinkHref()
    {
        $urlToSlug = $this->getUriToSlug();

        if ($urlToSlug) {
            $urlToSlug = '/' . trim($urlToSlug, '/');
        }

        $slugHashed = 'preview-' . sha1(config('arbory.preview.slug_salt') . $urlToSlug . '/' . $this->getValue());

        return url($slugHashed);
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->getModel()->getUri();
    }

    /**
     * @return string
     */
    protected function getUriToExistingModel()
    {
        $uriParts = explode('/', $this->getUri());

        array_pop($uriParts);

        return implode('/', $uriParts);
    }

    /**
     * @return string
     */
    protected function getUriToNewModel()
    {
        /**
         * @var Node
         */
        $repository = new NodesRepository;
        $parentNode = $repository->find($this->getParentId());

        return $parentNode ? $parentNode->getUri() : (string) null;
    }

    /**
     * @return string
     */
    public function getUriToSlug()
    {
        if (! $this->hasUriToSlug()) {
            return false;
        }

        return $this->getUriToExistingModel() ?: $this->getUriToNewModel();
    }

    public function hasUriToSlug(): bool
    {
        return $this->getModel() instanceof Node;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        $model = $this->getModel();

        if ($model instanceof Node) {
            return $model->getParentId();
        }

        return request('parent_id');
    }

    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    public function getFromFieldName(): string
    {
        return $this->fromFieldName;
    }
}
