<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class JobService
 * @package App\Services
 */
class JobService
{
    const EXECUTING_STATUS = 'executing';
    const STATUS_QUEUED = 'queued';
    const STATUS_FINISHED = 'finished';
    const STATUS_FAILED = 'failed';

    /**
     * @param null $type
     * @return Collection
     */
    public function getExecutingJobs($type = null)
    {
        $qb = DB::table('job_statuses')
            ->whereIn('status', [self::EXECUTING_STATUS, self::STATUS_QUEUED]);

        if ($type) {
            $qb->where('type', $type);
        }

        return $qb->get();
    }

    /**
     * @param null $date
     * @param null $type
     * @param array $status
     * @return Collection
     */
    public function getJobStatuses($date = null, $type = null, $status = [self::EXECUTING_STATUS])
    {
        $qb = DB::table('commission_status')
            ->whereIn('status', $status);

        if ($date) {
            $qb->whereDate('end_date', $date);
        }

        if ($type) {
            $qb->where('commission_type', $type);
        }

        return $qb->get();
    }

    public function getJobStatus($date = null, $type = null)
    {
        $qb = DB::table('commission_status');

        if ($date) {
            $qb->whereDate('end_date', $date);
        }

        if ($type) {
            $qb->where('commission_type', $type);
        }

        $result = $qb->first();

        if ($result) {
            return $result->status;
        }

        return null;
    }

}
