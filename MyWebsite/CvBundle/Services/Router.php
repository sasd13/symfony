<?php

namespace MyWebsite\CvBundle\Services;

class Router
{
	const ROUTE_CV_LIST = 'cv_list';
	const ROUTE_CV_LOAD = 'cv_load';
	
	const ROUTE_CV_PROFILE_LIST = 'cv_profile_list';
	const ROUTE_CV_PROFILE_NEW = 'cv_profile_new';
	const ROUTE_CV_PROFILE_EDIT = 'cv_profile_edit';
	const ROUTE_CV_PROFILE_DELETE = 'cv_profile_delete';
	
	const ROUTE_CV_PROFILE_CATEGORY_NEW = 'cv_profile_category_new';
	const ROUTE_CV_PROFILE_CATEGORY_EDIT = 'cv_profile_category_edit';
	const ROUTE_CV_PROFILE_CATEGORY_DELETE = 'cv_profile_category_delete';
	
	const ROUTE_CV_PROFILE_CATEGORY_CONTENT_NEW = 'cv_profile_category_content_new';
	const ROUTE_CV_PROFILE_CATEGORY_CONTENT_EDIT = 'cv_profile_category_content_edit';
	const ROUTE_CV_PROFILE_CATEGORY_CONTENT_DELETE = 'cv_profile_category_content_delete';
}
