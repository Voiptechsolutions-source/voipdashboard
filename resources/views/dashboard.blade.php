@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
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
              <div class="card report-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#" data-filter="today">Today</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="month">This Month</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="year">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body chart-container">
                  <h5 class="card-title">Reports <span>/Today</span></h5>

                    <canvas id="reportsChart"></canvas>

              </div>
            </div>
          </div>
        <!-- End Reports -->
        
    </section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
   document.addEventListener('DOMContentLoaded', function () {
    let dashboardChart;
    fetchDashboardData('today'); // Load default card data
    fetchReportsData('today'); // Load default reports data

    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function (event) {
            event.preventDefault();
            let filterValue = this.getAttribute('data-filter');
            
            let parentCard = this.closest('.info-card');

            if (parentCard) {
                let category = parentCard.querySelector('.dropdown-menu').getAttribute('data-category');
                fetchDashboardData(filterValue, category);
                parentCard.querySelector('.card-title span').innerText = `| ${this.innerText}`;
            } else {
                fetchReportsData(filterValue);
                let reportCard = document.querySelector('.report-card .card-title span');
                if (reportCard) {
                    reportCard.innerText = `/ ${this.innerText}`;
                }
            }

            this.closest('.dropdown-menu').querySelectorAll('.dropdown-item').forEach(el => el.classList.remove('active'));
            this.classList.add('active');
        });
    });

    function fetchDashboardData(filterValue, category = 'all') {
        fetch(`/dashboard/filter?filter=${filterValue}&category=${category}`)
            .then(response => response.json())
            .then(data => {
                if (category === 'all' || category === 'new-leads') {
                    document.getElementById('newLeadsCount').innerText = data.newLeads || 0;
                }
                if (category === 'all' || category === 'revenue') {
                    document.getElementById('totalRevenue').innerText = data.revenue || 0;
                }
                if (category === 'all' || category === 'total-customers') {
                    document.getElementById('totalCustomersCount').innerText = data.totalCustomers || 0;
                }
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    function fetchReportsData(filterValue) {
        fetch(`/dashboard/chart-data?filter=${filterValue}`)
            .then(response => response.json())
            .then(data => {
                updateChart(data);
            })
            .catch(error => console.error('Error fetching reports:', error));
    }

    function updateChart(data) {
    let cardBody = document.querySelector('.card-body.chart-container');

    if (!cardBody) {
        console.error("No .card-body.chart-container found!");
        return;
    }

    let chartContainer = document.getElementById('reportsChart');
    if (chartContainer) {
        chartContainer.remove();
    }

    let newCanvas = document.createElement('canvas');
    newCanvas.id = 'reportsChart';
    cardBody.appendChild(newCanvas);

    let ctx = newCanvas.getContext('2d');

    if (window.dashboardChart) {
        window.dashboardChart.destroy();
    }

    window.dashboardChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['New Leads', 'Revenue', 'Customers'],
            datasets: [{
                label: 'Report',
                data: [data.newLeads, data.revenue, data.totalCustomers],
                backgroundColor: ['#76acce', '#f3b032', '#3fb799'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}
});

</script>
<style>


@endsection

