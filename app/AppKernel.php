<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // Symfony
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            
            // Doctrine
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            
            // User
            new FOS\UserBundle\FOSUserBundle(),
                       
            // Other
            new Bmatzner\FontAwesomeBundle\BmatznerFontAwesomeBundle(),
            
            // Spectasonic Front
            new Spectasonic\Front\CoreBundle\SpectasonicFrontCoreBundle(),
            new Spectasonic\Front\ShopBundle\SpectasonicFrontShopBundle(),
            new Spectasonic\Front\UserBundle\SpectasonicFrontUserBundle(),
            new Spectasonic\Front\ForumBundle\SpectasonicFrontForumBundle(),
            new Spectasonic\Front\ContactBundle\SpectasonicFrontContactBundle(),
            new Spectasonic\Front\NewsBundle\SpectasonicFrontNewsBundle(),
                        
            // Spectasonic Back
            new Spectasonic\Back\CoreBundle\SpectasonicBackCoreBundle(),
            new Spectasonic\Back\BlogBundle\SpectasonicBackBlogBundle(),
            new Spectasonic\Back\ShopManagerBundle\SpectasonicBackShopManagerBundle(),
            new Spectasonic\Back\RemoteBundle\SpectasonicBackRemoteBundle(),
            new Spectasonic\Back\SupportBundle\SpectasonicBackSupportBundle(),
            new Spectasonic\Back\UserManagerBundle\SpectasonicBackUserManagerBundle(),
            new Spectasonic\Back\GestionContactBundle\SpectasonicBackGestionContactBundle(),
                        
            // Generate CRUD controller with Bootstrap
            new Lexik\Bundle\FormFilterBundle\LexikFormFilterBundle(),
            new Petkopara\TritonCrudBundle\PetkoparaTritonCrudBundle(),
            
            // CKEditor Bundle
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            
            // Addon for CKEditor (upload/search local server image, video, pdf...)
            new FM\ElfinderBundle\FMElfinderBundle(),
            
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
