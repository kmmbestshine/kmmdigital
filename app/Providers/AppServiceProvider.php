<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {   
        // CommonRepository
        $this->app->bind('App\Repositories\Common\CommonInterface', 'App\Repositories\Common\CommonRepository');        
        // UserRepository
        $this->app->bind('App\Repositories\User\UserInterface', 'App\Repositories\User\UserRepository');
        // FeedbackRepository
        $this->app->bind('App\Repositories\Feedback\FeedbackInterface', 'App\Repositories\Feedback\FeedbackRepository');
        // ReportRepository
        $this->app->bind('App\Repositories\Report\ReportInterface', 'App\Repositories\Report\ReportRepository');
        // NotificationRepository
        $this->app->bind('App\Repositories\Notification\NotificationInterface', 'App\Repositories\Notification\NotificationRepository');                
        // DevelopmentTeamInterface
        $this->app->bind('App\Repositories\DevelopmentTeam\DevelopmentTeamInterface', 'App\Repositories\DevelopmentTeam\DevelopmentTeamRepository');
        // ModuleRepository
        $this->app->bind('App\Repositories\Module\ModuleInterface', 'App\Repositories\Module\ModuleRepository');
        // QuestionRepository
        $this->app->bind('App\Repositories\Question\QuestionInterface', 'App\Repositories\Question\QuestionRepository');
        // ExamRepository
        $this->app->bind('App\Repositories\Exam\ExamInterface', 'App\Repositories\Exam\ExamRepository'); 
        // DiscussionRepository
        $this->app->bind('App\Repositories\Discussion\DiscussionInterface', 'App\Repositories\Discussion\DiscussionRepository'); 
        // SubscribePlanRepository
        $this->app->bind('App\Repositories\SubscribePlan\SubscribePlanInterface', 'App\Repositories\SubscribePlan\SubscribePlanRepository');
        // PaymentRepository 
        $this->app->bind('App\Repositories\Payment\PaymentInterface', 'App\Repositories\Payment\PaymentRepository'); 
        // ChartRepository 
        $this->app->bind('App\Repositories\Chart\ChartInterface', 'App\Repositories\Chart\ChartRepository');
    }
}
