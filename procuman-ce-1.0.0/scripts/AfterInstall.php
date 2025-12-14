<?php

class AfterInstall
{
    protected $container;

    public function run($container)
    {
        $this->container = $container;

        $config = $this->container->get('config');

        $tabList = $config->get('tabList', []);

        if (!in_array('CSupplier', $tabList)) {
            array_unshift($tabList, 'CSupplier');
        }
		if (!in_array('CProduct', $tabList)) {
            array_unshift($tabList, 'CProduct');
        }
		if (!in_array('CPurchaseOrder', $tabList)) {
            array_unshift($tabList, 'CPurchaseOrder');
        }
        //if (!in_array('RealEstateProperty', $tabList)) {
        //    array_unshift($tabList, 'RealEstateProperty');
        //}

        $quickCreateList = $config->get('quickCreateList', []);

        if (!in_array('CSupplier', $quickCreateList)) {
            array_unshift($quickCreateList, 'CSupplier');
        }
		if (!in_array('CProduct', $quickCreateList)) {
            array_unshift($quickCreateList, 'CProduct');
        }
		if (!in_array('CPurchaseOrder', $quickCreateList)) {
            array_unshift($quickCreateList, 'CPurchaseOrder');
        }
        //if (!in_array('RealEstateRequest', $quickCreateList)) {
        //    array_unshift($quickCreateList, 'RealEstateRequest');
        //}

        $globalSearchEntityList = $config->get('globalSearchEntityList', []);

        if (!in_array('CSupplier', $globalSearchEntityList)) {
            array_unshift($globalSearchEntityList, 'CSupplier');
        }
		if (!in_array('CProduct', $globalSearchEntityList)) {
            array_unshift($globalSearchEntityList, 'CProduct');
        }
		if (!in_array('CPurchaseOrder', $globalSearchEntityList)) {
            array_unshift($globalSearchEntityList, 'CPurchaseOrder');
        }
        //if (!in_array('RealEstateProperty', $globalSearchEntityList)) {
        //    array_unshift($globalSearchEntityList, 'RealEstateProperty');
        //}

        if (!in_array('Contact', $globalSearchEntityList)) {
            $globalSearchEntityList[] = 'Contact';
        }
        if (!in_array('CSupplier', $globalSearchEntityList)) {
            $globalSearchEntityList[] = 'CSupplier';
        }
		if (!in_array('CProduct', $globalSearchEntityList)) {
            $globalSearchEntityList[] = 'CProduct';
        }
		if (!in_array('CPurchaseOrder', $globalSearchEntityList)) {
            $globalSearchEntityList[] = 'CPurchaseOrder';
        }

        $config->set('tabList', $tabList);
        $config->set('quickCreateList', $quickCreateList);
        $config->set('globalSearchEntityList', $globalSearchEntityList);

        $config->save();

        $this->clearCache();

    }

    protected function clearCache()
    {
        try {
            $this->container->get('dataManager')->clearCache();
        } catch (\Exception $e) {}
    }
}
