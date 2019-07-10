<?php

namespace Amethyst\Attributes;

use Illuminate\Support\Collection;
use Railken\Bag;
use Railken\Lem\Attributes\TextAttribute;
use Railken\Lem\Contracts\EntityContract;

class FileChecksumAttribute extends TextAttribute
{
    /**
     * Name attribute.
     *
     * @var string
     */
    protected $name = 'checksum';

    /**
     * Update entity value.
     *
     * @param \Amethyst\Models\Template $entity
     * @param \Railken\Bag              $parameters
     *
     * @return Collection
     */
    public function update(EntityContract $entity, Bag $parameters)
    {
        $entity->checksum = $this->getManager()->checksum(!empty($entity->content) ? $entity->content : '');

        return new Collection();
    }
}
