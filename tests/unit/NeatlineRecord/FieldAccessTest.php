<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 cc=76; */

/**
 * Field set/get tests for NeatlineRecord.
 *
 * @package     omeka
 * @subpackage  neatline
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */

class Neatline_NeatlineRecordTest_FieldAccess
    extends Neatline_Test_AppTestCase
{


    /**
     * Test field set and get.
     */
    public function testFieldAccess()
    {

        // Create a record.
        $record = $this->__record();

        // Set values:
        $record->item_id            = 1;
        $record->exhibit_id         = 2;
        $record->tag_id             = 3;
        $record->slug               = '4';
        $record->title              = '5';
        $record->body               = '6';
        $record->tags               = '7';
        $record->map_active         = 8;
        $record->map_focus          = '9';
        $record->map_zoom           = 10;

        // Set keys:
        $record->vector_color       = 11;
        $record->stroke_color       = 12;
        $record->select_color       = 13;
        $record->vector_opacity     = 14;
        $record->select_opacity     = 15;
        $record->stroke_opacity     = 16;
        $record->image_opacity      = 17;
        $record->stroke_width       = 18;
        $record->point_radius       = 19;
        $record->point_image        = '20';
        $record->max_zoom           = 21;
        $record->min_zoom           = 22;

        // Save with coverage.
        $record->save('POINT(1 1)');

        // Re-get the record object.
        $record = $this->_recordsTable->find($record->id);

        // Check values:
        $this->assertEquals($record->item_id,       1);
        $this->assertEquals($record->exhibit_id,    2);
        $this->assertEquals($record->tag_id,        3);
        $this->assertEquals($record->slug,          '4');
        $this->assertEquals($record->title,         '5');
        $this->assertEquals($record->body,          '6');
        $this->assertEquals($record->tags,          '7');
        $this->assertEquals($record->map_active,    8);
        $this->assertEquals($record->map_focus,     '9');
        $this->assertEquals($record->map_zoom,      10);

        // Check keys:
        $this->assertEquals($record->vector_color,      11);
        $this->assertEquals($record->stroke_color,      12);
        $this->assertEquals($record->select_color,      13);
        $this->assertEquals($record->vector_opacity,    14);
        $this->assertEquals($record->select_opacity,    15);
        $this->assertEquals($record->stroke_opacity,    16);
        $this->assertEquals($record->image_opacity,     17);
        $this->assertEquals($record->stroke_width,      18);
        $this->assertEquals($record->point_radius,      19);
        $this->assertEquals($record->point_image,       '20');
        $this->assertEquals($record->max_zoom,          21);
        $this->assertEquals($record->min_zoom,          22);

        // Check the coverage value.
        $this->assertEquals($this->getCoverageAsText($record),
            'POINT(1 1)');

    }


}