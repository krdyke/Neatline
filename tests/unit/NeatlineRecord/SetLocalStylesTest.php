<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 cc=76; */

/**
 * Tests for `setLocalStyles()` on NeatlineRecord.
 *
 * @package     omeka
 * @subpackage  neatline
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */

class Neatline_NeatlineRecordTest_SetLocalStyles
    extends Neatline_Test_AppTestCase
{


    /**
     * When setLocalStyles() is passed a values array that includes non-
     * null static fields but either (a) no defined style fields or (b)
     * null style fields, the presence of the defined static fields should
     * not cause a record-specific tag to be created.
     *
     * @group tags
     */
    public function testIgnoreStaticFields()
    {

        // Create a record.
        $record = $this->__record();


        // With null style fields:
        // -----------------------

        // Mock values:
        $values = array(

            // Local values:
            // -------------

            'id'            => $record->id,
            'item_id'       => null,
            'slug'          => 'slug2',
            'title'         => 'title2',
            'body'          => 'body2',
            'tags'          => 'tag3,tag4',
            'map_focus'     => 'lat2/lon2',
            'coverage'      => 'POINT(1 1)',
            'map_zoom'      => 2,
            'map_active'    => 0,

            // Null local styles:
            // --------------------

            'vector_color'      => null,
            'stroke_color'      => null,
            'select_color'      => null,
            'vector_opacity'    => null,
            'select_opacity'    => null,
            'stroke_opacity'    => null,
            'image_opacity'     => null,
            'stroke_width'      => null,
            'point_radius'      => null,
            'point_image'       => null,
            'max_zoom'          => null,
            'min_zoom'          => null

        );

        // Starting tags count.
        $startCount = $this->_tagsTable->count();

        // Set local styles.
        $record->setLocalStyles($values);

        // Check tags+0.
        $this->assertEquals($startCount, $this->_tagsTable->count());


        // With unset style fields:
        // ------------------------

        // Mock values:
        $values = array(

            // Local values:
            // -------------

            'id'            => $record->id,
            'item_id'       => null,
            'slug'          => 'slug2',
            'title'         => 'title2',
            'body'          => 'body2',
            'tags'          => 'tag3,tag4',
            'map_focus'     => 'lat2/lon2',
            'coverage'      => 'POINT(1 1)',
            'map_zoom'      => 2,
            'map_active'    => 0

            // Un-set local styles:
            // --------------------

        );

        // Starting tags count.
        $startCount = $this->_tagsTable->count();

        // Set local styles.
        $record->setLocalStyles($values);

        // Check tags+0.
        $this->assertEquals($startCount, $this->_tagsTable->count());

    }


    /**
     * setLocalStyles() should create a record-specific tag when non-null
     * style values are passed in and a tag does not already exist.
     *
     * @group tags
     */
    public function testTagCreation()
    {

        // Create a record.
        $record = $this->__record();

        // Mock values:
        $values = array(
            'vector_color'      => '1',
            'stroke_color'      => '2',
            'select_color'      => '3',
            'vector_opacity'    => 4,
            'select_opacity'    => 5,
            'stroke_opacity'    => 6,
            'image_opacity'     => 7,
            'stroke_width'      => 8,
            'point_radius'      => 9,
            'point_image'       => '10',
            'max_zoom'          => 11,
            'min_zoom'          => 12
        );

        // Starting tags count.
        $startCount = $this->_tagsTable->count();

        // Set local styles.
        $record->setLocalStyles($values);

        // Check tags+1.
        $this->assertEquals($startCount+1, $this->_tagsTable->count());

        // Check new tag.
        $tag = $this->getLastTag();
        $this->assertEquals($record->tag_id,        $tag->id);
        $this->assertEquals($tag->vector_color,     '1');
        $this->assertEquals($tag->stroke_color,     '2');
        $this->assertEquals($tag->select_color,     '3');
        $this->assertEquals($tag->vector_opacity,   4);
        $this->assertEquals($tag->select_opacity,   5);
        $this->assertEquals($tag->stroke_opacity,   6);
        $this->assertEquals($tag->image_opacity,    7);
        $this->assertEquals($tag->stroke_width,     8);
        $this->assertEquals($tag->point_radius,     9);
        $this->assertEquals($tag->point_image,      '10');
        $this->assertEquals($tag->max_zoom,         11);
        $this->assertEquals($tag->min_zoom,         12);


    }


    /**
     * setLocalStyles() should update a record-specific tag when non-null
     * style values are passed in and a tag already exists.
     *
     * @group tags
     */
    public function testTagUpdate()
    {

        // Create record.
        $exhibit = $this->__exhibit();
        $record = $this->__record(null, $exhibit);

        // Create tag.
        $tag = new NeatlineTag($exhibit);
        $tag->vector_color =    '1';
        $tag->stroke_color =    '2';
        $tag->select_color =    '3';
        $tag->vector_opacity =  4;
        $tag->select_opacity =  5;
        $tag->stroke_opacity =  6;
        $tag->image_opacity =   7;
        $tag->stroke_width =    8;
        $tag->point_radius =    9;
        $tag->point_image =     '10';
        $tag->max_zoom =        11;
        $tag->min_zoom =        12;
        $tag->save();

        // Set tag reference.
        $record->tag_id = $tag->id;

        // Mock values:
        $values = array(
            'vector_color'      => '13',
            'stroke_color'      => '14',
            'select_color'      => '15',
            'vector_opacity'    => 16,
            'select_opacity'    => 17,
            'stroke_opacity'    => 18,
            'image_opacity'     => 19,
            'stroke_width'      => 20,
            'point_radius'      => 21,
            'point_image'       => '22',
            'max_zoom'          => 23,
            'min_zoom'          => null
        );

        // Starting tags count.
        $startCount = $this->_tagsTable->count();

        // Set local styles.
        $record->setLocalStyles($values);

        // Check tags+0.
        $this->assertEquals($startCount, $this->_tagsTable->count());

        // Check updated tag.
        $tag = $this->getLastTag();
        $this->assertEquals($record->tag_id,        $tag->id);
        $this->assertEquals($tag->vector_color,     '13');
        $this->assertEquals($tag->stroke_color,     '14');
        $this->assertEquals($tag->select_color,     '15');
        $this->assertEquals($tag->vector_opacity,   16);
        $this->assertEquals($tag->select_opacity,   17);
        $this->assertEquals($tag->stroke_opacity,   18);
        $this->assertEquals($tag->image_opacity,    19);
        $this->assertEquals($tag->stroke_width,     20);
        $this->assertEquals($tag->point_radius,     21);
        $this->assertEquals($tag->point_image,      '22');
        $this->assertEquals($tag->max_zoom,         23);

        // Null values unset in tag.
        $this->assertNull($tag->min_zoom);

    }


}
