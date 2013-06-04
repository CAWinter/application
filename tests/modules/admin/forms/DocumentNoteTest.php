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
 * @category    Application Unit Test
 * @author      Jens Schwidder <schwidder@zib.de>
 * @copyright   Copyright (c) 2013, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 * @version     $Id$
 */

/**
 * Description of DocumentNoteTest
 */
class Admin_Form_DocumentNoteTest extends ControllerTestCase {
    
    public function testCreateForm() {
        $form = new Admin_Form_DocumentNote();
        
        $this->assertNotNull($form->getElement('Id'));
        $this->assertNotNull($form->getElement('Visibility'));
        $this->assertNotNull($form->getELement('Message'));
    }
    
    public function testPopulateFromModel() {
        $form = new Admin_Form_DocumentNote();
        
        $note = new Opus_Note();
        $note->setMessage('Message1');
        $note->setVisibility('public');
        
        $form->populateFromModel($note);
        
        $this->assertEquals('Message1', $form->getElement('Message')->getValue());
        $this->assertEquals(1, $form->getElement('Visibility')->getValue());
        
        $note->setVisibility('private');
        
        $form->populateFromModel($note);
        
        $this->assertEquals(0, $form->getElement('Visibility')->getValue());
    }
    
    public function testUpdateModel() {
        $form = new Admin_Form_DocumentNote();
        
        $form->getElement('Message')->setValue('Test Message');
        $form->getElement('Visibility')->setChecked(true);
        
        $note = new Opus_Note();
        
        $form->updateModel($note);
        
        $this->assertEquals('Test Message', $note->getMessage());
        $this->assertEquals('public', $note->getVisibility());
        
        $form->getElement('Visibility')->setChecked(false);
        
        $form->updateModel($note);
        
        $this->assertEquals('private', $note->getVisibility());
    }
    
    public function testGetModel() {
        $form = new Admin_Form_DocumentNote();
        
        $doc = new Opus_Document(146);
        
        $notes = $doc->getNote();
        
        $note = $notes[0];
        
        $form->getElement('Id')->setValue($note->getId());
        $form->getElement('Visibility')->setChecked(true);
        $form->getElement('Message')->setValue('Test Message');
        
        $model = $form->getModel();
        
        $this->assertEquals($note->getId(), $model->getId());
        $this->assertEquals('public', $model->getVisibility());
        $this->assertEquals('Test Message', $model->getMessage());
    }
    
    public function testGetNewModel() {
        $form = new Admin_Form_DocumentNote();
        
        $form->getElement('Visibility')->setChecked(false);
        $form->getElement('Message')->setValue('Test Message');
        
        $model = $form->getModel();
        
        $this->assertNull($model->getId());
        $this->assertEquals('private', $model->getVisibility());
        $this->assertEquals('Test Message', $model->getMessage());
    }
    
    public function testValidation() {
        $form = new Admin_Form_DocumentNote();
        
        $post = array(
            'Visibility' => '0',
            'Message' => ''
        );
        
        $this->assertFalse($form->isValid($post));
        
        $this->assertContains('isEmpty', $form->getErrors('Message'));
    }
    
}