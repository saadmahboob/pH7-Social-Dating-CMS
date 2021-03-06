<?php
/**
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2016-2017, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / Admin / From / Processing
 */
namespace PH7;

defined('PH7') or exit('Restricted access');

use
PH7\Framework\Cache\Cache,
PH7\Framework\Mvc\Model\Module as ModuleModel;

class DisableModuleFormProcess extends Form
{
    public function __construct()
    {
        parent::__construct();

        $oModuleModel = new ModuleModel;

        // First, disable all mods as uncheckboxes elements aren't send throughth the form
        $this->disableMods($oModuleModel);

        // Then, enable the mods selected to be enabled
        foreach($this->httpRequest->post('module_id') as $iModId) {
            $oModuleModel->update($iModId, '1'); // Need to be string because in DB it's an "enum" type
        }
        unset($oModuleModel);

        $this->clearCache();

        \PFBC\Form::setSuccess('form_module', t('Module Status have been saved!'));
    }

    protected function disableMods(ModuleModel $oModuleModel)
    {
        foreach ($oModuleModel->get() as $oMod) {
            // Need to be string because in DB it's an "enum" type
            $oModuleModel->update($oMod->moduleId, '0');
        }
    }

    private function clearCache()
    {
        (new Cache)->start(ModuleModel::CACHE_GROUP, null, null)->clear();
    }
}
