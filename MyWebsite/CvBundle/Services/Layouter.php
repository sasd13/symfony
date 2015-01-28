<?php

namespace MyWebsite\CvBundle\Services;

class Layouter
{
	const LAYOUT_CV_LIST = 'MyWebsiteCvBundle::cv-list.html.twig';
	const LAYOUT_CV_LOAD = 'MyWebsiteCvBundle::cv.html.twig';
	
	const LAYOUT_CV_PROFILE = 'MyWebsiteCvBundle:Profile:profile.html.twig';
	
	const SUBLAYOUT_CV_PROFILE_LIST = 'SubLayout/cvs';
	const SUBLAYOUT_CV_PROFILE_NEW = 'SubLayout/cv-new';
	const SUBLAYOUT_CV_PROFILE_EDIT = 'SubLayout/cv-edit';
	
	const SUBLAYOUT_CV_PROFILE_CATEGORY_EDIT = 'SubLayout/cv-category-edit';
	
	const SUBLAYOUT_CV_PROFILE_MODEL_CATEGORY_CONTENT_EDIT = 'SubLayout/cv-category-content-edit';
}
