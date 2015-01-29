<?php

namespace MyWebsite\CvBundle\Services;

class Layouter
{
	const LAYOUT_CV_LIST = 'MyWebsiteCvBundle::cv-list.html.twig';
	const LAYOUT_CV_LOAD = 'MyWebsiteCvBundle::cv.html.twig';
	
	const LAYOUT_CV_PROFILE = 'MyWebsiteCvBundle:Profile:cv.html.twig';
	
	const SUBLAYOUT_CV_PROFILE_LIST = 'SubLayout/cv-list';
	const SUBLAYOUT_CV_PROFILE_NEW = 'SubLayout/cv-new';
	const SUBLAYOUT_CV_PROFILE_EDIT = 'SubLayout/cv-edit';
}
