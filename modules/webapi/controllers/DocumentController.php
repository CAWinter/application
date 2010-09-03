<?php
/**
 * This file is part of OPUS. The software OPUS has been originally developed
 * at the University of Stuttgart with funding from the German Research Net,
 * the Federal Department of Higher Education and Research and the Ministry
 * of Science, Research and the Arts of the State of Baden-Wuerttemberg.
 *
 * OPUS 4 is a complete rewrite of the original OPUS software and was developed
 * by the Stuttgart University Library, the Library Service Center
 * Baden-Wuerttemberg, the North Rhine-Westphalian Library Service Center,
 * the Cooperative Library Network Berlin-Brandenburg, the Saarland University
 * and State Library, the Saxon State Library - Dresden State and University
 * Library, the Bielefeld University Library and the University Library of
 * Hamburg University of Technology with funding from the German Research
 * Foundation and the European Regional Development Fund.
 *
 * LICENCE
 * OPUS is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the Licence, or any later version.
 * OPUS is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details. You should have received a copy of the GNU General Public License
 * along with OPUS; if not, write to the Free Software Foundation, Inc., 51
 * Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * @category   Application
 * @package    Module_Webapi
 * @author     Henning Gerhardt (henning.gerhardt@slub-dresden.de)
 * @copyright  Copyright (c) 2009, OPUS 4 development team
 * @license    http://www.gnu.org/licenses/gpl.html General Public License
 * @version    $Id$
 */

/**
 * Controller for handling document specific requests.
 */
class Webapi_DocumentController extends Controller_Rest {

    /**
     * Handles get requests.
     *
     * @see    library/Controller/Controller_Rest#getAction()
     * @return void
     */
    public function getAction() {
        $doc = new Webapi_Model_Document();
        $original_action = $this->requestData['original_action'];
        if ((false === empty($original_action))
            and (true === is_numeric($original_action))) {
            $result = $doc->getDocument($original_action);
        } else {
            $result = $doc->getAllDocuments();
        }
        $this->getResponse()->setBody($result);
        $this->getResponse()->setHttpResponseCode($doc->getResponseCode());
    }

    /**
     * Delete a specific document.
     *
     * @see    library/Controller/Controller_Rest#deleteAction()
     * @return void
     */
    public function deleteAction() {
        $original_action = $this->requestData['original_action'];
        if ((false === empty($original_action))
            and (true === is_numeric($original_action))) {
            $doc = new Webapi_Model_Document();
            $doc->deleteDocument($original_action);
            $this->getResponse()->setHttpResponseCode($doc->getResponseCode());
        } else {
            $this->getResponse()->setHttpResponseCode(404);
        }
    }

    /**
     * Add a new document to database.
     *
     * @see library/Controller/Controller_Rest#putAction()
     */
    public function putAction() {
        $rawBody = $this->getRequest()->getRawBody();
        $doc = new Webapi_Model_Document();
        $result = $doc->addNewDocument($rawBody);
        $this->getResponse()->setBody($result);
        $this->getResponse()->setHttpResponseCode($doc->getResponseCode());
    }

    /**
     * Update document data.
     *
     * @see library/Controller/Controller_Rest#postAction()
     */
    public function postAction() {
        $docId = $this->requestData['original_action'];
        $rawBody = $this->getRequest()->getRawBody();
        $doc = new Webapi_Model_Document();
        $result = $doc->update($docId, $rawBody);
        $this->getResponse()->setBody($result);
        $this->getResponse()->setHttpResponseCode($doc->getResponseCode());
    }

}