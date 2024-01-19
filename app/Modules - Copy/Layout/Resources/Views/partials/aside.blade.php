@inject('request', 'Illuminate\Http\Request')
@php
	//dd(auth()->user()->id);
@endphp
<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				@if ( auth()->user()->getMedia('avatar')->count() > 0)
					<img src="{{ asset(auth()->user()->getMedia('avatar')->first()->getUrl()) }}" class="img-circle">
				@else
		  			<img src="{{ asset('images/default-avatar.png') }}" class="img-circle">
				@endif
			</div>
			<div class="pull-left info">
		  		<p>{{ auth()->user()->name }}</p>
		  		<a href="{{ route('admin.profile.index') }}">{{ Lang::get('layout::admin.user_info') }}</a>
			</div>
	  	</div>

	  	<!-- {{ Form::open(['url' => route('admin.search.index'), 'class' => 'sidebar-form', 'method' => 'GET']) }}
		<div class="input-group">
			<input type="text" name="q" class="form-control" placeholder="Search Module..">
	  		<span class="input-group-btn">
				<button type="submit" name="search" id="search-btn" class="btn btn-flat">
					<i class="fa fa-search"></i>
				</button>
		 	</span>
		</div>
	  	{{ Form::close() }} -->

	  	<ul class="sidebar-menu" data-widget="tree">

			<!-- <li class="header">MAIN NAVIGATION</li> -->
			<li class="header">{{ Lang::get('layout::admin.cms_name') }}</li>


			<li class="treeview {{ $request->segment(2) == 'ncds-setting' || $request->segment(2) == 'ncds-2' || $request->segment(2) == 'ncds-4' || $request->segment(2) == 'ncds-6' ? 'active' : '' }}">
				<a href="#">
					<i class="fa fa-newspaper-o"></i>
					<span>{{ Lang::get('layout::admin.ncds') }}</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
			  	</a>
			  	<ul class="treeview-menu">
					<li class="{{ $request->segment(2) == 'ncds-setting' ? 'active' : '' }}">
						<a href="{{ route('admin.ncds_setting.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.setting_ncds') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'ncds-1' ? 'active' : '' }}">
						<a href="{{ route('admin.ncds-1.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.ncds_1') }}</a>
					</li>					
					<li class="{{ $request->segment(2) == 'ncds-2' ? 'active' : '' }}">
						<a href="{{ route('admin.ncds-2.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.ncds_2') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'ncds-4' ? 'active' : '' }}">
						<a href="{{ route('admin.ncds-4.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.ncds_4') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'ncds-6' ? 'active' : '' }}">
						<a href="{{ route('admin.ncds-6.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.ncds_6') }}</a>
					</li>					
			  	</ul>
			</li>			

			@can('manage-news-events')
			<li class="{{ $request->segment(2) == 'article' ? 'active' : '' }}">
				<a href="{{ route('admin.article.index') }}">
					<i class="fa fa-newspaper-o"></i>
					<span>{{ Lang::get('layout::admin.news_events') }}</span>
			  	</a>
			</li>
			@endcan

			@can('manage-single-page')
			<li class="{{ $request->segment(2) == 'single-page' ? 'active' : '' }}">
				<a href="{{ route('admin.single-page.index') }}">
					<i class="fa fa-newspaper-o"></i>
					<span>{{ Lang::get('layout::admin.single-page') }}</span>
			  	</a>
			</li>
			@endcan

			@can('manage-articles-research')
			<li class="{{ $request->segment(2) == 'articles-research' ? 'active' : '' }}">
				<a href="{{ route('admin.articles-research.index') }}">
					<i class="fa fa-newspaper-o"></i>
					<span>{{ Lang::get('layout::admin.articles_research') }}</span>
			  	</a>
			</li>
			@endcan

			@can('manage-include-statistics')
			<li class="{{ $request->segment(2) == 'include-statistics' ? 'active' : '' }}">
				<a href="{{ route('admin.include-statistics.index') }}">
					<i class="fa fa-newspaper-o"></i>
					<span>{{ Lang::get('layout::admin.include_statistics') }}</span>
			  	</a>
			</li>
			@endcan

			@can('manage-service')
			<li class="treeview {{ $request->segment(2) == 'service' ? 'active' : '' }}">
				<a href="#">
					<i class="fa fa-newspaper-o"></i>
					<span>{{ Lang::get('layout::admin.service') }}</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
			  	</a>
			  	<ul class="treeview-menu">
					<li class="{{ $request->segment(2) == 'our-service' ? 'active' : '' }}">
						<a href="{{ route('admin.our-service.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.our_service') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'sook-library' ? 'active' : '' }}">
						<a href="{{ route('admin.sook-library.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.sook_library') }}</a>
					</li>
			  	</ul>
			</li>
			@endcan
			

			@can('manage-training-course')
			<li class="treeview {{ $request->segment(2) == 'training-course' ? 'active' : '' }}">
				<a href="#">
					<i class="fa fa-newspaper-o"></i>
					<span>{{ Lang::get('layout::admin.training_course') }}</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
			  	</a>
			  	<ul class="treeview-menu">
					<li class="{{ $request->segment(2) == 'training-course' ? 'active' : '' }}">
						<a href="{{ route('admin.training-course.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.training_course') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'e-learning' ? 'active' : '' }}">
						<a href="{{ route('admin.e-learning.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.e_Learning') }}</a>
					</li>
			  	</ul>
			</li>
			@endcan

			@can('manage-interesting-issues')
			<li class="{{ $request->segment(2) == 'interesting-issues' ? 'active' : '' }}">
				<a href="{{ route('admin.interesting-issues.index') }}">
					<i class="fa fa-newspaper-o"></i>
					<span>{{ Lang::get('layout::admin.interesting-issues') }}</span>
			  	</a>
			</li>
			@endcan

			

			@can('manage-health-literacy')
			<li class="treeview {{ $request->segment(2) == 'health-literacy' || $request->segment(2) == 'health-literacy-category' || $request->segment(2) == 'health-literacy-main' ? 'active' : '' }}">
				<a href="#">
					<i class="fa fa-newspaper-o"></i>
					<span>{{ Lang::get('layout::admin.health-literacy') }}</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
			  	</a>
			  	<ul class="treeview-menu">
					<li class="{{ $request->segment(2) == 'health-literacy-category' ? 'active' : '' }}">
						<a href="{{ route('admin.health-literacy-category.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.health-literacy-category') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'health-literacy' ? 'active' : '' }}">
						<a href="{{ route('admin.health-literacy.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.health-literacy') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'health-literacy-main' ? 'active' : '' }}">
						<a href="{{ route('admin.health-literacy-main.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.health-literacy-main') }}</a>
					</li>
			  	</ul>
			</li>
			@endcan

			@can('manage-thaihealth-watch')
			<li class="{{ $request->segment(2) == 'thaihealth-watch' ? 'active' : '' }}">
				<a href="{{ route('admin.thaihealth-watch.index') }}">
					<i class="fa fa-newspaper-o"></i>
					<span>{{ Lang::get('layout::admin.thaihealth-watch') }}</span>
			  	</a>
			</li>
			@endcan

			@can('manage-learning-area-creates-direct-experience')
			<li class="{{ $request->segment(2) == 'learning-area-creates-direct-experience' ? 'active' : '' }}">
				<a href="{{ route('admin.learning-area-creates-direct-experience.index') }}">
					<i class="fa fa-newspaper-o"></i>
					<span>{{ Lang::get('layout::admin.learning-area-creates-direct-experience') }}</span>
			  	</a>
			</li>
			@endcan

			@can('manage-event-calendar')
			<li class="{{ $request->segment(2) == 'event-calendar' ? 'active' : '' }}">
				<a href="{{ route('admin.event-calendar.index') }}">
					<i class="fa fa-newspaper-o"></i>
					<span>{{ Lang::get('layout::admin.event-calendar') }}</span>
			  	</a>
			</li>
			@endcan

			@can('manage-exhibition')

			<li class="treeview {{ $request->segment(2) == 'exhibition' ? 'active' : '' }}">
					<a href="#">
						<i class="fa fa-newspaper-o"></i>
						<span>{{ Lang::get('layout::admin.exhibition') }}</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">

						<li class="{{ $request->segment(2) == 'exhibition' && $request->segment(3) == 'master' ? 'active' : '' }}">
							<a href="{{ route('admin.exhibition.master.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.exhibition_master') }}</a>
						</li>

						<li class="{{ $request->segment(2) == 'exhibition' ? 'active' : '' }}">
							<a href="{{ route('admin.exhibition.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.exhibition') }}</a>
						</li>

						<li class="{{ $request->segment(2) == 'book-an-exhibition' ? 'active' : '' }}">
							<a href="{{ route('admin.book-an-exhibition.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.book_an_exhibition') }}</a>
						</li>



						<!--<li class="{{ $request->segment(2) == 'exhibition' && $request->segment(3) == 'online' ? 'active' : '' }}">
							<a href="{{ route('admin.exhibition.online.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.online_exhibition') }}</a>
						</li>

						<li class="{{ $request->segment(2) == 'exhibition' && $request->segment(3) == 'revolving' ? 'active' : '' }}">
							<a href="{{ route('admin.exhibition.revolving.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.revolving_exhibition') }}</a>
						</li>

						<li class="{{ $request->segment(2) == 'exhibition' && $request->segment(3) == 'permanent' ? 'active' : '' }}">
							<a href="{{ route('admin.exhibition.permanent.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.permanent_exhibition') }}</a>
						</li>

						<li class="{{ $request->segment(2) == 'exhibition' && $request->segment(3) == 'traveling' ? 'active' : '' }}">
							<a href="{{ route('admin.exhibition.traveling.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.traveling_exhibition') }}</a>
						</li>

						<li class="{{ $request->segment(2) == 'exhibition' && $request->segment(3) == 'borrowed' ? 'active' : '' }}">
							<a href="{{ route('admin.exhibition.borrowed.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.exhibition_borrowed') }}</a>
						</li>-->

					</ul>
			</li>		

			@endcan

			@can('manage-api')
			<li class="treeview {{ $request->segment(2) == 'api' ? 'active' : '' }}">
				<a href="#">
					<i class="fa fa-cogs"></i>
					<span>{{ Lang::get('layout::admin.api') }}</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
			  	</a>
			  	<ul class="treeview-menu">
					<li class="{{ $request->segment(2) == 'api' && $request->segment(3) == 'list-media' ? 'active' : '' }}">
						<a href="{{ route('admin.api.list-media.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.media') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'api' && $request->segment(3) == 'list-category' ? 'active' : '' }}">
						<a href="{{ route('admin.api.list-category.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.category') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'api' && $request->segment(3) == 'list-issue' ? 'active' : '' }}">
						<a href="{{ route('admin.api.list-issue.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.issue') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'api' && $request->segment(3) == 'list-target' ? 'active' : '' }}">
						<a href="{{ route('admin.api.list-target.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.target') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'api' && $request->segment(3) == 'list-setting' ? 'active' : '' }}">
						<a href="{{ route('admin.api.list-setting.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.setting') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'api' && $request->segment(3) == 'list-area' ? 'active' : '' }}">
						<a href="{{ route('admin.api.list-area.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.area') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'api' && $request->segment(3) == 'list-province' ? 'active' : '' }}">
						<a href="{{ route('admin.api.list-province.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.province') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'api' && $request->segment(3) == 'sex' ? 'active' : '' }}">
						<a href="{{ route('admin.sex.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.sex') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'api' && $request->segment(3) == 'age' ? 'active' : '' }}">
						<a href="{{ route('admin.age.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.age') }}</a>
					</li>										
			  	</ul>
			</li>
			@endcan


			@can('manage-request-media')
			<li class="treeview {{ $request->segment(2) == 'request-media' ? 'active' : '' }}">
				<a href="#">
					<i class="fa fa-newspaper-o"></i>
					<span>{{ Lang::get('layout::admin.request_media') }}</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
			  	</a>
			  	<ul class="treeview-menu">
					<li class="{{ $request->segment(2) == 'request-media' && $request->segment(3) == '' ? 'active' : '' }}">
						<a href="{{ route('admin.request-media.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.request_media') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'request-media' && ($request->segment(3) == 'detail' || $request->segment(4) == 'create' || $request->segment(4) == 'edit') ? 'active' : '' }}">
						<a href="{{ route('admin.request-media-detail.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.request_media_detail') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'request-media' && ($request->segment(3) == 'email' || $request->segment(4) == 'create' || $request->segment(4) == 'edit') ? 'active' : '' }}">
						<a href="{{ route('admin.request-media-email.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.request_media_email') }}</a>
					</li>
			  	</ul>
			</li>
			@endcan


			@can('manage-report')
			<li class="treeview {{ $request->segment(2) == 'report' ? 'active' : '' }}">
				<a href="#">
					<i class="fa fa-bar-chart"></i>
					<span>{{ Lang::get('layout::admin.report') }}</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
			  	</a>
			  	<ul class="treeview-menu">
					<li class="{{ $request->segment(2) == 'report' && $request->segment(3) == 'article' ? 'active' : '' }}">
						<a href="{{ route('report.article') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.request_article') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'report' && ($request->segment(3) == 'media' || $request->segment(4) == 'create' || $request->segment(4) == 'edit') ? 'active' : '' }}">
						<a href="{{ route('report.media') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.media') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'report' && ($request->segment(3) == 'logs' || $request->segment(4) == 'create' || $request->segment(4) == 'edit') ? 'active' : '' }}">
						<a href="{{ route('report.logs') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.request_logs') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'report' && ($request->segment(3) == 'api' || $request->segment(4) == 'create' || $request->segment(4) == 'edit') ? 'active' : '' }}">
						<a href="{{ route('report.logs.api') }}" target="_blank"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.log_api') }}</a>
					</li>					
			  	</ul>
			</li>
			@endcan


			@can('manage-contact')
			<li class="treeview {{ $request->segment(2) == 'contact' ? 'active' : '' }}">
				<a href="#">
					<i class="fa fa-phone"></i>
					<span>{{ Lang::get('layout::admin.contact_us') }}</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
			  	</a>
			  	<ul class="treeview-menu">
					<li class="{{ $request->segment(2) == 'contact' ? 'active' : '' }}">
						<a href="{{ route('admin.contact.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.contact_us') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'contact' && ($request->segment(3) == 'subject' || $request->segment(4) == 'create' || $request->segment(4) == 'edit') ? 'active' : '' }}">
						<a href="{{ route('admin.contact.subject.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.subject') }}</a>
					</li>

			  	</ul>
			</li>
			@endcan


			@can('manage-users')
			<li class="treeview
				{{ $request->segment(2) == 'abilities' || $request->segment(2) == 'users' || $request->segment(2) == 'roles' ? 'active' : '' }}
				">
				<a href="#">
					<i class="fa fa-users"></i>
					<span>{{ Lang::get('layout::admin.user_group') }}</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
			  	</a>
			  	<ul class="treeview-menu">
					<li class="{{ $request->segment(2) == 'users' && ($request->segment(3) == '' || $request->segment(3) == 'create' || $request->segment(3) == 'edit') ? 'active' : '' }}">
						<a href="{{ route('admin.users.index') }}"><i class="fa fa-circle-o"></i>{{ Lang::get('layout::admin.users') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'roles' ? 'active' : '' }}">
						<a href="{{ route('admin.roles.index') }}"><i class="fa fa-circle-o"></i> {{ Lang::get('layout::admin.group') }}</a>
					</li>
					<!-- <li class="{{ $request->segment(2) == 'abilities' ? 'active' : '' }}">
						<a href="{{ route('admin.abilities.index') }}"><i class="fa fa-briefcase"></i> Permission</a>
					</li> -->
			  	</ul>
			</li>
			@endcan

			@can('manage-menu')
			<li class="{{ $request->segment(2) == 'menus' ? 'active' : '' }}">
				<a href="{{ route('admin.menus.index') }}">
					<i class="fa fa-th"></i>
					<span>{{ Lang::get('layout::admin.menu') }}</span>
			  	</a>
			</li>
			@endcan

			@can('manage-footermenu')
			<li class="treeview {{ $request->segment(2) == 'footermenus' ? 'active' : '' }}">
			  	<a href="#">
					<i class="fa fa-th"></i>
					<span>{{ Lang::get('layout::admin.footer_menu') }}</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
			  	</a>
			  	<ul class="treeview-menu">
					<li class="{{ $request->segment(2) == 'footermenus' && $request->segment(3) == 'left'  && ($request->segment(4) == '' || $request->segment(4) == 'create' || $request->segment(4) == 'edit') ? 'active' : '' }}">
						<a href="{{ route('admin.footermenus.left.index') }}"><i class="fa fa-circle-o"></i> {{ Lang::get('layout::admin.footer_menu_left') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'footermenus' && $request->segment(3) == 'right'  && ($request->segment(4) == '' || $request->segment(4) == 'create' || $request->segment(4) == 'edit') ? 'active' : '' }}">
						<a href="{{ route('admin.footermenus.right.index') }}"><i class="fa fa-circle-o"></i> {{ Lang::get('layout::admin.footer_menu_right') }}</a>
					</li>
			  	</ul>
			</li>
			@endcan


			@can('manage-banner')
			<li class="treeview {{ $request->segment(2) == 'banner' ? 'active' : '' }}">
			  	<a href="#">
					<i class="fa fa-image"></i>
					<span>{{ Lang::get('layout::admin.banner') }}</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
			  	</a>
			  	<ul class="treeview-menu">
					<li class="{{ $request->segment(2) == 'banner' && $request->segment(3) == '' ? 'active' : '' }}">
						<a href="{{ route('admin.banner.index') }}"><i class="fa fa-circle-o"></i> {{ Lang::get('layout::admin.banner') }}</a>
					</li>
					<li class="{{ $request->segment(2) == 'banner' && $request->segment(3) == 'category' ? 'active' : '' }}">
						<a href="{{ route('admin.banner.category.index') }}"><i class="fa fa-circle-o"></i> {{ Lang::get('layout::admin.category_of_banner') }}</a>
					</li>
			  	</ul>
			</li>
			@endcan


			@can('manage-setting')
			<li class="{{ $request->segment(2) == 'setting' ? 'active' : '' }}">
				<a href="{{ route('admin.setting.index') }}">
					<i class="fa fa-gear"></i>
					<span>{{ Lang::get('layout::admin.setting') }}</span>
			  	</a>
			</li>
			<li class="{{ $request->segment(2) == 'clear-cache' ? 'active' : '' }}">
				<a href="{{ route('admin.clear-cache') }}">
					<i class="fa fa-gear"></i>
					<span>{{ Lang::get('layout::admin.clear_cache') }}</span>
			  	</a>
			</li>
			@endcan

		</ul>
	</section>
</aside>
