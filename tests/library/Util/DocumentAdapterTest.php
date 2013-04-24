<?php
/*
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
 * @category    Application Unit Tests
 * @author      Jens Schwidder <schwidder@zib.de>
 * @copyright   Copyright (c) 2008-2012, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 * @version     $Id$
 */

/**
 * Unit test for class Review_Model_DocumentAdapter.
 *
 * TODO $view not used at the moment, refactor or add tests
 */
class Util_DocumentAdapterTest extends ControllerTestCase {

    public function testHasFilesTrue() {
        $view = Zend_Registry::get('Opus_View');

        $doc = new Opus_Document(1);

        $docAdapter = new Util_DocumentAdapter($view, $doc);

        $this->assertTrue($docAdapter->hasFiles());
    }

    public function testHasFilesFalse() {
        $view = Zend_Registry::get('Opus_View');

        $doc = new Opus_Document();

        $docAdapter = new Util_DocumentAdapter($view, $doc);

        $this->assertFalse($docAdapter->hasFiles());
    }

    public function testGetFileCount() {
        $view = Zend_Registry::get('Opus_View');

        $doc = new Opus_Document(1);

        $docAdapter = new Util_DocumentAdapter($view, $doc);

        $this->assertEquals(2, $docAdapter->getFileCount());
    }

    public function testGetFileCountZero() {
        $view = Zend_Registry::get('Opus_View');

        $doc = new Opus_Document();

        $docAdapter = new Util_DocumentAdapter($view, $doc);

        $this->assertEquals(0, $docAdapter->getFileCount());
    }

    public function testIsBelongsToBibliographyTrue() {
        $view = Zend_Registry::get('Opus_View');

        $doc = new Opus_Document();

        $doc->setBelongsToBibliography(true);

        $docAdapter = new Util_DocumentAdapter($view, $doc);

        $this->assertTrue($docAdapter->isBelongsToBibliography());
    }

    public function testIsBelongsToBibliographyFalse() {
        $view = Zend_Registry::get('Opus_View');

        $doc = new Opus_Document();

        $doc->setBelongsToBibliography(false);

        $docAdapter = new Util_DocumentAdapter($view, $doc);

        $this->assertFalse($docAdapter->isBelongsToBibliography());
    }
    
    /**
     * Tests returning title in document language.
     */
    public function testGetMainTitle() {
        $view = Zend_Registry::get('Opus_View');
        
        $doc = new Opus_Document();
        
        $title = new Opus_Title();
        $title->setLanguage('deu');
        $title->setValue('Deutscher Titel');
        $doc->addTitleMain($title);
        
        $title = new Opus_Title();
        $title->setLanguage('eng');
        $title->setValue('Englischer Titel');
        $doc->addTitleMain($title);
        
        $doc->setLanguage('eng');
        
        $docAdapter = new Util_DocumentAdapter($view, $doc);
        
        $this->assertEquals($docAdapter->getMainTitle(), 'Englischer Titel');
    }
    
    public function testGetDocTitle() {
        $view = Zend_Registry::get('Opus_View');
        
        $doc = new Opus_Document();
        
        $title = new Opus_Title();
        $title->setLanguage('deu');
        $title->setValue('Deutscher Titel');
        $doc->addTitleMain($title);
        
        $title = new Opus_Title();
        $title->setLanguage('eng');
        $title->setValue('Englischer Titel');
        $doc->addTitleMain($title);
        
        $doc->setLanguage('eng');
        
        $docAdapter = new Util_DocumentAdapter($view, $doc);
        
        $this->assertEquals($docAdapter->getDocTitle(), 'Deutscher Titel');
    }
    
    public function testGetAuthors() {
        $doc = new Opus_Document();
        
        $person = new Opus_Person();
        $person->setLastName("Doe");
        $doc->addPersonAuthor($person);
        
        $person = new Opus_Person();
        $person->setLastName("Smith");
        $person->setFirstName("Jane");
        $doc->addPersonAuthor($person);
        
        $docAdapter = new Util_DocumentAdapter(null, $doc);
        
        $authors = $docAdapter->getAuthors();
        
        $this->assertEquals('Doe', $authors[0]['name']);
        $this->assertEquals('Smith, Jane', $authors[1]['name']);
    }
        
    public function testGetAuthorsForDocumentWithoutAuthors() {
        $view = Zend_Registry::get('Opus_View');
        
        $doc = new Opus_Document();
        
        $docAdapter = new Util_DocumentAdapter($view, $doc);
        
        $authors = $docAdapter->getAuthors();
        
        $this->assertTrue(is_array($authors));
        $this->assertEmpty($authors);
    }
    
}