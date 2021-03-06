<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\PageBundle\Tests\Model;

use Mautic\PageBundle\Entity\Page;
use Mautic\PageBundle\Tests\PageTestAbstract;
use ReflectionClass;

class PageModelTest extends PageTestAbstract
{
    public function testGenerateUrl_WhenCalled_ReturnsValidUrl()
    {
        $page = new Page();
        $page->setAlias('this-is-a-test');
        $pageModel = $this->getPageModel();
        $url       = $pageModel->generateUrl($page);
        $this->assertContains('/this-is-a-test', $url);
    }

    public function testCleanQuery_WhenCalled_ReturnsSafeAndValidData()
    {
        $pageModel           = $this->getPageModel();
        $pageModelReflection = new ReflectionClass(get_class($pageModel));
        $cleanQueryMethod    = $pageModelReflection->getMethod('cleanQuery');
        $cleanQueryMethod->setAccessible(true);
        $res = $cleanQueryMethod->invokeArgs($pageModel, [
            [
                'page_title'    => 'Mautic & PHP',
                'page_url'      => 'http://mautic.com/page/test?hello=world&lorem=ipsum',
                'page_language' => 'en',
            ],
        ]);
        $this->assertEquals($res, [
            'page_title'    => 'Mautic &#38; PHP',
            'page_url'      => 'http://mautic.com/page/test?hello=world&lorem=ipsum',
            'page_language' => 'en',
        ]);
    }
}
