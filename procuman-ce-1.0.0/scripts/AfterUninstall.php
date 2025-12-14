<?php

class AfterUninstall
{
    protected $container;

    public function run($container)
    {
        $this->container = $container;
        $config = $this->container->get('config');

        $tabList = $config->get('tabList', []);
        $quickCreateList = $config->get('quickCreateList', []);
        $globalSearchEntityList = $config->get('globalSearchEntityList', []);

        foreach ($tabList as $i => $item) {
            if ($item == 'CSupplier') {
                unset($tabList[$i]);
            }
        }
        $tabList = array_values($tabList);

        foreach ($quickCreateList as $i => $item) {
            if ($item == 'CSupplier') {
                unset($quickCreateList[$i]);
            }
			if ($item == 'CProduct') {
                unset($quickCreateList[$i]);
            }
			if ($item == 'CPurchaseOrder') {
                unset($quickCreateList[$i]);
            }
        }
        $quickCreateList = array_values($quickCreateList);

        foreach ($globalSearchEntityList as $i => $item) {
            if ($item == 'CSupplier') {
                unset($globalSearchEntityList[$i]);
            }
			if ($item == 'CProduct') {
                unset($globalSearchEntityList[$i]);
            }
			if ($item == 'CPurchaseOrder') {
                unset($globalSearchEntityList[$i]);
            }
        }
        $globalSearchEntityList = array_values($globalSearchEntityList);

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
