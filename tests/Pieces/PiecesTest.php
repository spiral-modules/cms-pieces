<?php

namespace Spiral\Tests\Pieces;

use Spiral\Pieces\Database\PageMeta;
use Spiral\Pieces\Database\PieceLocation;
use Spiral\Pieces\Pieces;
use Spiral\Views\ViewCacheLocator;
use Symfony\Component\DomCrawler\Crawler;

class PiecesTest extends \Spiral\Tests\BaseTest
{
    protected function deleteCacheFiles()
    {
        foreach ($this->files->getFiles($this->views->getEnvironment()->cacheDirectory()) as $filename) {
            $this->files->delete($filename);
        }
    }

    public function testPiece()
    {
        /** @var Pieces $pieces */
        $pieces = $this->container->get(Pieces::class);
        $piece = $pieces->findPiece('sample-piece');

        $this->assertNotNull($piece);
        $this->assertSame('Sample.', trim($piece->content));
        $this->assertSame(2, $piece->locations->count());

        /** @var PieceLocation $location */
        $location = $piece->locations->getIterator()[0];
        $this->assertSame("default", $location->view);
        $this->assertSame("default", $location->namespace);
    }

    public function testMeta()
    {
        /** @var Pieces $pieces */
        $pieces = $this->container->get(Pieces::class);
        $meta = $pieces->findMeta('default', 'default', 'static');

        $this->assertNotNull($meta);

        $this->assertSame('default', $meta->namespace);
        $this->assertSame('default', $meta->view);
        $this->assertSame('static', $meta->code);

        $this->assertSame('Title', $meta->title);
        $this->assertSame('Description', $meta->description);
        $this->assertSame('Keywords', $meta->keywords);
        $this->assertSame('<meta name="foo" content="Bar">', trim($meta->html));
    }

    public function testEditableRendering()
    {
        $env = $this->views->getEnvironment()->withDependency('cms.editable', function () {
            return true;
        });

        $crawler = new Crawler($this->views->withEnvironment($env)->render('default'));

        // page metadata
        $title = $crawler->filterXPath('//title')->html();
        $description = $crawler->filterXPath('//meta[@name="description"]')->attr('content');
        $keywords = $crawler->filterXPath('//meta[@name="keywords"]')->attr('content');
        $custom = $crawler->filterXPath('//meta[@name="foo"]')->attr('content');

        $this->assertSame('Title', $title);
        $this->assertSame('Description', $description);
        $this->assertSame('Keywords', $keywords);
        $this->assertSame('Bar', $custom);

        // check js
        $meta = json_encode($this->orm->source(PageMeta::class)->findOne());
        $script = trim($crawler->filterXPath('//script')->html());
        $this->assertSame("window.metadata = $meta;", $script);

        // piece div
        $div = $crawler->filterXPath('//div');
        $this->assertGreaterThan(0, $div->count());
        $this->assertSame('html', $div->attr('data-piece'));
        $this->assertSame('sample-piece', $div->attr('data-id'));
        $this->assertSame('Sample.', trim($div->html()));
    }

    public function testRendering()
    {
        $env = $this->views->getEnvironment()->withDependency('cms.editable', function () {
            return false;
        });

        $crawler = new Crawler($this->views->withEnvironment($env)->render('default'));

        // page metadata
        $title = $crawler->filterXPath('//title')->html();
        $description = $crawler->filterXPath('//meta[@name="description"]')->attr('content');
        $keywords = $crawler->filterXPath('//meta[@name="keywords"]')->attr('content');
        $custom = $crawler->filterXPath('//meta[@name="foo"]')->attr('content');
        $this->assertSame('Title', $title);
        $this->assertSame('Description', $description);
        $this->assertSame('Keywords', $keywords);
        $this->assertSame('Bar', $custom);

        // piece div
        $div = $crawler->filterXPath('//body');
        $this->assertGreaterThan(0, $div->count());
        $this->assertContains('Sample.', trim($div->html()));
    }

    public function testRecompileCache()
    {
        $this->deleteCacheFiles();

        $env1 = $this->views->getEnvironment()->withDependency('value', function () {
            return false;
        });
        $env2 = $this->views->getEnvironment()->withDependency('value', function () {
            return true;
        });

        $this->views->withEnvironment($env1)->render('default');
        $this->views->withEnvironment($env2)->render('default');
        $this->views->withEnvironment($env1)->render('home');
        $this->views->withEnvironment($env2)->render('home');

        /** @var ViewCacheLocator  $viewCacheLocator */
        $viewCacheLocator = $this->container->get(ViewCacheLocator::class);
        $files = $viewCacheLocator->getFiles('default');

        $this->assertSame(2, count($files));
    }
}