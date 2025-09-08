<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use WeasyPrint\Facade as WeasyPrint;
use App\Services\PagedView;
use App\Traits\HasPublicId;

class Gpoa extends Model
{
    use HasPublicId;

    public function activities(): HasMany
    {
        return $this->hasMany(GpoaActivity::class);
    }

    public function adviser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adviser_user_id');
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function report()
    {
        $activities = $this->activities()->where('status', 'approved')
            ->orderBy('start_date', 'asc')->get();
        $president = User::ofPosition(['president'])->first();
        $adviser = User::ofPosition(['adviser'])->first();
        /*
        return view('gpoa.report', [
            'gpoa' => $this,
            'activities' => $activities,
            'president' => $president,
            'adviser' => $adviser
        ]);
        */
        return WeasyPrint::prepareSource(new PagedView('gpoa.report', [
            'gpoa' => $this,
            'activities' => $activities,
            'president' => $president,
            'adviser' => $adviser
        ]))->inline('gpoa_report.pdf');
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('active', true);
    }
}
