<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\PartnershipController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\EventDeliverableController;
use App\Http\Controllers\EventDeliverableTaskController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\GpoaController;
use App\Http\Controllers\GpoaActivityController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\EventEvalFormController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\MultiStepFormController;
use App\Http\Controllers\EventRegisFormController;
use App\Http\Controllers\AccomReportController;
use App\Http\Controllers\EventAttachmentController;
use App\Http\Controllers\EventEvaluationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentSectionController;
use App\Http\Controllers\StudentYearController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\GpoaActivityModeController;
use App\Http\Controllers\GpoaActivityPartnershipTypeController;
use App\Http\Controllers\GpoaActivityTypeController;
use App\Http\Controllers\GpoaActivityFundSourceController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\EventLinkController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureEachEvalFormStepIsComplete;
use App\Http\Middleware\CheckFormStep;
use App\Http\Middleware\CheckGpoaActivity;
use App\Http\Middleware\CheckSignupInviteCode;
use App\Http\Middleware\CheckEventRegisStep;
use App\Http\Middleware\CheckEventEvalStep;
use App\Http\Middleware\CheckEventEvalForm;
use App\Http\Middleware\CheckPasswordResetToken;
use App\Http\Middleware\CheckActiveGpoa;;
use App\Http\Middleware\EnsureGpoaIsActive;;
use App\Http\Middleware\CheckEventRegistration;;
use Illuminate\Support\Facades\Mail;
use App\Models\Event;
use App\Services\EvalFormStep;
use App\Services\QrCode;

Route::get('/test.html', function (Request $request) {
	abort(500);
});

Route::domain(config('app.admin_domain'))->group(function () {

    Route::name('admin.')->group(function () {

        Route::get('/', [LoginController::class, 'adminLogin'])->name('login');

        Route::post('/login', [LoginController::class, 'adminAuth'])
            ->name('auth');
    });

    Route::get('/auth/{provider}/callback', [LoginController::class,
        'adminAuthWith'])->name('admin-auth.callback');

    Route::get('/auth/{provider}/redirect.php', [LoginController::class,
        'redirectAdminSignin'])->name('admin-auth.redirect');

});

Route::domain(config('app.admin_domain'))->middleware('auth')
        ->group(function () {

    Route::get('/home.html', [HomeController::class , 'adminIndex'])
        ->name('admin.home');

    Route::get('/sign-out.php', [LoginController::class, 'logout'])
        ->name('admin.logout');

    Route::prefix('accounts')->name('accounts.')->group(function () {

        Route::get('/signup-invite/create.html',
            [AccountController::class, 'createSignupInvite'])
            ->name('signup-invites.create');

        Route::post('/signup-invite/store.php',
            [AccountController::class, 'sendSignupInvite'])
            ->name('signup-invites.store');

        Route::get('/signup-invites.html',
            [AccountController::class, 'signupInviteIndex'])
            ->name('signup-invites.index');

        Route::delete('/signup-invite/{invite}/revoke.php',
            [AccountController::class, 'revokeSignupInvite'])
            ->name('signup-invites.destroy');

        Route::get('/signup-invite/{invite}/revoke.html',
            [AccountController::class, 'confirmRevokeSignupInvite'])
            ->name('signup-invites.confirm-destroy');

    });

    Route::name('accounts.')->controller(AccountController::class)
        ->group(function () {

        Route::get('/accounts.html', 'index')->name('index');

        Route::prefix('account-{account}')->group(function () {

            Route::get('/index.html', 'show')->name('show');

            Route::put('/update.php', 'update')->name('update');

            Route::get('/delete.html', 'confirmDestroy')
                ->name('confirm-destroy');

            Route::delete('/delete.php', 'destroy')->name('destroy');

        });
    });

    Route::prefix('roles')->name('roles.')->group(function () {

        Route::put('/update', [RoleController::class, 'update'])
            ->name('update');

        Route::get('/', [RoleController::class, 'index'])
            ->name('index');
    });

    Route::controller(AuditTrailController::class)->name('audit.')
        ->group(function () {

        Route::get('/audit.html', 'index')->name('index');

        Route::get('/audit-{audit}.html', 'show')->name('show');
    });

    Route::get('/analytics', [AnalyticController::class, 'index'])
        ->name('analytics.index');

    Route::get('/avatar_{avatar}', [ProfileController::class, 'showAvatar'])
        ->name('admin-profile.showAvatar');
});

Route::name('profile.')->controller(PasswordResetController::class)
    ->group(function () {

    Route::get('/reset-password.html', 'createPasswordReset')
        ->name('password-reset.create');

    Route::post('/reset-password.php', 'storePasswordReset')
        ->name('password-reset.store');

    Route::get('/password-changed.html', 'endPasswordReset')
        ->name('password-reset.end');

    Route::middleware(CheckPasswordResetToken::class)->group(function () {
        Route::get('/change-password.html', 'editPasswordReset')
            ->name('password-reset.edit');

        Route::put('/change-password.php', 'updatePasswordReset')
            ->name('password-reset.update');
    });
});

Route::domain(config('app.user_domain'))->group(function () {

    Route::controller(EventController::class)->prefix('/event-{event}')
        ->name('events.')->middleware(CheckActiveGpoa::class)
        ->group(function () {

        Route::name('banner.')->group(function () {

            Route::get('/banner/{file}', 'showBanner')->name('show');

        });
    });

    Route::prefix('event-register-{event}')->name('events.registrations.')
        ->middleware([EnsureGpoaIsActive::class, CheckEventRegistration::class])
        ->controller(EventRegistrationController::class)->group(function () {

        Route::get('/index.html', 'editConsentStep')->name('consent.edit');

        Route::post('/consent.php', 'storeConsentStep')->name('consent.store');

        Route::middleware([CheckEventRegisStep::class])
            ->group(function () {

            Route::get('/identity.html', 'editIdentityStep')
                ->name('identity.edit');

            Route::post('/identity.php', 'storeIdentityStep')
                ->name('identity.store');

            Route::get('/result.html', 'showEndStep')->name('end.show');

        });

        Route::get('/qr-code.png', 'showQrCode')->name('qr-code.show');

    });

    Route::get('/event-register-{event}{slash?}', function ($id) {
        return redirect()->route('events.registrations.consent.edit', [
            'event' => $id
        ]);
    })->where('slash', '\/');

    Route::prefix('event-eval-form-{event}')->name('events.evaluations.')
        ->middleware([EnsureGpoaIsActive::class, 'can:evaluate,event', 
        CheckEventEvalForm::class])
        ->controller(EventEvaluationController::class)->group(function () {

        Route::get('/index.html', 'editConsentStep')->name('consent.edit');

        Route::post('/consent.php', 'storeConsentStep')->name('consent.store');

        Route::middleware([CheckEventEvalStep::class])->group(function () {

            Route::get('/evaluation.html', 'editEvaluationStep')
                ->name('evaluation.edit');

            Route::post('/evaluation.php', 'storeEvaluationStep')
                ->name('evaluation.store');

            Route::get('/acknowledgement.html', 'editAcknowledgementStep')
                ->name('acknowledgement.edit');

            Route::post('/acknowledgement.php', 'storeAcknowledgementStep')
                ->name('acknowledgement.store');

            Route::get('/thank-you.html', 'showEndStep')->name('end.show');

        });
    });

    Route::get('/event-eval-form-{event}{slash?}', function (Request $request,
            $id) {
        return redirect()->route('events.evaluations.consent.edit', [
            'event' => $id,
            'token' => $request->token
        ]);
    })->where('slash', '\/');

    Route::name('user.')->group(function () {

        Route::get('/sign-out.php', [LoginController::class, 'logout'])
            ->name('logout');

        Route::get('/', [LoginController::class, 'login'])->name('login');

        Route::post('/login', [LoginController::class, 'auth'])->name('auth');

        Route::middleware(CheckSignupInviteCode::class)->group(function () {

            Route::get('/signup-invitation.html', [LoginController::class,
                'showSignupInvitation'])->name('invitation');

        });
    });

    Route::prefix('auth')->name('auth.')
        ->middleware(CheckSignupInviteCode::class)->group(function () {

        Route::get('/{provider}/redirect.php', [LoginController::class,
            'redirectSignin'])->name('redirect');

        Route::get('/{provider}/callback', [LoginController::class,
            'signinWith'])->name('callback');

    });

    Route::get('/sign-up/{provider}/redirect.php', [LoginController::class,
        'redirectSignup'])->name('signup.redirect')
        ->middleware(CheckSignupInviteCode::class);

    Route::get('/sign-up/{provider}/callback', [LoginController::class,
        'signupWith'])->name('signup.callback')
        ->middleware(CheckSignupInviteCode::class);

    Route::name('users.')->middleware(CheckSignupInviteCode::class)
        ->controller(UserController::class)->group(function () {

        Route::get('/sign-up.html', 'create')->name('create');

        Route::post('/sign-up.php', 'store')->name('store');

    });

    Route::get('/verify-email', [ProfileController::class, 'verifyEmail'])
        ->name('verify-email');
});

Route::domain(config('app.user_domain'))->middleware('auth')
    ->group(function () {

    Route::middleware('auth.setting')->prefix('settings')->name('settings.')
        ->group(function () {

        Route::get('/index.html', [SettingController::class, 'index'])
            ->name('index');

	Route::middleware('auth.setting-edit')->controller(AssetController::class)->name('logos.')
            ->group(function () {

            Route::get('/logos.html', 'editLogo')->name('edit');  

            Route::put('/logos.php', 'updateLogo')->name('update');  
        });
    
        Route::controller(StudentSectionController::class)
            ->name('students.sections.')->group(function () {
    
            Route::get('/student-sections.html', 'index')->name('index');
    
            Route::get('/add-student-sections.html', 'create')->name('create');
    
            Route::post('/add-student-sections.php', 'store')->name('store');
        });
    
        Route::prefix('student-section-{section}')->name('students.sections.')
            ->controller(StudentSectionController::class)->group(function () {
    
            Route::get('/delete.html', 'confirmDestroy')
                ->name('confirm-destroy');
    
            Route::delete('/delete.php', 'destroy')->name('destroy');
        });
    
        Route::controller(StudentYearController::class)
            ->name('students.years.')->group(function () {
    
            Route::get('/student-year-levels.html', 'index')->name('index');
    
            Route::get('/add-student-year-levels.html', 'create')
                ->name('create');
    
            Route::post('/add-student-year-levels.php', 'store')
                ->name('store');
        });
    
        Route::prefix('student-year-level-{year}')->name('students.years.')
            ->controller(StudentYearController::class)->group(function () {
    
            Route::get('/delete.html', 'confirmDestroy')
                ->name('confirm-destroy');
    
            Route::delete('/delete.php', 'destroy')->name('destroy');
        });
    
        Route::controller(StudentCourseController::class)
            ->name('students.courses.')->group(function () {
    
            Route::get('/student-courses.html', 'index')->name('index');
    
            Route::get('/add-student-course.html', 'create')
                ->name('create');
    
            Route::post('/add-student-course.php', 'store')
                ->name('store');
        });
    
        Route::prefix('student-course-{course}')->name('students.courses.')
            ->controller(StudentCourseController::class)->group(function () {
    
            Route::get('/delete.html', 'confirmDestroy')
                ->name('confirm-destroy');
    
            Route::delete('/delete.php', 'destroy')->name('destroy');
        });
    
        Route::controller(GpoaActivityModeController::class)
            ->name('gpoa-activities.modes.')->group(function () {
    
            Route::get('/gpoa-modes.html', 'index')->name('index');
        });
    
        Route::prefix('gpoa-mode-{mode}')->name('gpoa-activities.modes.')
            ->controller(GpoaActivityModeController::class)->group(function () {
    
            Route::get('/delete.html', 'confirmDestroy')
                ->name('confirm-destroy');
    
            Route::delete('/delete.php', 'destroy')->name('destroy');
        });
    
        Route::controller(GpoaActivityFundSourceController::class)
            ->name('gpoa-activities.fund-sources.')->group(function () {
    
            Route::get('/gpoa-sources-of-fund.html', 'index')->name('index');
        });
    
        Route::prefix('gpoa-source-of-fund-{fund}')
            ->name('gpoa-activities.fund-sources.')
            ->controller(GpoaActivityFundSourceController::class)
            ->group(function () {
    
            Route::get('/delete.html', 'confirmDestroy')
                ->name('confirm-destroy');
    
            Route::delete('/delete.php', 'destroy')->name('destroy');
        });
    
        Route::controller(GpoaActivityPartnershipTypeController::class)
            ->name('gpoa-activities.partnership-types.')->group(function () {
    
            Route::get('/gpoa-partnerships.html', 'index')->name('index');
        });
    
        Route::prefix('gpoa-partnership-{partnership}')
            ->name('gpoa-activities.partnership-types.')
            ->controller(GpoaActivityPartnershipTypeController::class)
            ->group(function () {
    
            Route::get('/delete.html', 'confirmDestroy')
                ->name('confirm-destroy');
    
            Route::delete('/delete.php', 'destroy')->name('destroy');
        });
    
        Route::controller(GpoaActivityTypeController::class)
            ->name('gpoa-activities.types.')->group(function () {
    
            Route::get('/gpoa-types.html', 'index')->name('index');
        });
    
        Route::prefix('gpoa-type-{type}')->name('gpoa-activities.types.')
            ->controller(GpoaActivityTypeController::class)->group(function () {
    
            Route::get('/delete.html', 'confirmDestroy')
                ->name('confirm-destroy');
    
            Route::delete('/delete.php', 'destroy')->name('destroy');
        });
    });

    Route::get('/home.html', [HomeController::class, 'index'])
        ->name('user.home');

    Route::get('/old.html', [GpoaController::class, 'oldIndex'])
        ->name('gpoas.old-index');

    Route::prefix('gpoa-{gpoa}')->name('gpoas.')
            ->controller(GpoaController::class)->group(function () {

        Route::get('/index.html', 'show')->name('show');

        Route::get('/report.html', 'showReport')->name('report.show');

        Route::get('/report.pdf', 'showReportFile')->name('report-file.show');

        Route::get('/accomplishment-report.html', 'showAccomReport')
            ->name('accom-report.show');

        Route::get('/accomplishment-report.pdf', 'showAccomReportFile')
            ->name('accom-report-file.show');
    });

    Route::name('gpoa.')->middleware(CheckGpoaActivity::class)
        ->group(function () {

        Route::get('/gpoa.html', [GpoaController::class, 'index'])
            ->name('index');

        Route::prefix('gpoa')->group(function () {

            Route::controller(GpoaController::class)->group(function () {

                Route::get('/create.html', 'create')->name('create');

                Route::post('/store.php', 'store')->name('store');
            });

            Route::controller(GpoaController::class)
                ->middleware(CheckActiveGpoa::class)->group(function () {

                Route::get('/gpoa_report_{id}.pdf', 'streamPdf')
                    ->name('streamPdf');

                // Route::get('/report.html', 'genPdf')->name('showGenPdf');
                Route::get('/report.html', 'showCurrentReport')->name('showGenPdf');

                Route::get('/confirm-close.html', 'confirmClose')
                    ->name('confirmClose');

                Route::put('/close.php', 'close')->name('close');

                Route::get('/edit.html', 'edit')->name('edit');

                Route::put('/update.php', 'update')->name('update');

            });

            Route::controller(GpoaActivityController::class)
                    ->middleware(CheckActiveGpoa::class)
                    ->name('activities.')->group(function () {

                Route::get('/create-activity.html', 'create')->name('create');

                Route::post('/store-activity.php', 'store')->name('store');

                Route::prefix('activity-{activity}')->group(function () {

                    Route::get('/index.html', 'show')->name('show');

                    Route::get('/submit.html', 'prepareForSubmit')
                        ->name('prepareForSubmit');

                    Route::put('/submit.php', 'submit')->name('submit');

                    Route::get('/return.html', 'prepareForReturn')
                        ->name('prepareForReturn');

                    Route::put('/return.php', 'return')->name('return');

                    Route::get('/reject.html', 'prepareForReject')
                        ->name('prepareForReject');

                    Route::put('/reject.php', 'reject')->name('reject');

                    Route::get('/approve.html', 'prepareForApprove')
                        ->name('prepareForApprove');

                    Route::put('/approve.php', 'approve')->name('approve');

                    Route::get('/edit.html', 'edit')->name('edit');

                    Route::put('/update.php', 'update')->name('update');

                    Route::get('/delete.html', 'confirmDestroy')
                        ->name('confirmDestroy');

                    Route::delete('/delete.php', 'destroy')->name('destroy');

                });

                Route::get('/activity-{activity}{slash?}', function ($id) {
                    return redirect()->route('gpoa.activities.show', [
                        'activity' => $id
                    ]);
                })->where('slash', '\/');

            });
        });
    });


    Route::get('/events.html', [EventController::class, 'index'])
        ->name('events.index');

    Route::name('events.')->group(function () {

        Route::prefix('event-{event}')->group(function () {

            Route::controller(EventAttachmentController::class)
                    ->name('attachments.')->group(function () {

                Route::prefix('attachment-set-{attachment_set}')
                        ->group(function () {

                    Route::get('/attachment-preview-{attachment}.jpg',
                        'showPreviewFile')->name('showPreviewFile');

                    Route::get('/attachment-{attachment}.jpg',
                        'showFullFile')->name('showFullFile');
                });
            });
        });

    });

    Route::name('events.')->middleware(CheckActiveGpoa::class)
        ->group(function () {

        Route::prefix('event-{event}')->group(function () {

            Route::controller(EventController::class)->group(function () {

                Route::get('/index.html', 'show')->name('show');

                Route::get('/edit.html', 'edit')->name('edit');

                Route::put('/update.php', 'update')->name('update');

                Route::name('venue.')->group(function () {

                    Route::get('/venue.html', 'editVenue')->name('edit');

                    Route::put('/venue.php', 'updateVenue')->name('update');
                });

                Route::name('description.')->group(function () {

                    Route::get('/description.html', 'editDescription')
                        ->name('edit');

                    Route::put('/description.php', 'updateDescription')
                        ->name('update');
                });

                Route::name('narrative.')->group(function () {

                    Route::get('/narrative.html', 'editNarrative')
                        ->name('edit');

                    Route::put('/narrative.php', 'updateNarrative')
                        ->name('update');
                });

                Route::name('dates.')->group(function () {

                    Route::get('/date-create.html', 'createDate')
                        ->name('create');

                    Route::get('/dates.html', 'dateIndex')->name('index');

                    Route::post('/date.php', 'storeDate')->name('store');

                    Route::prefix('date-{date}')->group(function () {

                        /*
                        Route::get('/edit.html', 'editDate')->name('edit');

                        Route::put('/update.php', 'updateDate')
                            ->name('update');
                        */

                        Route::get('/confirm-delete.html',
                            'confirmDestroyDate')->name('confirmDestroy');

                        Route::delete('/delete.php', 'destroyDate')
                            ->name('destroy');

                    });
                });

                Route::get('/attendance.html', 'showAttendance')
                    ->name('attendance.show');

                Route::get('/add-attendee.html', 'createAttendee')
                    ->name('attendance.create');

                Route::post('/add-attendee.php', 'storeAttendee')
                    ->name('attendance.store');

                /*
                Route::get('/accom-report.html', 'showAccomReport')
                    ->name('accom-report.show');
                */

                Route::get('/accom-report-{id}.pdf', 'streamAccomReport')
                    ->name('accom-report.stream');

                Route::get('/evaluation.html', 'editComments')
                    ->name('evaluations.comments.edit');

                Route::put('/evaluation.php', 'updateComments')
                    ->name('evaluations.comments.update');

                Route::name('banner.')->group(function () {

                    Route::get('/banner.html', 'editBanner')->name('edit');

                    Route::put('/banner.php', 'updateBanner')->name('update');

                });
            });

            Route::controller(EventLinkController::class)->prefix('links')
                ->name('links.')->group(function () {

                Route::get('/index.html', 'index')->name('index');

                Route::get('/create.html', 'create')->name('create');

                Route::post('/create.php', 'store')->name('store');
                
                Route::prefix('link-{link}')->group(function () {

                    Route::get('/delete.html', 'confirmDestroy')
                        ->name('confirm-destroy');

                    Route::delete('/delete.php', 'destroy')->name('destroy');

                });

            });

            Route::controller(EventEvalFormController::class)
                    ->name('eval-form.')->group(function () {

                Route::get('/eval-form.html', 'editQuestions')
                    ->name('edit-questions');

                Route::put('/eval-form.php', 'updateQuestions')
                    ->name('update-questions');

            });

            Route::controller(EventRegisFormController::class)
                    ->name('regis-form.')->group(function () {

                Route::get('/regis-form.html', 'edit')->name('edit');

                Route::put('/regis-form.php', 'update')->name('update');
            });

            Route::controller(EventAttachmentController::class)
                    ->name('attachments.')->group(function () {

                Route::get('/attachments.html', 'index')->name('index');

                Route::get('/create-attachment.html', 'create')->name('create');

                Route::post('/create-attachment.php', 'store')->name('store');

                Route::prefix('attachment-set-{attachment_set}')
                        ->group(function () {

                    Route::get('/edit.html', 'edit')->name('edit');

                    Route::put('/edit.php', 'update')->name('update');

                    Route::prefix('attachment-{attachment}')
                            ->group(function () {

                        Route::get('/index.html', 'show')->name('show');

                        Route::put('/update.php', 'updateAttachment')
                            ->name('updateAttachment');

                        Route::get('/delete.html', 'confirmDestroy')
                            ->name('confirmDestroy');

                        Route::delete('/delete.php', 'destroy')
                            ->name('destroy');
                    });

                    Route::get('/delete.html', 'confirmDestroySet')
                        ->name('confirmDestroySet');

                    Route::delete('/delete.php', 'destroySet')
                        ->name('destroySet');
                });
            });
        });

        Route::get('/event-{event}{slash?}', function ($id) {
            return redirect()->route('events.show', [
                'event' => $id
            ]);
        })->where('slash', '\/');
    });

    Route::get('/accom-reports.html', [AccomReportController::class, 'index'])
        ->name('accom-reports.index');

    Route::controller(AccomReportController::class)
            ->middleware(CheckActiveGpoa::class)->name('accom-reports.')
            ->group(function () {
/*
        Route::get('/accom-reports/change-background.html', 
            'editBackground')->name('background.edit');

        Route::put('/accom-reports/change-background.php', 
            'updateBackground')->name('background.update');
*/

        Route::get('/accom-reports/gen-pdf.html', 'generate')->name('generate');

        Route::get('/accom-reports/cancel-gen-pdf.php', 'stopGenerating')
            ->name('stop-generating');

        Route::get('/accom-reports/accom_report_set_{id}.pdf', 'stream')
            ->name('stream');

        Route::prefix('accom-report-{event}')->group(function () {

            Route::get('/index.html', 'show')->name('show');

            Route::get('/report.html', 'showReport')->name('report.show');

            Route::get('/report.pdf', 'showReportFile')->name('report-file.show');

            Route::get('/submit.html', 'prepareForSubmit')
                ->name('prepareForSubmit');

            Route::put('/submit.php', 'submit')->name('submit');

            Route::get('/return.html', 'prepareForReturn')
                ->name('prepareForReturn');

            Route::put('/return.php', 'return')->name('return');

            Route::get('/approve.html', 'prepareForApprove')
                ->name('prepareForApprove');

            Route::put('/approve.php', 'approve')->name('approve');
        });
    });

/*

    Route::resource('meetings', MeetingController::class);

    Route::resource('meetings', MeetingController::class)->only([
        'index', 'show'
    ]);

    Route::prefix('meetings')->name('meetings.')->group(function () {

        Route::get('/{meeting}/minutes-file/{filename}',
                   [MeetingController::class, 'showMinutesFile'])
            ->name('showMinutesFile');

        Route::get('/{meeting}/minutes-file',
                   [MeetingController::class, 'showMinutes'])
            ->name('showMinutes');
    });



    Route::resource('platforms', PlatformController::class);

    Route::resource('platforms', PlatformController::class)->only([
        'index', 'show'
    ]);

    Route::resource('partnerships', PartnershipController::class);

    Route::resource('partnerships', PartnershipController::class)->only([
        'index', 'show'
    ]);
*/

    Route::controller(PositionController::class)->name('positions.')
            ->group(function () {

        Route::get('central-body.html', 'index')->name('index');

        Route::get('central-body-create.html', 'create')->name('create');

        Route::post('central-body-store.php', 'store')->name('store');

        Route::prefix('central-body-{position}')->group(function () {

            Route::get('/index.html', 'show')->name('show');

            Route::put('/update.php', 'update')->name('update');

            Route::get('/delete.html', 'confirmDestroy')
                ->name('confirmDestroy');

            Route::delete('/delete.php', 'destroy')->name('destroy');

        });

    });

    /*
    Route::prefix('positions')->name('positions.')->group(function () {
        Route::get('/{position}/confirm-delete', [PositionController::class,
            'confirmDestroy'])->name('confirmDestroy');

        Route::get('/edit', [PositionController::class, 'edit'])
            ->name('edit');
        Route::post('/update', [PositionController::class, 'update'])
            ->name('update');
        Route::post('/position/{id}/update',
                    [PositionController::class, 'updatePosition'])
            ->name('updatePosition');
        Route::delete('/position/{id}',
                   [PositionController::class, 'destroy'])->name('destroy');
        Route::get('/position/{id}', [PositionController::class, 'show'])
            ->name('show');
        Route::get('/create', [PositionController::class, 'create'])
            ->name('create');
        Route::post('/store', [PositionController::class, 'store'])
            ->name('store');
    });
    Route::get('/council-body', [PositionController::class, 'index'])
               ->name('positions.index');
    Route::resource('positions', PositionController::class);
    */

    Route::prefix('account')->name('profile.')
            ->controller(ProfileController::class)->group(function () {

        Route::get('/', 'index')->name('index');

        Route::get('/edit.html', 'edit')->name('edit');

        Route::put('/update.php', 'update')->name('update');

        Route::get('/avatar_{avatar}', 'showAvatar')->name('showAvatar');

        Route::get('/edit-email.html', 'editEmail')
            ->name('email.edit');

        Route::put('/update-email.php', 'updateEmail')
            ->name('email.update');

        Route::get('/send-email-verify.php', 'resendEmailVerify')
            ->name('email.send-verify');

        Route::get('/edit-password.html', 'editPassword')
            ->name('password.edit');

        Route::put('/update-password.php', 'updatePassword')
            ->name('password.update');
    });

    Route::prefix('connect')->name('profile.connect.')
            ->controller(ProfileController::class)->group(function () {

        Route::get('/{provider}/redirect.php', 'connectSocial')
            ->name('redirect');

        Route::get('/{provider}/callback', 'updateSocial')->name('callback');

        Route::get('/{provider}/remove', 'deleteSocial')->name('remove');
    });

    Route::post('/attendance/store', [AttendanceController::class, 'store'])
        ->name('attendance.store');

    Route::get('/attendance.html', [AttendanceController::class, 'create'])
        ->name('attendance.create');
});


