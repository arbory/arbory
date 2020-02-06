<?php

namespace Arbory\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Waavi\Translation\Repositories\LanguageRepository;

/**
 * Class TranslationStoreRequest.
 */
class TranslationStoreRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        $rules = [
            'namespace' => 'required',
            'group' => 'required',
            'item' => 'required',
            'page' => 'required',
        ];

        /* @var $languageRepository LanguageRepository */
        $languageRepository = app(LanguageRepository::class);
        foreach ($languageRepository->all() as $language) {
            $rules['text_'.$language->locale] = 'required|';
        }

        return $rules;
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
