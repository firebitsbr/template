<?php

namespace Amethyst\Http\Controllers\Admin;

use Amethyst\Core\Http\Controllers\RestManagerController;
use Amethyst\Core\Http\Controllers\Traits as RestTraits;
use Amethyst\Managers\DataBuilderManager;
use Amethyst\Managers\TemplateManager;
use Illuminate\Http\Request;

/**
 * @method \Amethyst\Managers\TemplateManager getManager()
 */
class TemplatesController extends RestManagerController
{
    use RestTraits\RestIndexTrait;
    use RestTraits\RestShowTrait;
    use RestTraits\RestCreateTrait;
    use RestTraits\RestUpdateTrait;
    use RestTraits\RestRemoveTrait;

    /**
     * The class of the manager.
     *
     * @var string
     */
    public $class = TemplateManager::class;

    /**
     * Render raw template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render(Request $request)
    {
        $dbm = (new DataBuilderManager());

        /** @var \Amethyst\Models\DataBuilder */
        $data_builder = $dbm->getRepository()->findOneById(intval($request->input('data_builder_id')));

        if ($data_builder == null) {
            return $this->error([['message' => 'invalid data_builder_id']]);
        }

        $data = (array) $request->input('data');

        $result = $dbm->build($data_builder, $data);

        if (!$result->ok()) {
            return $this->error(['errors' => $result->getSimpleErrors()]);
        }

        $data = array_merge($data, $result->getResource());

        if ($result->ok()) {
            $result = $this->getManager()->render(
                $data_builder,
                strval($request->input('filetype')),
                [
                    'content' => strval($request->input('content')),
                ],
                $data
            );
        }

        if (!$result->ok()) {
            return $this->error(['errors' => $result->getSimpleErrors()]);
        }

        $resource = $result->getResource();

        return $this->success(['resource' => [
            'content' => base64_encode($resource['content']),
        ]]);
    }
}
