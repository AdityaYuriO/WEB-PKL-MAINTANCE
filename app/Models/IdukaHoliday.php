<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class IdukaHoliday extends Model
{
    use HasFactory;

    protected $table = 'iduka_holidays';

    protected $fillable = [
        'iduka_id',
        'date',
        'name',
        'recurring'
    ];

    protected $casts = [
        'date' => 'date',
        'recurring' => 'boolean'
    ];

    /**
     * Check if given date is holiday for an iduka
     */
    public static function isHoliday($idukaId, $date)
    {
        $dateObj = Carbon::parse($date);

        // Cek jika hari Minggu
        if ($dateObj->isSunday()) {
            return true;
        }

        $dayMonth = $dateObj->format('m-d');

        $exists = self::where('iduka_id', $idukaId)
            ->where(function ($q) use ($dateObj, $dayMonth) {
                $q->whereDate('date', $dateObj->toDateString())
                  ->orWhere(function ($q2) use ($dayMonth) {
                      $q2->where('recurring', true)
                         ->whereRaw("DATE_FORMAT(`date`,'%m-%d') = ?", [$dayMonth]);
                  });
            })->exists();

        return $exists;
    }

    /**
     * Return holidays collection for given iduka and date
     */
    public static function getHolidaysOn($idukaId, $date)
    {
        $dateObj = Carbon::parse($date);

        // Jika hari Minggu, return virtual holiday untuk semua IDUKA
        if ($dateObj->isSunday()) {
            return collect([
                (object)[
                    'id' => 0,
                    'iduka_id' => $idukaId,
                    'date' => $dateObj,
                    'name' => 'Hari Minggu (Libur)',
                    'recurring' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }

        $dayMonth = $dateObj->format('m-d');

        return self::where('iduka_id', $idukaId)
            ->where(function ($q) use ($dateObj, $dayMonth) {
                $q->whereDate('date', $dateObj->toDateString())
                  ->orWhere(function ($q2) use ($dayMonth) {
                      $q2->where('recurring', true)
                         ->whereRaw("DATE_FORMAT(`date`,'%m-%d') = ?", [$dayMonth]);
                  });
            })->get();
    }
}
