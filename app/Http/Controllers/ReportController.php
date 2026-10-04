<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\ArchiveRequest;
use App\Models\Researcher;
use App\Models\ArchiveAccessLog;
use App\Models\ArchiveRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends BaseController
{
    public function overviewReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'timeframe' => 'sometimes|string|in:today,week,month,year,custom'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 'Validation failed');
        }

        try {
            $timeframe = $request->timeframe ?? 'month';
            $dateRange = $this->getDateRange($timeframe, $request->start_date, $request->end_date);

            // Basic counts
            $totalArchives = Archive::count();
            $totalResearchers = Researcher::count();
            $totalRequests = ArchiveRequest::count();

            // Date filtered counts
            $newArchives = Archive::whereBetween('created_at', $dateRange)->count();
            $newResearchers = Researcher::whereBetween('created_at', $dateRange)->count();
            $periodRequests = ArchiveRequest::whereBetween('created_at', $dateRange)->count();

            // Access statistics
            $totalAccesses = ArchiveAccessLog::whereBetween('access_time', $dateRange)->count();
            $userAccesses = ArchiveAccessLog::whereBetween('access_time', $dateRange)
                ->whereNotNull('user_id')->count();
            $researcherAccesses = ArchiveAccessLog::whereBetween('access_time', $dateRange)
                ->whereNotNull('researcher_id')->count();

            // Request status breakdown
            $requestStatuses = ArchiveRequest::selectRaw('status, count(*) as count')
                ->whereBetween('created_at', $dateRange)
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            // Monthly trends
            $monthlyTrends = ArchiveAccessLog::selectRaw(
                "DATE_FORMAT(access_time, '%Y-%m') as month, 
                COUNT(*) as access_count,
                COUNT(DISTINCT archive_id) as unique_archives,
                COUNT(DISTINCT researcher_id) as unique_researchers"
            )
                ->whereBetween('access_time', $dateRange)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Top 5 accessed archives
            $topArchives = ArchiveAccessLog::with('archive')
                ->selectRaw('archive_id, count(*) as access_count')
                ->whereBetween('access_time', $dateRange)
                ->groupBy('archive_id')
                ->orderBy('access_count', 'desc')
                ->limit(5)
                ->get();

            return $this->successResponse([
                'summary' => [
                    'total_archives' => $totalArchives,
                    'total_researchers' => $totalResearchers,
                    'total_requests' => $totalRequests,
                    'new_archives' => $newArchives,
                    'new_researchers' => $newResearchers,
                    'period_requests' => $periodRequests,
                    'total_accesses' => $totalAccesses,
                    'user_accesses' => $userAccesses,
                    'researcher_accesses' => $researcherAccesses,
                ],
                'request_statuses' => $requestStatuses,
                'monthly_trends' => $monthlyTrends,
                'top_archives' => $topArchives,
                'date_range' => $dateRange
            ], 'Overview report generated successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to generate overview report', $e->getMessage());
        }
    }

    public function requestedItemsReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string|in:pending,approved,rejected,completed,issued,returned'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 'Validation failed');
        }

        try {
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subMonth();
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();

            // Get requested items with request and archive data
            $query = ArchiveRequestItem::with([
                'request.researcher', 
                'archive.subSeries.mainSeries',
                'archive.group',
                'archive.type'
            ])->whereHas('request', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });

            if ($request->status) {
                $query->where('status', $request->status);
            }

            $requestedItems = $query->get();

            // Group by archive for popularity (count requests per archive)
            $popularArchives = ArchiveRequestItem::with('archive')
                ->selectRaw('archive_id, count(*) as request_count')
                ->whereHas('request', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->groupBy('archive_id')
                ->orderBy('request_count', 'desc')
                ->limit(10)
                ->get();

            // Status distribution of requested items
            $statusDistribution = ArchiveRequestItem::selectRaw('status, count(*) as count')
                ->whereHas('request', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            // Monthly request trends (count of ArchiveRequestItems per month)
            $monthlyRequests = ArchiveRequestItem::selectRaw(
                "DATE_FORMAT(archive_request_items.created_at, '%Y-%m') as month, 
                COUNT(*) as total_items,
                COUNT(CASE WHEN archive_request_items.status = 'approved' THEN 1 END) as approved_items,
                COUNT(CASE WHEN archive_request_items.status = 'pending' THEN 1 END) as pending_items,
                COUNT(CASE WHEN archive_request_items.status = 'issued' THEN 1 END) as issued_items"
            )
                ->join('archive_requests', 'archive_request_items.archive_request_id', '=', 'archive_requests.id')
                ->whereBetween('archive_request_items.created_at', [$startDate, $endDate])
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Requests with item counts
            $requestsWithItemCounts = ArchiveRequest::with(['researcher', 'items.archive'])
                ->withCount('items')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            return $this->successResponse([
                'requested_items' => $requestedItems,
                'popular_archives' => $popularArchives,
                'status_distribution' => $statusDistribution,
                'monthly_requests' => $monthlyRequests,
                'requests_with_counts' => $requestsWithItemCounts,
                'date_range' => [
                    'start' => $startDate,
                    'end' => $endDate
                ]
            ], 'Requested items report generated successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to generate requested items report', $e->getMessage());
        }
    }

    public function mostAccessedReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 'Validation failed');
        }

        try {
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subYear();
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
            $limit = $request->limit ?? 20;

            // Most accessed archives
            $mostAccessed = ArchiveAccessLog::with(['archive.subSeries.mainSeries', 'archive.group'])
                ->selectRaw('archive_id, 
                    count(*) as total_accesses,
                    count(DISTINCT researcher_id) as unique_researchers,
                    count(DISTINCT user_id) as unique_users')
                ->whereBetween('access_time', [$startDate, $endDate])
                ->groupBy('archive_id')
                ->orderBy('total_accesses', 'desc')
                ->limit($limit)
                ->get();

            // Access trends by month for top archives
            $accessTrends = ArchiveAccessLog::selectRaw(
                "DATE_FORMAT(access_time, '%Y-%m') as month,
                archive_id,
                count(*) as access_count"
            )
                ->whereBetween('access_time', [$startDate, $endDate])
                ->whereIn('archive_id', $mostAccessed->pluck('archive_id'))
                ->groupBy('month', 'archive_id')
                ->orderBy('month')
                ->get()
                ->groupBy('archive_id');

            // Access type breakdown for top archives
            $accessTypeBreakdown = ArchiveAccessLog::selectRaw(
                'archive_id,
                access_type,
                count(*) as count'
            )
                ->whereBetween('access_time', [$startDate, $endDate])
                ->whereIn('archive_id', $mostAccessed->pluck('archive_id'))
                ->groupBy('archive_id', 'access_type')
                ->get()
                ->groupBy('archive_id');

            return $this->successResponse([
                'most_accessed' => $mostAccessed,
                'access_trends' => $accessTrends,
                'access_type_breakdown' => $accessTypeBreakdown,
                'date_range' => [
                    'start' => $startDate,
                    'end' => $endDate
                ]
            ], 'Most accessed archives report generated successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to generate most accessed report', $e->getMessage());
        }
    }

    public function researcherReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'organization' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 'Validation failed');
        }

        try {
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subYear();
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();

            // Get researchers with their request and access counts
            $researchers = Researcher::with(['archiveRequests' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])->get()->map(function($researcher) use ($startDate, $endDate) {
                $researcher->total_requests = $researcher->archiveRequests->count();
                $researcher->approved_requests = $researcher->archiveRequests->where('status', 'approved')->count();
                $researcher->pending_requests = $researcher->archiveRequests->where('status', 'pending')->count();
                
                // Get access count
                $researcher->total_accesses = ArchiveAccessLog::where('researcher_id', $researcher->id)
                    ->whereBetween('access_time', [$startDate, $endDate])
                    ->count();
                
                // Get last activity
                $researcher->last_activity = ArchiveAccessLog::where('researcher_id', $researcher->id)
                    ->whereBetween('access_time', [$startDate, $endDate])
                    ->max('access_time');

                return $researcher;
            });

            // Researcher activity trends
            $activityTrends = ArchiveAccessLog::whereNotNull('researcher_id')
                ->selectRaw(
                    "researcher_id,
                    DATE_FORMAT(access_time, '%Y-%m') as month,
                    count(*) as access_count"
                )
                ->whereBetween('access_time', [$startDate, $endDate])
                ->groupBy('researcher_id', 'month')
                ->orderBy('month')
                ->get()
                ->groupBy('researcher_id');

            // Organization statistics
            $organizationStats = Researcher::with(['archiveRequests' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])->get()->groupBy('organization')->map(function($orgResearchers) {
                return [
                    'researcher_count' => $orgResearchers->count(),
                    'avg_requests' => $orgResearchers->avg(function($researcher) {
                        return $researcher->archiveRequests->count();
                    }),
                    'total_organization_requests' => $orgResearchers->sum(function($researcher) {
                        return $researcher->archiveRequests->count();
                    })
                ];
            })->sortByDesc('researcher_count');

            // Top active researchers by access count
            $topResearchers = ArchiveAccessLog::with('researcher')
                ->selectRaw('researcher_id, count(*) as access_count')
                ->whereBetween('access_time', [$startDate, $endDate])
                ->whereNotNull('researcher_id')
                ->groupBy('researcher_id')
                ->orderBy('access_count', 'desc')
                ->limit(10)
                ->get();

            return $this->successResponse([
                'researchers' => $researchers,
                'activity_trends' => $activityTrends,
                'organization_stats' => $organizationStats,
                'top_researchers' => $topResearchers,
                'date_range' => [
                    'start' => $startDate,
                    'end' => $endDate
                ]
            ], 'Researcher report generated successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to generate researcher report', $e->getMessage());
        }
    }

    private function getDateRange($timeframe, $customStart = null, $customEnd = null)
    {
        $startDate = match($timeframe) {
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            'custom' => $customStart ? Carbon::parse($customStart)->startOfDay() : now()->subMonth(),
            default => now()->subMonth(),
        };

        $endDate = $timeframe === 'custom' && $customEnd ? 
            Carbon::parse($customEnd)->endOfDay() : now();

        return [$startDate, $endDate];
    }
}