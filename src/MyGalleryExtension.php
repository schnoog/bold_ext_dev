<?php

namespace Bolt\Extension\schnoog\mygallery;

use Bolt\Asset\Snippet\Snippet;
use Bolt\Asset\Target;
use Bolt\Collection\Bag;
use Bolt\Extension\SimpleExtension;
use Bolt\Version;
use Symfony\Component\HttpFoundation\Request;
use Twig\Markup;

/**
 * ExtensionName extension class.
 *
 * @author Your Name <you@example.com>
 */
class MygalleryExtension extends SimpleExtension
{

    protected function registerTwigFunctions()
    {
        return [
            'gallery'     => 'gallery',
            'gallerylinks' => 'gallerylinks',
        ];
    }

    public function gallerylinks()
    {
        $config = $this->getConfig();
        $app = $this->getContainer();
        $request = Request::createFromGlobals();
		$html = "<h1>Hier könnte Ihre Werbung stehen</h1>";
        if (!$config->get('disqus_name')) {
//            return new \Twig_Markup("<p>Please set the 'Disqus Short name' in <code>app/config/extensions/disqus.bolt.yml</code>.</p>", 'UTF-8');
        }

        $id = $request->server->get('REQUEST_URI');
        if ((version_compare(Version::forComposer(), 3.2, '>='))) {
            $canonical = $app['canonical']->getUrl();
        } else {
            $canonical = $app['resources']->getUrl('canonicalurl');
        }
        $html = str_replace('%shortname%', $config->get('disqus_name'), $html);
        $html = str_replace('%url%', $canonical, $html);
        $html = str_replace('%id%', $id, $html);
        return new Markup($html, 'UTF-8');
    }
    public function gallery($folder)
    {
        $config = $this->getConfig();
        $app = $this->getContainer();
    
        $script = '<script type="text/javascript>var tmp=12;</script>';
        $script = str_replace("%shortname%", $config->get('disqus_name'), $script);
        $asset = new Snippet();
        $asset->setCallback($script)
            ->setLocation(Target::END_OF_BODY);
        $app['asset.queue.snippet']->add($asset);
    
        $html = '%hosturl%%link%#disqus_thread';
        $html = str_replace('%hosturl%', $app['resources']->getUrl('hosturl'), $html);
        $html = str_replace('%link%', $link, $html);
        return new Markup($html, 'UTF-8');
    }
    
    protected function getDefaultConfig()
    {
        return ['disqus_name' => 'boltcm'];
    }
    protected function getConfig()
    {
        return $this->config = new Bag(parent::getConfig());
    }    

}
