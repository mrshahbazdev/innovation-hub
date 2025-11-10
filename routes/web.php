<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\IdeaSubmissionForm;
use App\Livewire\IdeaPipeline;
use App\Livewire\IdeaDetail;
use App\Livewire\SiteLogoManager;
use App\Livewire\BrowseTeams;
use App\Livewire\ViewTeam;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Route Area (Public)
//Route::get('/submit-idea', IdeaSubmissionForm::class);
Route::get('/thank-you', function () {
    return view('thank-you');
})->name('thank-you');
// Admin/Auth Area
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/pipeline', IdeaPipeline::class)->name('pipeline');
    Route::get('/idea/{idea}', IdeaDetail::class)->name('idea.detail');
    Route::get('/site-logo', SiteLogoManager::class)->name('site.logo');
   // Route::get('/submit-idea', IdeaSubmissionForm::class)->name('submit-idea');
    Route::get('/browse-teams', BrowseTeams::class)->name('teams.browse');
    Route::get('/teams/{team}/view', ViewTeam::class)->name('teams.view');
});
