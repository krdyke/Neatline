<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 cc=76; */

/**
 * Tests for edit action in exhibits controller.
 *
 * @package     omeka
 * @subpackage  neatline
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */

class Neatline_ExhibitsControllerTest_Edit
    extends Neatline_Test_AppTestCase
{


    /**
     * /edit/:id should display the edit form populated with values.
     */
    public function testBaseMarkup()
    {

        $exhibit = $this->__exhibit('slug');
        $exhibit->title         = 'title';
        $exhibit->description   = 'description';
        $exhibit->public        = 1;
        $exhibit->save();

        // /edit:id.
        $this->dispatch('neatline/edit/'.$exhibit->id);

        // Title:
        $this->assertXpath(
            '//input[@name="title"][@value="title"]'
        );

        // Description:
        $this->assertXpathContentContains(
            '//textarea[@name="description"]',
            'description'
        );

        // Slug:
        $this->assertXpath(
            '//input[@name="slug"][@value="slug"]'
        );

        // Public.
        $this->assertXpath(
            '//input[@name="public"][@checked="checked"]'
        );

    }


    /**
     * Edit form should require a title.
     */
    public function testNoTitleError()
    {

        $exhibit = $this->__exhibit();
        $exhibit->title = "title";
        $exhibit->save();

        // Missing title.
        $this->request->setMethod('POST')->setPost(array(
            'title' => ''
        ));

        // Submit the form.
        $this->dispatch('neatline/edit/'.$exhibit->id);
        $this->assertAction('edit');

        // Should flash error.
        $this->assertXpath('//input[@name="title"]/following-sibling::
            ul[@class="error"]'
        );

        // Should not save exhibit.
        $exhibit = $this->_exhibitsTable->find($exhibit->id);
        $this->assertEquals($exhibit->title, 'title');

    }


    /**
     * Edit form should require a slug.
     */
    public function testNoSlugError()
    {

        $exhibit = $this->__exhibit('slug');

        // Missing slug:
        $this->request->setMethod('POST')->setPost(array(
            'slug' => ''
        ));

        // Submit the form.
        $this->dispatch('neatline/edit/'.$exhibit->id);
        $this->assertAction('edit');

        // Should flash error.
        $this->assertXpath('//input[@name="slug"]/following-sibling::
            ul[@class="error"]'
        );

        // Should not save exhibit.
        $exhibit = $this->_exhibitsTable->find($exhibit->id);
        $this->assertEquals($exhibit->slug, 'slug');

    }


    /**
     * Edit form should block a slug with spaces.
     */
    public function testInvalidSlugWithSpacesError()
    {

        $exhibit = $this->__exhibit('slug');

        // Spaces:
        $this->request->setMethod('POST')->setPost(array(
            'slug' => 'slug with spaces'
        ));

        // Submit the form.
        $this->dispatch('neatline/edit/'.$exhibit->id);
        $this->assertAction('edit');

        // Should flash error.
        $this->assertXpath('//input[@name="slug"]/following-sibling::
            ul[@class="error"]'
        );

        // Should not save exhibit.
        $exhibit = $this->_exhibitsTable->find($exhibit->id);
        $this->assertEquals($exhibit->slug, 'slug');

    }


    /**
     * Edit form should block a slug with capitals.
     */
    public function testInvalidSlugWithCapsError()
    {

        $exhibit = $this->__exhibit('slug');

        // Capitals:
        $this->request->setMethod('POST')->setPost(array(
            'slug' => 'Slug-With-Capitals'
        ));

        // Submit the form.
        $this->dispatch('neatline/edit/'.$exhibit->id);
        $this->assertAction('edit');

        // Should flash error.
        $this->assertXpath('//input[@name="slug"]/following-sibling::
            ul[@class="error"]'
        );

        // Should not save exhibit.
        $exhibit = $this->_exhibitsTable->find($exhibit->id);
        $this->assertEquals($exhibit->slug, 'slug');

    }


    /**
     * Edit form should block a slug with non-alphanumeric characters.
     */
    public function testInvalidSlugWithNonAlphasError()
    {

        $exhibit = $this->__exhibit('slug');

        // Non-alphanumerics:
        $this->request->setMethod('POST')->setPost(array(
            'slug' => 'slug#with%non&alphas!'
        ));

        // Submit the form.
        $this->dispatch('neatline/edit/'.$exhibit->id);
        $this->assertAction('edit');

        // Should flash error.
        $this->assertXpath('//input[@name="slug"]/following-sibling::
            ul[@class="error"]'
        );

        // Should not save exhibit.
        $exhibit = $this->_exhibitsTable->find($exhibit->id);
        $this->assertEquals($exhibit->slug, 'slug');

    }

    /**
     * Edit form should block a duplicate slug.
     */
    public function testDuplicateSlugError()
    {

        $exhibit1 = $this->__exhibit('slug-1');
        $exhibit2 = $this->__exhibit('slug-2');

        // Duplicate slug.
        $this->request->setMethod('POST')->setPost(array(
            'slug' => 'slug-2'
        ));

        // Submit the form.
        $this->dispatch('neatline/edit/'.$exhibit1->id);
        $this->assertAction('edit');

        // Should flash error.
        $this->assertXpath('//input[@name="slug"]/following-sibling::
            ul[@class="error"]'
        );

        // Should not save exhibit.
        $exhibit1 = $this->_exhibitsTable->find($exhibit1->id);
        $this->assertEquals($exhibit1->slug, 'slug-1');

    }

    /**
     * Edit form should not block an unchanged slug.
     */
    public function testUnchangedSlug()
    {

        $exhibit = $this->__exhibit('slug');

        // Unchanged slug.
        $this->request->setMethod('POST')->setPost(array(
            'title' => 'title',
            'slug'  => 'slug'
        ));

        // Submit the form.
        $this->dispatch('neatline/edit/'.$exhibit->id);

        // Should save exhibit.
        $exhibit = $this->_exhibitsTable->find($exhibit->id);
        $this->assertEquals($exhibit->title, 'title');

    }


    /**
     * Edit form should block empty base layers.
     */
    public function testNoLayersError()
    {

    }


    /**
     * Edit form should update an exhibit when a valid title, description,
     * and slug are provided.
     */
    public function testSuccess()
    {

        $exhibit = $this->__exhibit();

        // Valid form.
        $this->request->setMethod('POST')->setPost(array(
            'title'         => 'title2',
            'description'   => 'desc2',
            'slug'          => 'slug2',
            'public'        => 1
        ));

        // Submit the form, reload exhibit.
        $this->dispatch('neatline/edit/'.$exhibit->id);
        $exhibit = $this->_exhibitsTable->find($exhibit->id);

        // Should save fields.
        $this->assertEquals($exhibit->title,        'title2');
        $this->assertEquals($exhibit->description,  'desc2');
        $this->assertEquals($exhibit->slug,         'slug2');
        $this->assertEquals($exhibit->public,       1);

    }


}
