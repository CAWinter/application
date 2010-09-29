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
 * @category    Application
 * @author      Pascal-Nicolas Becker <becker@zib.de>
 * @copyright   Copyright (c) 2008-2010, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 * @version     $Id$
 */

$doc = new Opus_Document();
$doc->setType('all');
$doc->setServerState('published');
$doc->setServerDatePublished('01.01.1900');


// damn API. $doc->addPersonSubmiter() doesn't work for link models!
// -> we should change this in 4.x
$submitter = new Opus_Person();
$submitter->setFirstName('Donald')->setLastName('Duck')->setEmail('donald@example.org')->setDateOfBirth('13.03.1920')->setPlaceOfBirth('Entenhausen');
$doc->addPersonSubmitter($submitter);

$author = new Opus_Person();
$author->setFirstName('Daniel')->setLastName('Düsentrieb')->setAcademicTitle('Dr.-Ing.');
$doc->addPersonAuthor($author);

$doc->setLanguage('deu');

$titleMain = $doc->addTitleMain();
$titleMain->setValue('Dokument zur empirischen Unterschung der OAI-Schnittstelle');
$titleMain->setLanguage('deu');
$titleMain2 = $doc->addTitleMain();
$titleMain2->setValue('Document for empirical testing OAI interface');
$titleMain2->setLanguage('eng');

$abstract = $doc->addTitleAbstract();
$abstract->setValue('Dokument, dass alle Daten enhält, um testen zu können, wie die OAI-Schnittstelle sie ausgibt.');
$abstract->setLanguage('deu');

$titleSub = $doc->addTitleSub();
$titleSub->setValue('Beispielhaft erleutert an OPUS 4.0.0');
$titleSub->setLanguage('deu');

$titleAdditional = $doc->addTitleAdditional();
$titleAdditional->setValue('OAI-Schnittstellen empirisch testen am Beispiel von OPUS 4.0');
$titleAdditional->setLanguage('deu');

$titleParent = $doc->addTitleParent();
$titleParent->setValue('Tester interface d\'OAI en OPUS 4.0');
$titleParent->setLanguage('fra');

$doc->setPageNumber('123');
$doc->setPageFirst('122');
$doc->setPageLast('124');

$doc->setVolume('4');
$doc->setIssue('18');

$instituteName='Institut für empirische Forschung';
$institutesRole = Opus_CollectionRole::fetchByName('institutes');
if (is_null($institutesRole) === true) {
    $institutesRole = new Opus_CollectionRole();
    $institutesRole->setName('institutes')
                   ->setOaiName('institutes')
                   ->setPosition(1)
                   ->setVisible(1)
                   ->setVisibleBrowsingStart(1)
                   ->setDisplayBrowsing('Name')
                   ->setVisibleFrontdoor(1)
                   ->setDisplayFrontdoor('Name')
                   ->setVisibleOai('Name')
                   ->setDisplayOai('Name')
                   ->store();
}
$instituteCollections = Opus_Collection::fetchCollectionsByRoleName($institutesRole->getId(), $instituteName);
if (count($instituteCollections) >=1) {
    $instituteCollection = $instituteCollections[0];
} else {
    $rootCollection = $institutesRole->getRootCollection();
    if (is_null($rootCollection) === true) {
        $rootCollection = $institutesRole->addRootCollection();
        $rootCollection->setVisible(1)->store();
        $institutesRole->store();
    }
    $instituteCollection = $rootCollection->addLastChild();
    $instituteCollection->setVisible(1)
                        ->setName($instituteName)
                        ->store();
}
$doc->addCollection($instituteCollection);

$doc->setPublishedYear('2010');
$doc->setPublishedDate('28.09.2010');

$doc->setPublisherName('The Walt Disney Company');
$doc->setPublisherPlace('Burbank, CA');

$doc->setCompletedYear('2010');
$doc->setCompletedDate('27.09.2010');

$o3id = $doc->addIdentifierOpus3();
$o3id->setValue('1234');

// empty URN will be automaticaly replace by new URN.
$urn = $doc->addIdentifierUrn();
$urn->setValue('urn:nbn:de:kobv:nn-opus-173');

$isbn = $doc->addIdentifierIsbn();
$isbn->setValue('978-3-86680-192-9');

$issn = $doc->addIdentifierIssn();
$issn->setValue('1234-5678');

$doc->addIdentifierOpac()->setValue('OPAC-ID 001 1237890654');

$doc->setThesisDateAccepted('01.02.2003');

$dnbInstitute=new Opus_DnbInstitute();
$dnbInstitute->setName('Forschungsinstitut für Code Coverage');
foreach(Opus_DnbInstitute::getGrantors() as $grantor) {
    if ($dnbInstitute->getName() === $grantor->getName()) {
        $dnbInstitute = $grantor;
        break;
    }
}
if (is_null($dnbInstitute->getId()) === true) {
        $dnbInstitute->setCity('Mousetown')->setIsGrantor(1)->store();
}
$doc->setThesisGrantor($dnbInstitute);
$doc->setThesisPublisher($dnbInstitute);

$referee = new Opus_Person();
$referee->setFirstName('Gyro');
$referee->setLastName('Gearloose');
$referee->setAcademicTitle('Prof. Dr.');
$referee->store();
$doc->addPersonReferee($referee );

$editor = new Opus_Person();
$editor->setFirstName('Bob');
$editor->setLastName('Foster');
$editor->store();
$doc->addPersonEditor($editor);

$advisor = new Opus_Person();
$advisor->setFirstName('Fred');
$advisor->setLastName('Clever');
$advisor->store();
$doc->addPersonAdvisor($advisor);

$translator = new Opus_Person();
$translator->setFirstName('Erika');
$translator->setLastName('Fuchs');
$translator->store();
$doc->addPersonTranslator($translator);

$contributor = new Opus_Person();
$contributor->setFirstName('Jeff');
$contributor->setLastName('Smart');
$contributor->store();
$doc->addPersonContributor($contributor);

$doc->setCreatingCorporation('Walt Disney Creation Laboratories');
$doc->setContributingCorporation('Pixar Animation Studio');

$swd = $doc->addSubjectSwd();
$swd->setValue('Test');

$free_subject_deu = $doc->addSubjectUncontrolled();
$free_subject_deu->setLanguage('deu')->setValue('Maustest');

$free_subject_eng = $doc->addSubjectUncontrolled();
$free_subject_eng->setLanguage('eng')->setValue('mouse test');

$note1 = $doc->addNote();
$note1->setVisibility('public')->setMessage('ein Dokument, dass noch eine Bemerkung braucht, weil im Abstract nicht alles gesagt wurde...');
$note2 = $doc->addNote();
$note2->setVisibility('private')->setMessage('und noch eine Bemerkung zum Bearbeitungsstand.');

$licences = Opus_Licence::getAll();
if (count($licences) >= 1) {
    $lic = $licences[0];
} else {
    $lic = new Opus_Licence();
    $lic->setActive(1);
    $lic->setLanguage('deu');
    $lic->setLinkLicence('http://www.test.de');
    $lic->setNameLong('Ein langer LizenzName');
    $lic->store();
}
$doc->setLicence($lic);

$doc->setServerDateUnlocking("23.05.1949");

$ddc = $doc->addSubjectDDC();
$ddc->setValue('Allgemeines, Wissenschaft')->setLanguage('deu')->setExternalKey('000');

$doc->store();
print("Document stored. ID: " . $doc->getId() . "\n");
