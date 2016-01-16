<?php namespace phppdb\tests;

use PHPUnit_Framework_TestCase;
use phppdb\PalmDB,
    phppdb\PalmAddress,
    phppdb\PalmDatebook,
    phppdb\PalmDoc,
    phppdb\PalmListDB,
    phppdb\PalmMemo,
    phppdb\PalmSmallBASIC,
    phppdb\PalmTodoList;

class ModulesTest extends PHPUnit_Framework_TestCase
{

    public function testAddressbook() {
        $addr = new PalmAddress();

        // Create some categories
        $categorias = array(
            1 => 'VIP',
            'AAA',
            'Inicial'
        );
        $addr->SetCategoryList($categorias);

        // Add one entry
        $fields = array(
            'LastName' => 'Pascual',
            'FirstName' => 'Eduardo',
            'Phone1' => '21221552',
            'Phone2' => '58808912',
            'Phone5' => 'epascual@cie.com.mx',
            'Address' => 'Hda. la Florida 10A',
            'City' => 'Izcalli'
        );
        $addr->SetRecordRaw($fields);
        $addr->GoToRecord('+1');

        // Add another
        $fields = array(
            'LastName' => 'de tal',
            'FirstName' => 'fulanito',
            'Address' => 'Direccion',
            'Phone1' => '21232425',
            'Phone2' => 'fulanito@dondesea.com',
            'Phone1Type' => PalmAddress::LABEL_HOME,
            'Phone2Type' => PalmAddress::LABEL_EMAIL,
            'Phone3Type' => PalmAddress::LABEL_WORK,
            'Phone4Type' => PalmAddress::LABEL_FAX,
            'Phone5Type' => PalmAddress::LABEL_OTHER,
            'Display' => 1,
            'Reserved' => ''
        );
        $addr->SetRecordRaw($fields);
        $addr->SetRecordAttrib(PalmDB::RECORD_ATTRIB_PRIVATE | 1);  // Private record, category 1
        $this->assertEquals('3ce11d66da0a869632623f000460374a', $this->GenerateMd5($addr));
    }

    public function testDatebook() {
        $d = new PalmDatebook();

        /* Create a repeating event that happens every other Friday.
         * I want it to be an all-day event that says "Pay Day!" */
        $Repeat = array(
            'Type' => PalmDatebook::REPEAT_WEEKLY,
            'Frequency' => 2,
            'Days' => '5',
            'StartOfWeek' => 0
        );
        $Record = array(
            'Date' => '2001-11-2',
            'Description' => 'Pay Day!',
            'Repeat' => $Repeat
        );

        // Add the record to the datebook
        $d->SetRecordRaw($Record);
        $this->assertEquals('acb80f080d5d8161fb6651e0fc0310df', $this->GenerateMd5($d));
    }

    private function getMockDocText() {
        $text = "Mary had a little lamb,\n" .
            "little lamb,\n" .
            "little lamb.\n" .
            "Mary had a little lamb,\n" .
            "its fleece as white as snow.\n" .
            "\n" .
            "It followed her to school one day,\n" .
            "school one day,\n" .
            "school one day.\n" .
            "It followed her to school one day.\n" .
            "and I hope this doc text test works well.\n" .
            "\n" .
            "(Yeah, I know.  It does not rhyme.)\n";

        // Just in case the file is edited and the newlines are changed a bit.
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\r", "\n", $text);
        $text = explode("\n", $text);
        $newText = '';

        foreach ($text as $t) {
            trim($t);
            $newText .= $t . "\n";
        }
        return $newText;
    }

    public function testDocTest() {
        $d = new PalmDoc('Title Goes Here', false);
        $d->AddText($this->getMockDocText());
        $this->assertEquals('91fa7442b46075d8ff451c58457d7246', $this->GenerateMd5($d));
    }

    public function testCompressDocTest() {
        $c = new PalmDoc('Title Goes Here', true);
        $c->AddText($this->getMockDocText());
        $this->assertEquals('c5d02609dcfd8e565318da246226cd64', $this->GenerateMd5($c));
    }

    public function testList() {
        $d = new PalmListDB('List Test');
        $d->SetRecordRaw(array(
            'abc',
            '123',
            'Have Lots Of Fun!'
        ));
        $this->assertEquals('dfcb278a4508f33a3e6a0ba288d5d49e', $this->GenerateMd5($d));
    }

    public function testMemo() {
        $d = new PalmMemo();
        $d->SetText('Rolling along with the wind is no place to be.');
        $this->assertEquals('3066a78037a2c7394605d9a6b2af4e48', $this->GenerateMd5($d));
    }

    public function testSmallBASIC() {
        $d = new PalmSmallBASIC('pen.bas');
        $text = "! pen                     \n" .
            "\n" .
            "print \"Use /B (Graffiti) to exit\"\n" .
            "\n" .
            "pen on\n" .
            "while 1\n" .
            " if pen(0)\n" .
            "  line pen(4),pen(5)\n" .
            " fi\n" .
            "wend\n" .
            "pen off\n";;
        $text = str_replace('!', '\'', $text);
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\r", "\n", $text);
        $d->ConvertFromText($text);
        $this->assertEquals('e680fa5719b5ca1d7408148e2d8c7b43', $this->GenerateMd5($d));
    }

    public function testTodo() {
        $todo = new PalmTodoList();

        // Add some categories
        $categorias = array(
            1 => 'Visita',
            'Fax',
            'Correo'
        );
        $todo->SetCategoryList($categorias);

        // Add a record
        $record = array(
            'Description' => 'Enviar Fax',
            'Note' => "25\nProbar palm",
            'Priority' => 2
        );
        $todo->SetRecordRaw($record);
        $todo->SetRecordAttrib(2);  // Category #2
        $todo->GoToRecord('+1');

        // Add another record
        $record = array(
            'Description' => 'Llamar a juan',
            'Note' => '35',
            'DueDate' => '2002-5-31'
        );
        $todo->SetRecordRaw($record);
        $todo->SetRecordAttrib(PalmDB::RECORD_ATTRIB_PRIVATE | PalmDB::RECORD_ATTRIB_DIRTY | 0);  // Two flags and category 0
        $this->assertEquals('d009e145a7633a33f1376712e3a6bc12', $this->GenerateMd5($todo));
    }

    private function GenerateMd5(PalmDB $PalmDB, $DumpToScreen = false) {
        /* Change the dates so the header looks the same no matter when we
         * generate the file */
        $PalmDB->CreationTime = 1;
        $PalmDB->ModificationTime = 1;
        $PalmDB->BackupTime = 1;

        if (! $DumpToScreen)ob_start();
        $PalmDB->WriteToStdout();

        if (! $DumpToScreen) {
            $file = ob_get_contents();
            ob_end_clean();
            return md5($file);
        }

        return 'MD5 not generated';
    }
}
