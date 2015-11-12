<?php

namespace KamilZ\B2BackblazeBundle\Twig\Extension;

/**
 * B2Helper twig helper class.
 */
class B2HelperExtension  extends \Twig_Extension
{
    /**
     * @var String Bucket region.
     */
    protected $region;
    /**
     * Constructor.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->region = $params['bucket_region'];
    }

    public function getFilters()
    {
        return array(new \Twig_SimpleFilter('b2', array($this, 'appendRegion')));
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function appendRegion($name)
    {
        return $this->region.($name[0] == '/' ? substr($name, 1) : $name);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'b2_helper';
    }
}
