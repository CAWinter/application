<?php
/**
 * This file is part of OPUS. The software OPUS has been originally developed
 * at the University of Stuttgart with funding from the German Research Net,
 * the Federal Department of Higher Education and Research and the Ministry
 * of Science, Research and the Arts of the State of Baden-Wuerttemberg.
 *
 * OPUS 4 is a complete rewrite of the original OPUS software and was developed
 * by the Stuttgart University Library, the Library Service Center
 * Baden-Wuerttemberg, the Cooperative Library Network Berlin-Brandenburg,
 * the Saarland University and State Library, the Saxon State Library -
 * Dresden State and University Library, the Bielefeld University Library and
 * the University Library of Hamburg University of Technology with funding from
 * the German Research Foundation and the European Regional Development Fund.
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
 * @category    Application
 * @package     Module_SolrSearch
 * @author      Sascha Szott <szott@zib.de>
 * @copyright   Copyright (c) 2008-2010, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 * @version     $Id$
 */

class SolrSearch_Model_CollectionList {

    private $collection;
    private $collectionRole;
    private $collectionNode;

    public function __construct($collectionNodeId) {
        if (is_null($collectionNodeId)) {
            throw new SolrSearch_Model_Exception('Could not browse collection due to missing id parameter.');
        }

        $collectionNode = null;
        try {
            $collectionNode = new Opus_CollectionNode((int) $collectionNodeId);
        }
        catch (Opus_Model_NotFoundException $e) {
            throw new SolrSearch_Model_Exception("Collection node with id '" . $collectionNodeId . "' does not exist.");
        }
        if ($collectionNode->getVisibility() === false) {
            throw new SolrSearch_Model_Exception("Collection node with id '" . $collectionNodeId . "' is not visible.");
        }

        $collectionRole = null;
        try {
            $collectionRole = new Opus_CollectionRole($collectionNode->getRoleId());
        }
        catch (Opus_Model_NotFoundException $e) {
            throw new SolrSearch_Model_Exception("Collection role with id '" . $collectionNode->getRoleId() . "' does not exist.");
        }
        
        if (!($collectionRole->getVisible() === '1' and $collectionRole->getVisibleBrowsingStart() === '1')) {
            throw new SolrSearch_Model_Exception("Collection role with id '" . $collectionRole->getId() . "' is not visible.");
        }

        $collection = null;
        try {
            $collection = new Opus_Collection($collectionNode->getCollectionId());
        }
        catch (Opus_Model_NotFoundException $e) {
            throw new SolrSearch_Model_Exception("Collection with id '" . $collectionNode->getCollectionId() . "' does not exist.");
        }
                
        $this->collectionNode = $collectionNode;
        $this->collectionRole = $collectionRole;
        $this->collection = $collection;
    }

    public function isRootNode() {
        return count($this->collectionNode->getParents()) === 1;
    }

    /**
     *
     * @return array An array of CollectionNode objects along the path to the root.
     */
    public function getParents() {
        $parents = $this->collectionNode->getParents();
        $numOfParents = count($parents);
        if ($numOfParents < 2) {
            // only the current node and the root node are present in $parents
            return array();
        }                
        $results = array();
        for ($i = 1; $i < $numOfParents; $i++) {
            array_push($results, $parents[$numOfParents - $i]);
        }
        return $results;
    }

    public function getSubNodes() {
        $subnodes = array();
        foreach ($this->collectionNode->getChildren() as $subnode) {
            if ($subnode->getVisibility() === true) {
                array_push($subnodes, $subnode);
            }
        }
        return $subnodes;
    }

    public function getTitle() {
        if ($this->isRootNode()) {
            return $this->getCollectionRoleTitle();
        }
        return $this->collection->getDisplayName('browsing');
    }

    public function getTheme() {
        return $this->collection->getTheme();
    }

    public function getCollectionId() {
        return $this->collection->getId();
    }

    public function getCollectionRoleTitle() {
        return 'search_index_custom_browsing_' . $this->collectionRole->getDisplayName('browsing');
    }
}
?>
