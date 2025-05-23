<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('company.dashboard') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold ms-2 text-capitalize" style="color: black">Stock ERP</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->routeIs('company.dashboard') ? 'active' : '' }}">
            <a href="{{ route('company.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        {{--  Master Menu  --}}
        <li class="menu-item {{ request()->routeIs('company.variation.*') || request()->routeIs('company.subcompany.*') || request()->routeIs('company.tax.*') || request()->routeIs('company.item.*') || request()->routeIs('company.vendor.*') || request()->routeIs('company.customer.*') || request()->routeIs('company.bank.*')  ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div class="text-truncate" data-i18n="Master">Master</div>
            </a>
            <ul class="menu-sub">
                @foreach([
                    ['route' => 'company.subcompany.index', 'text' => 'Sub Company'],
                    ['route' => 'company.variation.index', 'text' => 'Category'],
                    ['route' => 'company.item.index', 'text' => 'Item'],
                    ['route' => 'company.vendor.index', 'text' => 'Creditors'],
                    ['route' => 'company.customer.index', 'text' => 'Customer'],
                    ['route' => 'company.transport.index', 'text' => 'Transport'],
                ] as $mastermenu)
                    <li class="menu-item {{ request()->routeIs($mastermenu['route']) ? 'active' : '' }}">
                        <a href="{{ route($mastermenu['route']) }}" class="menu-link">
                            <i class="menu-icon tf-icons"></i>
                            <div data-i18n="{{ $mastermenu['text'] }}">{{ $mastermenu['text'] }}</div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>

        {{--  <li class="menu-item {{ request()->routeIs('company.bank.and.cash.') ? 'active' : '' }}">
            <a href="{{ route('company.bank.and.cash.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboard">Bank And Cash</div>
            </a>
        </li>  --}}

        {{--  Process menu  --}}
        <li class="menu-item {{ request()->routeIs(['company.purches.book.*', 'company.sales.book.*', 'company.receipt.book.voucher.*', 'company.payment.book.*']) ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div class="text-truncate" data-i18n="Master">Process</div>
            </a>
            <ul class="menu-sub">
                @foreach([
                    ['route' => 'company.purches.book.index', 'text' => 'Purchase Book'],
                    ['route' => 'company.purches.book.return.list', 'text' => 'Debit Note'],
                    ['route' => 'company.sales.book.index', 'text' => 'Sales Book'],
                    ['route' => 'company.sales.book.return.list', 'text' => 'Credit Note'],
                ] as $processmenu)
                    <li class="menu-item {{ request()->routeIs($processmenu['route']) ? 'active' : '' }}">
                        <a href="{{ route($processmenu['route']) }}" class="menu-link">
                            <i class="menu-icon tf-icons"></i>
                            <div data-i18n="{{ $processmenu['text'] }}">{{ $processmenu['text'] }}</div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>

         {{--  Report menu  --}}
         <li class="menu-item {{ request()->routeIs(['company.purches.report.*', 'company.sales.report.*', 'company.receipt.report.*', 'company.payment.report.*', 'company.payment.report.*', 'company.stock.report.*', 'company.gst.report.*']) ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div class="text-truncate" data-i18n="Master">Report</div>
            </a>
            <ul class="menu-sub">
                @foreach([
                    ['route' => 'company.purches.report.index', 'text' => 'Purchase Report'],
                    ['route' => 'company.sales.report.index', 'text' => 'Sales Report'],
                    ['route' => 'company.stock.report.index', 'text' => 'Stock Report'],
                ] as $reportmenu)
                    <li class="menu-item {{ request()->routeIs($reportmenu['route']) ? 'active' : '' }}">
                        <a href="{{ route($reportmenu['route']) }}" class="menu-link">
                            <i class="menu-icon tf-icons"></i>
                            <div data-i18n="{{ $reportmenu['text'] }}">{{ $reportmenu['text'] }}</div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>





    </ul>
</aside>
