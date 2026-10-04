<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\ArchiveRequest;
use App\Models\Researcher;
use App\Models\ArchiveAccessLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends BaseController
{
    public function archiveSummary(Request $request)
    {
        try {
            $totalArchives = Archive::count();
            $totalResearchers = Researcher::count();
            $totalArchiveRequests = ArchiveRequest::count();
            $pendingRequests = ArchiveRequest::where('status', 'pending')->count();
            $approvedRequests = ArchiveRequest::where('status', 'approved')->count();
            $activeResearchers = Researcher::whereNotNull('access_otp')
                ->where('otp_expire_at', '>', now())
                ->count();

            // Recent activities (access logs)
            $recentActivities = ArchiveAccessLog::with(['archive', 'user', 'researcher'])
                ->orderBy('access_time', 'desc')
                ->limit(10)
                ->get();

            // Archive requests by status
            $requestsByStatus = ArchiveRequest::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            // Recent archive requests
            $recentRequests = ArchiveRequest::with(['researcher', 'items.archive'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Popular archives (most accessed)
            $popularArchives = ArchiveAccessLog::selectRaw('archive_id, count(*) as access_count')
                ->with('archive')
                ->groupBy('archive_id')
                ->orderBy('access_count', 'desc')
                ->limit(5)
                ->get();

            return $this->successResponse([
                'summary' => [
                    'total_archives' => $totalArchives,
                    'total_researchers' => $totalResearchers,
                    'total_archive_requests' => $totalArchiveRequests,
                    'pending_requests' => $pendingRequests,
                    'approved_requests' => $approvedRequests,
                    'active_researchers' => $activeResearchers,
                ],
                'requests_by_status' => $requestsByStatus,
                'recent_activities' => $recentActivities,
                'recent_requests' => $recentRequests,
                'popular_archives' => $popularArchives,
            ], 'Archive dashboard data retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to load dashboard data', $e->getMessage());
        }
    }

    public function archiveAnalytics(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'timeframe' => 'sometimes|string|in:today,week,month,year,custom',
            'start_date' => 'required_if:timeframe,custom|date',
            'end_date' => 'required_if:timeframe,custom|date',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 'Validation failed');
        }

        try {
            $timeframe = $request->timeframe ?? 'month';
            $startDate = $this->getStartDate($timeframe, $request->start_date);
            $endDate = now();

            // Access trends
            $accessTrends = ArchiveAccessLog::whereBetween('access_time', [$startDate, $endDate])
                ->selectRaw('DATE(access_time) as date, count(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Requests trends
            $requestTrends = ArchiveRequest::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, count(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Researcher registrations
            $researcherRegistrations = Researcher::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, count(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Access by type
            $accessByType = ArchiveAccessLog::whereBetween('access_time', [$startDate, $endDate])
                ->selectRaw('access_type, count(*) as count')
                ->groupBy('access_type')
                ->get()
                ->pluck('count', 'access_type')
                ->toArray();

            // Top researchers
            $topResearchers = ArchiveAccessLog::whereBetween('access_time', [$startDate, $endDate])
                ->whereNotNull('researcher_id')
                ->with('researcher')
                ->selectRaw('researcher_id, count(*) as access_count')
                ->groupBy('researcher_id')
                ->orderBy('access_count', 'desc')
                ->limit(10)
                ->get();

            return $this->successResponse([
                'access_trends' => $accessTrends,
                'request_trends' => $requestTrends,
                'researcher_registrations' => $researcherRegistrations,
                'access_by_type' => $accessByType,
                'top_researchers' => $topResearchers,
                'timeframe' => [
                    'start' => $startDate,
                    'end' => $endDate,
                ]
            ], 'Analytics data retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to load analytics data', $e->getMessage());
        }
    }

    public function researcherStats(Request $request)
    {
        try {
            $totalResearchers = Researcher::count();
            $activeResearchers = Researcher::whereNotNull('access_otp')
                ->where('otp_expire_at', '>', now())
                ->count();
            $newResearchersThisMonth = Researcher::where('created_at', '>=', now()->startOfMonth())->count();

            // Researchers by organization
            $researchersByOrganization = Researcher::selectRaw('organization, count(*) as count')
                ->groupBy('organization')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();

            // Recent researcher registrations
            $recentResearchers = Researcher::orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Researcher activity
            $researcherActivity = ArchiveAccessLog::whereNotNull('researcher_id')
                ->with('researcher')
                ->selectRaw('researcher_id, count(*) as activity_count, max(access_time) as last_activity')
                ->groupBy('researcher_id')
                ->orderBy('last_activity', 'desc')
                ->limit(10)
                ->get();

            return $this->successResponse([
                'total_researchers' => $totalResearchers,
                'active_researchers' => $activeResearchers,
                'new_researchers_this_month' => $newResearchersThisMonth,
                'researchers_by_organization' => $researchersByOrganization,
                'recent_researchers' => $recentResearchers,
                'researcher_activity' => $researcherActivity,
            ], 'Researcher statistics retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to load researcher statistics', $e->getMessage());
        }
    }

    private function getStartDate($timeframe, $customStartDate = null)
    {
        return match($timeframe) {
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            'custom' => Carbon::parse($customStartDate)->startOfDay(),
            default => now()->subDays(30)->startOfDay(),
        };
    }
}