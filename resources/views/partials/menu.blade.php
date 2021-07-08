<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">{{ trans('panel.site_title') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                @can('user_management_access')
                    <li class="nav-item has-treeview {{ request()->is('admin/permissions*') ? 'menu-open' : '' }} {{ request()->is('admin/roles*') ? 'menu-open' : '' }} {{ request()->is('admin/users*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw fas fa-users">

                            </i>
                            <p>
                                <span>{{ trans('cruds.userManagement.title') }}</span>
                                <i class="right fa fa-fw fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-unlock-alt">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.permission.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-briefcase">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.role.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-user">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.user.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('push_access')
                    <li class="nav-item has-treeview {{ request()->is('admin/push*') ? 'menu-open' : '' }} ">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw fas fa-users"></i>
                            <p>
                                <span>Пуш-Сетка</span>
                                <i class="right fa fa-fw fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ route("admin.push-templates.index") }}" class="nav-link {{ request()->is('admin/push-templates') || request()->is('admin/push-templates/*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-unlock-alt"></i>
                                    <p>
                                        <span>Кампании</span>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("admin.push-clients.index") }}" class="nav-link {{ request()->is('admin.push-clients') || request()->is('admin.push-clients/*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-unlock-alt"></i>
                                    <p>
                                        <span>Клиенты</span>
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('news_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.news.index") }}" class="nav-link {{ request()->is('admin/news') || request()->is('admin/news/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-rss">

                            </i>
                            <p>
                                <span>{{ trans('cruds.news.title') }}</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('tizer_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.tizers.index") }}" class="nav-link {{ request()->is('admin/tizers') || request()->is('admin/tizers/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-cogs">

                            </i>
                            <p>
                                <span>{{ trans('cruds.tizer.title') }}</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('tizer_result_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.tizers-result.index") }}" class="nav-link {{ request()->is('admin/tizers-result') || request()->is('admin/tizers-result/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-cogs">

                            </i>
                            <p>
                                <span>Выдача Тизеров</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('tizer_test_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.tizers-test.index") }}" class="nav-link {{ request()->is('admin/tizers-test') || request()->is('admin/tizers-test/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-cogs">

                            </i>
                            <p>
                                <span>АБ тестирование</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('tizer_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.news-result.index") }}" class="nav-link {{ request()->is('admin/news-result') || request()->is('admin/news-result/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-cogs">

                            </i>
                            <p>
                                <span>Выдача Новостей</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('category_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.categories.index") }}" class="nav-link {{ request()->is('admin/categories') || request()->is('admin/categories/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-list">

                            </i>
                            <p>
                                <span>{{ trans('cruds.category.title') }}</span>
                            </p>
                        </a>
                    </li>
                @endcan

                @can('template_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.templates.index") }}" class="nav-link {{ request()->is('admin/templates') || request()->is('admin/templates/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-surprise">

                            </i>
                            <p>
                                <span>{{ trans('cruds.template.title') }}</span>
                            </p>
                        </a>
                    </li>
                @endcan

                @can('domain_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.domains.index") }}" class="nav-link {{ request()->is('admin/domains') || request()->is('admin/domains/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-globe">

                            </i>
                            <p>
                                <span>Домены</span>
                            </p>
                        </a>
                    </li>
                @endcan

                @can('source_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.sources.index") }}" class="nav-link {{ request()->is('admin/sources') || request()->is('admin/sources/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-cogs">

                            </i>
                            <p>
                                <span>Источники</span>
                            </p>
                        </a>
                    </li>
                @endcan

                @can('scripts_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.scripts.index") }}" class="nav-link {{ request()->is('admin/scripts') || request()->is('admin/scripts/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-cogs">

                            </i>
                            <p>
                                <span>Скрипты</span>
                            </p>
                        </a>
                    </li>
                @endcan

                @can('stats_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.stats.index") }}" class="nav-link {{ request()->is('admin/stats') || request()->is('admin/stats/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-cogs">

                            </i>
                            <p>
                                <span>Статистика</span>
                            </p>
                        </a>
                    </li>
                @endcan

                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt">

                            </i>
                            <span>{{ trans('global.logout') }}</span>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>