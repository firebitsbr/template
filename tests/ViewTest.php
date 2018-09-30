<?php

namespace Railken\Amethyst\Tests;

use Railken\Amethyst\Fakers\TemplateFaker;
use Railken\Amethyst\Managers\TemplateManager;

class ViewTest extends BaseTest
{
    /**
     * Retrieve basic url.
     *
     * @return \Railken\Amethyst\Managers\TemplateManager
     */
    public function getManager()
    {
        return new TemplateManager();
    }

    public function testHtmlExtendsRender()
    {
        $parameters = TemplateFaker::make()->parameters()
            ->set('filename', 'html-test-base')
            ->set('filetype', 'text/html')
            ->set('content', 'The following is a block: {% block content %} {% endblock %}')
            ->set('mock_data', []);

        $result = $this->getManager()->create($parameters);
        $this->assertEquals(true, $result->ok());

        $this->getManager()->loadViews();

        $parameters = TemplateFaker::make()->parameters()
            ->set('filename', 'html-test')
            ->set('filetype', 'text/html')
            ->set('content', "{% extends 'amethyst::html-test-base' %}{% block content %}{{ message }}{% endblock %}")
            ->set('data_builder.mock_data', ['message' => 'lie']);

        $result = $this->getManager()->create($parameters);
        $this->assertEquals(true, $result->ok());
        $resource = $result->getResource();

        $rendered = $this->getManager()->renderMock($resource)->getResource()['content'];

        $this->assertEquals('The following is a block: lie', $rendered);
    }
}
