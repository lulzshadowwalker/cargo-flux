<?php

namespace App\Filament\Resources\ReviewResource\Widgets;

use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReviewsStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '3s';

    protected function getStats(): array
    {
        // Fetching the current and previous period data
        $currentPeriodReviews = Review::where('created_at', '>=', now()->subDays(30))->count();
        $previousPeriodReviews = Review::whereBetween('created_at', [Carbon::now()->subDays(60), Carbon::now()->subDays(30)])->count();

        $currentPeriodRatingAvg = Review::where('created_at', '>=', now()->subDays(30))->avg('rating');
        $previousPeriodRatingAvg = Review::whereBetween('created_at', [Carbon::now()->subDays(60), Carbon::now()->subDays(30)])->avg('rating');

        // Generate chart data for the last 30 days
        $reviewCountChart = $this->getReviewCountChart();
        $ratingChart = $this->getRatingChart();

        // Calculate the trend for reviews
        $reviewTrend = $this->calculateTrend($currentPeriodReviews, $previousPeriodReviews);
        $reviewTrendColor = $this->getTrendColor($reviewTrend);
        $reviewTrendIcon = $this->getTrendIcon($reviewTrend);
        $reviewTrendDescription = $this->getTrendDescription($reviewTrend);

        // Calculate the trend for average rating
        $ratingTrend = $this->calculateTrend($currentPeriodRatingAvg, $previousPeriodRatingAvg);
        $ratingTrendColor = $this->getTrendColor($ratingTrend);
        $ratingTrendIcon = $this->getTrendIcon($ratingTrend);
        $ratingTrendDescription = $this->getTrendDescription($ratingTrend);

        // Create the Review Count stat card
        $reviewCountCard = Stat::make(__('filament/resources/review-resource.reviews-count'), $currentPeriodReviews)
            ->description($reviewTrendDescription)
            ->descriptionIcon($reviewTrendIcon)
            ->chart($reviewCountChart)
            ->color($reviewTrendColor);

        // Create the Rating Average stat card
        $ratingCard = Stat::make(__('filament/resources/review-resource.average-rating'), number_format($currentPeriodRatingAvg, 1))
            ->description($ratingTrendDescription)
            ->descriptionIcon($ratingTrendIcon)
            ->chart($ratingChart)
            ->color($ratingTrendColor);

        return [
            $reviewCountCard,
            $ratingCard,
        ];
    }

    // Helper function to calculate percentage trend
    protected function calculateTrend($current, $previous)
    {
        if ($previous == 0) {
            return 100; // If there was no previous data, treat it as a 100% increase
        }

        return (($current - $previous) / $previous) * 100;
    }

    // Helper function to determine color based on trend
    protected function getTrendColor($trend)
    {
        if ($trend > 0) {
            return 'success'; // Positive trend
        } elseif ($trend < 0) {
            return 'danger'; // Negative trend
        }
        return 'primary'; // No change
    }

    // Helper function to determine icon based on trend
    protected function getTrendIcon($trend)
    {
        if ($trend > 0) {
            return 'heroicon-m-arrow-trending-up';
        } elseif ($trend < 0) {
            return 'heroicon-m-arrow-trending-down';
        }
        return 'heroicon-m-minus'; // No change
    }

    // Helper function to get description based on trend
    protected function getTrendDescription($trend)
    {
        if ($trend > 0) {
            return number_format($trend, 2) . '% ' . __('filament/resources/review-resource.increase');
        } elseif ($trend < 0) {
            return number_format(abs($trend), 2) . '% ' . __('filament/resources/review-resource.decrease');
        }

        return __('filament/resources/review-resource.no-change');
    }

    // Fetch daily review counts for the last 30 days
    protected function getReviewCountChart()
    {
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        return Review::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();
    }

    // Fetch daily average ratings for the last 30 days
    protected function getRatingChart()
    {
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        return Review::select(DB::raw('DATE(created_at) as date'), DB::raw('avg(rating) as avg_rating'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('avg_rating')
            ->toArray();
    }
}
