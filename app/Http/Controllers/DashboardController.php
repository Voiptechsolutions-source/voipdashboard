<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConvertLead;
use App\Models\Support;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        return $this->fetchData('today', false); // Load the view
    }

    public function filter(Request $request)
    {
        $filter = $request->query('filter', 'today');
        $category = $request->query('category', 'all'); // Get category filter

        return $this->fetchData($filter, true, $category);
    }

    public function getChartData(Request $request)
    {
        try {
            $filter = $request->input('filter', 'month');

            if ($filter == "month") {
                $startDate = now()->startOfMonth()->timezone('UTC');
                $endDate = now()->endOfMonth()->timezone('UTC');
            } elseif ($filter == "year") {
                $startDate = now()->startOfYear()->timezone('UTC');
                $endDate = now()->endOfYear()->timezone('UTC');
            } else {
                $startDate = now()->startOfDay()->timezone('UTC');
                $endDate = now()->endOfDay()->timezone('UTC');
            }

            \Log::info("Fetching chart data from: $startDate to $endDate");

            // Aggregate Revenue
            $totalRevenue = Support::whereBetween('created_at', [$startDate, $endDate])
                ->sum('revenue_per_day');

            // Aggregate Customers
            $totalCustomers = Customer::where('status', 2)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // Aggregate Leads
            $newLeads = Customer::whereBetween('created_at', [$startDate, $endDate])
                ->count();

            return response()->json([
                'revenue' => $totalRevenue, 
                'totalCustomers' => $totalCustomers,
                'newLeads' => $newLeads,
            ]);

        } catch (\Exception $e) {
            \Log::error("Error in getChartData: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function fetchData($filter, $jsonResponse = false, $category = 'all')
    {
        try {
            // Convert all timestamps to UTC for consistency
            if ($filter == "month") {
                $startDate = now()->startOfMonth()->timezone('UTC');
                $endDate = now()->endOfMonth()->timezone('UTC');
            } elseif ($filter == "year") {
                $startDate = now()->startOfYear()->timezone('UTC');
                $endDate = now()->endOfYear()->timezone('UTC');
            } else { // Default: Today
                $startDate = now()->startOfDay()->timezone('UTC');
                $endDate = now()->endOfDay()->timezone('UTC');
            }

            // Log date range for debugging
            \Log::info("Fetching new leads from: $startDate to $endDate");

            // Get new leads (status = 2)
            $newLeads = ($category === 'all' || $category === 'new-leads')
                ? Customer::where('status', 2)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count()
                : null;

            // Get revenue
            $revenue = ($category === 'all' || $category === 'revenue')
                ? Support::whereBetween('created_at', [$startDate, $endDate])
                    ->sum('revenue_per_day')
                : null;

            // Get total customers
            $totalCustomers = ($category === 'all' || $category === 'total-customers')
                ? Customer::whereBetween('created_at', [$startDate, $endDate])->count()
                : null;

            if ($jsonResponse) {
                return response()->json([
                    'newLeads' => $newLeads,
                    'revenue' => number_format($revenue, 2),
                    'totalCustomers' => $totalCustomers
                ]);
            }

            return view('dashboard', compact('newLeads', 'revenue', 'totalCustomers', 'filter'));

        } catch (\Exception $e) {
            \Log::error("Error fetching data: " . $e->getMessage());
            return $jsonResponse
                ? response()->json(['error' => $e->getMessage()], 500)
                : view('dashboard')->withErrors(['error' => $e->getMessage()]);
        }
    }

    private function getDateRange($filter)
    {
        $now = Carbon::now();

        return match ($filter) {
            'month' => ['start' => $now->startOfMonth(), 'end' => $now->endOfMonth()],
            'year' => ['start' => $now->startOfYear(), 'end' => $now->endOfYear()],
            default => ['start' => $now->startOfDay(), 'end' => $now->endOfDay()],
        };
    }
}
