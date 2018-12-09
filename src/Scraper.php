<?php

namespace Grab;

use Clue\React\Buzz\Browser;
use Psr\Http\Message\ResponseInterface;
use React\Filesystem\Filesystem;
use React\Filesystem\FilesystemInterface;

final class Scraper
{
    /**
     * @var Browser
     */
    private $browser;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $directory;

    private $urls;

    /**
     * Scraper constructor.
     *
     * @param Browser             $browser
     * @param FilesystemInterface $filesystem
     * @param string              $directory
     */
    public function __construct(Browser $browser, FilesystemInterface $filesystem, string $directory)
    {
        $this->browser = $browser;
        $this->filesystem = $filesystem;
        $this->directory = $directory;
    }

    public function addUrl(string $url)
    {
        $this->urls[] = $url;
    }

    /**
     */
    public function scrape()
    {
        foreach($this->urls as $url) {
            $this->browser->get($url)->then(function (ResponseInterface $response) use ($url) {
                $this->saveImage($response, $url);
            });
        }
    }

    /**
     * @param ResponseInterface $response
     * @param                   $url
     */
    private function saveImage(ResponseInterface $response, $url)
    {
        $this->filesystem->file($this->getFilePath($url))
            ->putContents($response->getBody());
    }

    /**
     * @param $url
     * @return string
     */
    private function getFilePath($url): string
    {
        return $this->directory . DIRECTORY_SEPARATOR . 'test-file.jpg';
    }
}