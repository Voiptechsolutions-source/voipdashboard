@extends('layouts.app')

@section('title', 'Dashboard')

//@section('content')
        <div class="pagetitle">
      <h1>Dashboard</h1>
    </div>                                                                                                                                                                                                                                                                                                           

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-md-12">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" data-category="new-leads">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item active" href="#" data-filter="today">Today</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="month">This Month</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="year">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title new-leads">New Lead <span>| Today</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-cart"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="newLeadsCount"></h6>
                      <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" data-category="revenue">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item active" href="#" data-filter="today">Today</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="month">This Month</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="year">This Year</a></li>
                </div>

                <div class="card-body">
                  <h5 class="card-title revenue">Revenue <span>| This Today</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-currency-rupee"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="totalRevenue"><?php //echo $totalRevenue; ?></h6>
                      <!-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-4 col-xl-12">

              <div class="card info-card customers-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" data-category="total-customers">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item active" href="#" data-filter="today">Today</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="month">This Month</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="year">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title total-customers">Customers <span>| This Today</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="totalCustomersCount"></h6>
                      <!-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->

                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->

            <!-- Reports -->
            <div class="col-12">
              <div class="card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Reports <span>/Today</span></h5>

                    <div id="reportsChart"></div>

              </div>
            </div>
          </div>
        <!-- End Reports -->
            <!-- Recent Sales -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Recent Sales <span>| Today</span></h5>

                  <div class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns"><div class="datatable-top">
    <div class="datatable-dropdown">
            <label>
                <select class="datatable-selector" name="per-page"><option value="5">5</option><option value="10" selected="">10</option><option value="15">15</option><option value="-1">All</option></select> entries per page
            </label>
        </div>
    <div class="datatable-search">
            <input class="datatable-input" placeholder="Search..." type="search" name="search" title="Search within table">
        </div>
</div>
<div class="datatable-container"><table class="table table-borderless datatable datatable-table"><thead><tr><th scope="col" data-sortable="true" style="width: 10.714285714285714%;"><button class="datatable-sorter">#</button></th><th scope="col" data-sortable="true" style="width: 23.511904761904763%;"><button class="datatable-sorter">Customer</button></th><th scope="col" data-sortable="true" style="width: 39.285714285714285%;"><button class="datatable-sorter">Product</button></th><th scope="col" data-sortable="true" style="width: 11.755952380952381%;"><button class="datatable-sorter">Price</button></th><th scope="col" data-sortable="true" class="red" style="width: 14.732142857142858%;"><button class="datatable-sorter">Status</button></th></tr></thead><tbody><tr data-index="0"><td scope="row"><a href="#">#2457</a></td><td>Brandon Jacob</td><td><a href="#" class="text-primary">At praesentium minu</a></td><td>$64</td><td class="green"><span class="badge bg-success">Approved</span></td></tr><tr data-index="1"><td scope="row"><a href="#">#2147</a></td><td>Bridie Kessler</td><td><a href="#" class="text-primary">Blanditiis dolor omnis similique</a></td><td>$47</td><td class="green"><span class="badge bg-warning">Pending</span></td></tr><tr data-index="2"><td scope="row"><a href="#">#2049</a></td><td>Ashleigh Langosh</td><td><a href="#" class="text-primary">At recusandae consectetur</a></td><td>$147</td><td class="green"><span class="badge bg-success">Approved</span></td></tr><tr data-index="3"><td scope="row"><a href="#">#2644</a></td><td>Angus Grady</td><td><a href="#" class="text-primar">Ut voluptatem id earum et</a></td><td>$67</td><td class="green"><span class="badge bg-danger">Rejected</span></td></tr><tr data-index="4"><td scope="row"><a href="#">#2644</a></td><td>Raheem Lehner</td><td><a href="#" class="text-primary">Sunt similique distinctio</a></td><td>$165</td><td class="green"><span class="badge bg-success">Approved</span></td></tr></tbody></table></div>
<div class="datatable-bottom">
    <div class="datatable-info">Showing 1 to 5 of 5 entries</div>
    <nav class="datatable-pagination"><ul class="datatable-pagination-list"></ul></nav>
</div></div>

                </div>

              </div>
            </div><!-- End Recent Sales -->

            

          </div>
        </div><!-- End Left side columns -->

        

      </div>
    
@endsection


