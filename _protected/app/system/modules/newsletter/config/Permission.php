<?php
/**
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012-2017, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / Newsletter / Config
 */

namespace PH7;

defined('PH7') or exit('Restricted access');

use
PH7\Framework\Layout\Html\Design,
PH7\Framework\Mvc\Router\Uri,
PH7\Framework\Url\Header;

class Permission extends PermissionCore
{
    public function __construct()
    {
        parent::__construct();

        if (UserCore::auth() && $this->registry->controller === 'HomeController') {
            // Newsletter subscription is only for visitors, not for members since they can subscribe into their account
            Header::redirect(Uri::get('user','main','index'));
        }

        if (!AdminCore::auth() && $this->registry->controller === 'AdminController') {
            // For security reasons, we don't redirect the user to the admin panel URL
            Header::redirect(
                Uri::get('user','main','login'),
                $this->adminSignInMsg(),
                Design::ERROR_TYPE
            );
        }
    }
}
