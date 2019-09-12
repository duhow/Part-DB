<?php
/**
 *
 * part-db version 0.1
 * Copyright (C) 2005 Christoph Lechner
 * http://www.cl-projects.de/
 *
 * part-db version 0.2+
 * Copyright (C) 2009 K. Jacobs and others (see authors.php)
 * http://code.google.com/p/part-db/
 *
 * Part-DB Version 0.4+
 * Copyright (C) 2016 - 2019 Jan Böhmer
 * https://github.com/jbtronics
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
 *
 */

namespace App\Controller;

use App\Entity\UserSystem\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RedirectController extends AbstractController
{
    protected $default_locale;

    public function __construct(string $default_locale)
    {
        $this->default_locale = $default_locale;
    }

    public function addLocalePart(Request $request)
    {
        //By default we use the global default locale
        $locale = $this->default_locale;

        //Check if a user has set a preferred language setting:
        $user = $this->getUser();
        if ($user instanceof User) {
           if(!empty($user->getLanguage())) {
               $locale = $user->getLanguage();
           }
        }

        //$new_url = str_replace($request->getPathInfo(), '/' . $locale . $request->getPathInfo(), $request->getUri());
        $new_url = $request->getUriForPath('/' . $locale . $request->getPathInfo());

        return $this->redirect($new_url);
    }
}