<?php

namespace App\Services\CommissionControlCenter;

use App\BinaryCommissionHistory;
use App\Commission;
use App\Jobs\LeadershipCommission;
use App\Models\CommissionStatus;
use App\PromoCommission;
use App\Services\BinaryCommissionService;
use App\Jobs\BinaryCommission as BinaryCommissionJob;
use App\Services\JobService;
use App\Services\TsbCommissionService;
use App\Services\VibeCommissionService;
use App\Services\UnilevelCommission;
use App\TSBCommission;
use App\User;
use App\VibeCommission;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use \App\Jobs\TsbCommission as TsbCommissionJob;

/**
 * Class CalculateCommissionService
 * @package App\Services
 */
class CalculateCommissionService
{
    const CALCULATE_METHODS_MAP = [
        CommissionControlService::FSB_KEY => 'calculateFsbCommission',
        CommissionControlService::BINARY_KEY => 'calculateBinaryCommission',
        CommissionControlService::UNILEVEL_KEY => 'calculateUnilevelCommission' ,
        CommissionControlService::LEADERSHIP_KEY => 'calculateLeadershipCommission',
        CommissionControlService::TSB_KEY => 'calculateTsbCommission',
        CommissionControlService::PROMO_KEY => 'importPromoCommissions',
        CommissionControlService::VIBE_KEY => 'importVibeCommissions'
    ];

    /** @var BinaryCommissionService */
    private $binaryCommissionService;

    /** @var JobService */
    private $jobService;

    /** @var UnilevelCommission */
    private $unilevelService;

    /** @var \App\Services\LeadershipCommission */
    private $leadershipService;

    /**
     * @var TsbCommissionService
     */
    private $tsbCommissionService;

    /**
     * @var VibeCommissionService
     */
    private $vibeCommissionService;

    /**
     * CalculateCommissionService constructor.
     * @param BinaryCommissionService $binaryCommissionService
     * @param JobService $jobService
     * @param UnilevelCommission $unilevelService
     * @param \App\Services\LeadershipCommission $leadershipService
     * @param TsbCommissionService $tsbCommissionService
     * @param VibeCommissionService $vibeCommissionService
     */
    public function __construct(
        BinaryCommissionService $binaryCommissionService,
        JobService $jobService,
        UnilevelCommission $unilevelService,
        \App\Services\LeadershipCommission $leadershipService,
        TsbCommissionService $tsbCommissionService,
        VibeCommissionService $vibeCommissionService
    ) {
        $this->binaryCommissionService = $binaryCommissionService;
        $this->jobService = $jobService;
        $this->unilevelService = $unilevelService;
        $this->leadershipService = $leadershipService;
        $this->tsbCommissionService = $tsbCommissionService;
        $this->vibeCommissionService = $vibeCommissionService;
    }

    /**
     * @param $commissionType
     * @param Carbon $startDate
     * @param bool $isConfirmAction
     * @param bool $isRecalculate
     * @return mixed
     */
    public function calculate($commissionType, Carbon $startDate, $isConfirmAction, $isRecalculate)
    {
        $method = self::CALCULATE_METHODS_MAP[$commissionType];

        if (!method_exists($this, $method)) {
            return ['message' => 'Commission is not available'];
        }

        return $this->$method($startDate, $isConfirmAction, $isRecalculate);
    }

    /**
     * @param Carbon $startDate
     * @param $isConfirmAction
     * @param $isConfirmRecalculate
     * @return array
     */
    protected function calculateFsbCommission(Carbon $startDate, $isConfirmAction, $isConfirmRecalculate)
    {
        $endDate = $startDate->copy()->endOfWeek();

        Commission::setComEngFromDate($startDate);
        Commission::setComEngToDate($endDate);

        if (!$isConfirmAction) {
            return [
                'isConfirmAction' => true,
                'message' => sprintf(
                    'You are about to calculate FSB commission for the %s - %s period',
                    $startDate->toDateString(),
                    $endDate->toDateString()
                )
            ];
        }

        DB::selectFromWriteConnection("SELECT * FROM calculate_fsb_7levels('" . $startDate . "','" . $endDate . "')");

        return ['message' => 'FSB commission is being calculated'];
    }

    /**
     * Gives an error message or null
     *
     * @param UploadedFile $file
     * @return string|null Returns an error message (string) or null if no issues
     */
    private function validateVibeCsvFile($file)
    {
        $extension = $file->getClientOriginalExtension();

        if ($extension != 'csv') {
            return 'Please upload csv file.';
        }

        if ($file->getSize() == 0) {
            return 'CSV is empty!';
        }

        $file = $file->openFile();
        $headerRow = $file->fgetcsv();

        $headers = [
            'date',
            'ride_id',
            'durationDistanceAmount',
            'TSA #',
            'PAYOUT'
        ];

        foreach ($headers as $header) {
            if (in_array($header, $headerRow)) {
                continue;
            }

            $missingHeaders[] = $header;
        }

        if (!empty($missingHeaders)) {
            $missingHeadersMessage = implode('<br>- ', $missingHeaders);
            return 'CSV is missing the header(s):<br> - ' . $missingHeadersMessage;
        }

        return null;
    }
    /**
     * Gives an error message or null
     *
     * @param UploadedFile $file
     * @return string|null Returns an error message (string) or null if no issues
     */
    private function validatePromoCsvFile($file)
    {
        $extension = $file->getClientOriginalExtension();

        if ($extension != 'csv') {
            return 'Please upload csv file.';
        }

        if ($file->getSize() == 0) {
            return 'CSV is empty!';
        }

        $file = $file->openFile();
        $headerRow = $file->fgetcsv();

        $headers = [
            'distid',
            'Amount'
        ];

        foreach ($headers as $header) {
            if (in_array($header, $headerRow)) {
                continue;
            }

            $missingHeaders[] = $header;
        }

        if (!empty($missingHeaders)) {
            $missingHeadersMessage = implode('<br>- ', $missingHeaders);
            return 'CSV is missing the header(s):<br> - ' . $missingHeadersMessage;
        }

        return null;
    }

    /**
     * Gives an error message or null
     *
     * @param UploadedFile $tsbFile
     * @return string|null Returns an error message (string) or null if no issues
     */
    private function validateTsbFile($tsbFile)
    {
        $extension = $tsbFile->getClientOriginalExtension();

        if ($extension != 'csv') {
            return 'Please upload csv file.';
        }

        if ($tsbFile->getSize() == 0) {
            return 'CSV is empty!';
        }

        $file = $tsbFile->openFile();
        $headerRow = $file->fgetcsv();

        $missingHeaders = $this->tsbCommissionService->checkForMissingHeaders($headerRow);

        if (!empty($missingHeaders)) {
            $missingHeadersMessage = implode('<br>- ', $missingHeaders);
            return 'CSV is either the wrong file or it is missing the header(s):<br> - ' . $missingHeadersMessage;
        }

        return null;
    }

    /**
     * Gives an error message or null
     *
     * @param $startDate
     * @param $endDate
     * @return string|null Returns a message (string) or null if no issues
     */
    private function validateTsbJobStatus($startDate, $endDate)
    {
        $commissionStatus = $this->jobService->getJobStatus(
            $endDate,
            TsbCommissionService::COMMISSION_TYPE
        );

        if ($commissionStatus == JobService::STATUS_QUEUED) {
            return 'TSB commission is in the queue to be calculated';
        }

        if ($commissionStatus == JobService::EXECUTING_STATUS) {
            return 'TSB commission is already being calculated';
        }

        if ($this->tsbCommissionService->isPostedCommission($startDate, $endDate)) {
            return 'The commission for this period was already posted';
        }

        if ($this->tsbCommissionService->isPaidCommission($startDate, $endDate)) {
            return 'The commission for this period was already paid';
        }

        return null;
    }

    /**
     * @param UploadedFile $tsbFile
     * @param $startDate
     * @param $endDate
     */
    private function queueTsbJob($tsbFile, $startDate, $endDate)
    {
        $originalFilename = $tsbFile->getClientOriginalName();
        $originalFilename = date('Y-m-d-h-i-s') . "-" . $originalFilename;

        $filename = public_path('/csv/tsb_commissions_csv/') . $originalFilename;
        $tsbFile->move(public_path('/csv/tsb_commissions_csv/'), $originalFilename);

        TsbCommissionJob::dispatch($startDate, $endDate, $filename)->onQueue('default');

        $status = new CommissionStatus();

        $status->end_date = $endDate;
        $status->commission_type = TsbCommissionService::COMMISSION_TYPE;
        $status->status = JobService::STATUS_QUEUED;

        $status->save();
    }

    private function parseCsv($filename)
    {
        $csv = array_map('str_getcsv', file($filename));

        $headers = $csv[0];

        array_walk($csv, function(&$a) use ($csv, $headers) {
            $a = array_combine($headers, $a);
        });

        array_shift($csv); // remove header row

        return $csv;
    }

    /**
     * @param Carbon $startDate
     * @param bool $isConfirmAction
     * @param bool $isConfirmRecalculate
     * @return array
     */
    protected function importVibeCommissions(Carbon $startDate, $isConfirmAction, $isConfirmRecalculate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $request = request();

        if (!$request->hasFile('file')) {
            return ['error' => 1, 'msg' => 'Please upload a csv file.'];
        }

        /**
         * @var UploadedFile $tsbFile
         */

        $file = $request->file;

        $fileError = $this->validateVibeCsvFile($file);

        if ($fileError != null) {
            return ['error' => 1, 'msg' => $fileError];
        }

        if (!$isConfirmAction) {
            return [
                'isConfirmAction' => true,
                'message' => sprintf(
                    'You are about to import promotional commission for the %s - %s period',
                    $startDate->toDateString(), $endDate->toDateString()
                )
            ];
        }

        $csv = $this->parseCsv($file);
        $numEntries = sizeof($csv);
        $numFailures = 0;

        foreach ($csv as $result) {
            $rideDate = $result['date'];
            $rideDate = substr($rideDate, 0, stripos($rideDate, ' GMT'));
            $rideDate = Carbon::createFromFormat('D M d Y H:i:s', $rideDate);
            $rideId = $result['ride_id'];
            $distId = $result['TSA #'];
            $directPayout = substr($result['PAYOUT'], 1);
            
            $rideCommission = $result['durationDistanceAmount'];

            $user = User::select(['id'])
                ->where('distid', '=', $distId)
                ->first();

            if (!$user) {
                $numFailures++;
                continue;
            }

            $commission = new VibeCommission([
                'user_id' => $user->id,
                'ride_commission' => $rideCommission,
                'direct_payout' => $directPayout,
                'ride_id' => $rideId,
                'driver_id' => isset($result['driverId']) ? $result['driverId'] : null,
                'driver_name' => isset($result['driverName']) ? $result['driverName'] : null,
                'rider_id' => isset($result['riderId']) ? $result['riderId'] : null,
                'rider_name' => isset($result['riderName']) ? $result['riderName'] : null,
                'ride_date' => $rideDate,
                'calculation_date' => now(),
                'paid_date' => $endDate,
                'status' => PromoCommission::CALCULATED_STATUS
            ]);

            $commission->save();
        }

        $numImported = $numEntries - $numFailures;

        return [
            'isConfirmAction' => false,
            'message' => "Imported $numImported / $numEntries entries"
        ];
    }

    /**
     * @param Carbon $startDate
     * @param bool $isConfirmAction
     * @param bool $isConfirmRecalculate
     * @return array
     */
    protected function importPromoCommissions(Carbon $startDate, $isConfirmAction, $isConfirmRecalculate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $request = request();

        if (!$request->hasFile('file')) {
            return ['error' => 1, 'msg' => 'Please upload a csv file.'];
        }

        /**
         * @var UploadedFile $tsbFile
         */

        $file = $request->file;

        $fileError = $this->validatePromoCsvFile($file);

        if ($fileError != null) {
            return ['error' => 1, 'msg' => $fileError];
        }

        if (!$isConfirmAction) {
            return [
                'isConfirmAction' => true,
                'message' => sprintf(
                    'You are about to import promotional commission for the %s - %s period',
                    $startDate->toDateString(), $endDate->toDateString()
                )
            ];
        }

        $csv = $this->parseCsv($file);
        $numEntries = sizeof($csv);
        $numFailures = 0;

        foreach ($csv as $result) {
            $distId = $result['distid'];
            $amount = substr($result['Amount'], 1);

            $user = User::select(['id'])
                ->where('distid', '=', $distId)
                ->first();

            if (!$user) {
                $numFailures++;
                continue;
            }

            $commission = new PromoCommission([
                'user_id' => $user->id,
                'amount' => $amount,
                'paid_date' => $startDate->day(20),
                'created_dt' => $endDate,
                'promo' => request()->promo,
                'status' => PromoCommission::CALCULATED_STATUS
            ]);

            $commission->save();
        }

        $numImported = $numEntries - $numFailures;

        return [
            'isConfirmAction' => false,
            'message' => "Imported $numImported / $numEntries entries"
        ];
    }

    /**
     * @param Carbon $startDate
     * @param bool $isConfirmAction
     * @param bool $isConfirmRecalculate
     * @return array
     */
    protected function calculateTsbCommission(Carbon $startDate, $isConfirmAction, $isConfirmRecalculate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $request = request();

        if (!$request->hasFile('file')) {
            return ['error' => 1, 'msg' => 'Please upload a csv file.'];
        }

        /**
         * @var UploadedFile $tsbFile
         */

        $tsbFile = $request->file;

        $fileError = $this->validateTsbFile($tsbFile);

        if ($fileError != null) {
            return ['error' => 1, 'message' => $fileError];
        }

        $statusMessage = $this->validateTsbJobStatus($startDate, $endDate);

        if ($statusMessage != null) {
            return ['message' => $statusMessage];
        }

        if (!$isConfirmAction) {
            return [
                'isConfirmAction' => true,
                'message' => sprintf(
                    'You are about to calculate TSB commission for the %s - %s period',
                    $startDate->toDateString(), $endDate->toDateString()
                )
            ];
        }

        $this->queueTsbJob($tsbFile, $startDate, $endDate);

        return ['message' => 'TSB commission is being calculated'];
    }

    /**
     * @param Carbon $startDate
     * @param bool $isConfirmAction
     * @param bool $isConfirmRecalculate
     * @return array
     */
    protected function calculateBinaryCommission(Carbon $startDate, $isConfirmAction, $isConfirmRecalculate)
    {
        $endDate = $startDate->copy()->endOfWeek();

        $executingCommission = $this->jobService->getJobStatuses(
            $endDate,
            BinaryCommissionService::COMMISSION_TYPE,
            [JobService::EXECUTING_STATUS]
        );

        if ($executingCommission->isNotEmpty()) {
            return ['message' => 'Dual team commission is being calculated'];
        }

        $queuedCommission = $this->jobService->getJobStatuses(
            $endDate,
            BinaryCommissionService::COMMISSION_TYPE,
            [JobService::STATUS_QUEUED]
        );

        if ($queuedCommission->isNotEmpty()) {
            return ['message' => 'Dual team commission is in the queue to be calculated'];
        }

        if ($this->binaryCommissionService->isPaidCommission($endDate)) {
            return ['message' => 'The commission for this period was already paid'];
        }

        if ($this->unilevelService->isPostedCommission($endDate)) {
            return ['message' => 'The commission for this period was already posted'];
        }

        if ($isConfirmRecalculate) {
            $commission = BinaryCommissionHistory::where('end_date', $endDate)->first();

            if ($commission) {
                $this->binaryCommissionService->clearCommissionHistory($commission);
            }

            BinaryCommissionJob::dispatch($startDate, $endDate, $isConfirmRecalculate)->onQueue('default');

            $status = new CommissionStatus();

            $status->end_date = $endDate;
            $status->commission_type = BinaryCommissionService::COMMISSION_TYPE;
            $status->status = JobService::STATUS_QUEUED;

            $status->save();

            return ['message' => 'Dual team commission is being calculated'];
        }

        $unpaidCommission = $this->binaryCommissionService->getUnpaidCommissions($endDate);

        if ($unpaidCommission->isNotEmpty()) {
            return ['isConfirmRecalculate' => true];
        }

        if (!$isConfirmAction) {
            return [
                'isConfirmAction' => true,
                'message' => sprintf(
                    'You are about to calculate Dual Team commission for the %s - %s period',
                    $startDate->toDateString(), $endDate->toDateString()
                )
            ];
        }

        BinaryCommissionJob::dispatch($startDate, $endDate)->onQueue('default');

        $status = new CommissionStatus();

        $status->end_date = $endDate;
        $status->commission_type = BinaryCommissionService::COMMISSION_TYPE;
        $status->status = JobService::STATUS_QUEUED;

        $status->save();

        return ['message' => 'Dual team commission is being calculated'];
    }

    /**
     * @param Carbon $startDate
     * @param $isConfirmAction
     * @param $isConfirmRecalculate
     * @return array
     */
    protected function calculateUnilevelCommission(Carbon $startDate, $isConfirmAction, $isConfirmRecalculate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $executingCommission = $this->jobService->getJobStatuses(
            $endDate,
            UnilevelCommission::COMMISSION_TYPE,
            [JobService::EXECUTING_STATUS]
        );

        if ($executingCommission->isNotEmpty()) {
            return ['message' => 'Unilevel commission is being calculated'];
        }

        $queuedCommission = $this->jobService->getJobStatuses(
            $endDate,
            UnilevelCommission::COMMISSION_TYPE,
            [JobService::STATUS_QUEUED]
        );

        if ($queuedCommission->isNotEmpty()) {
            return ['message' => 'Unilevel commission is in the queue to be calculated'];
        }

        if ($this->unilevelService->isPaidCommission($endDate)) {
            return ['message' => 'The commission for this period was already paid'];
        }

        if ($this->unilevelService->isPostedCommission($endDate)) {
            return ['message' => 'The commission for this period was already posted'];
        }

        if ($isConfirmRecalculate) {
            \App\Jobs\UnilevelCommission::dispatch($startDate, $endDate, $isConfirmRecalculate)->onQueue('default');

            $status = new CommissionStatus();

            $status->end_date = $endDate;
            $status->commission_type = UnilevelCommission::COMMISSION_TYPE;
            $status->status = JobService::STATUS_QUEUED;

            $status->save();

            return ['message' => 'Unilevel commission is being calculated'];
        }

        $unpaidCommission = $this->unilevelService->getUnpaidCommissions($endDate);

        if ($unpaidCommission->isNotEmpty()) {
            return ['isConfirmRecalculate' => true];
        }

        if (!$isConfirmAction) {
            return [
                'isConfirmAction' => true,
                'message' => sprintf(
                    'You are about to calculate Unilevel commission for the %s - %s period',
                    $startDate->toDateString(),
                    $endDate->toDateString()
                )
            ];
        }

        \App\Jobs\UnilevelCommission::dispatch($startDate, $endDate)->onQueue('default');

        $status = new CommissionStatus();

        $status->end_date = $endDate;
        $status->commission_type = UnilevelCommission::COMMISSION_TYPE;
        $status->status = JobService::STATUS_QUEUED;

        $status->save();

        return ['message' => 'Unilevel commission is being calculated'];
    }


    /**
     * @param Carbon $startDate
     * @param $isConfirmAction
     * @param $isConfirmRecalculate
     * @return array
     */
    protected function calculateLeadershipCommission(Carbon $startDate, $isConfirmAction, $isConfirmRecalculate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $executingCommission = $this->jobService->getJobStatuses(
            $endDate,
            \App\Services\LeadershipCommission::COMMISSION_TYPE,
            [JobService::EXECUTING_STATUS]
        );

        if ($executingCommission->isNotEmpty()) {
            return ['message' => 'Leadership commission is being calculated'];
        }

        $queuedCommission = $this->jobService->getJobStatuses(
            $endDate,
            \App\Services\LeadershipCommission::COMMISSION_TYPE,
            [JobService::STATUS_QUEUED]
        );

        if ($queuedCommission->isNotEmpty()) {
            return ['message' => 'Leadership commission is in the queue to be calculated'];
        }

        if ($this->leadershipService->isPaidCommission($endDate)) {
            return ['message' => 'The commission for this period was already paid'];
        }

        if ($this->unilevelService->isPostedCommission($endDate)) {
            return ['message' => 'The commission for this period was already posted'];
        }

        if ($isConfirmRecalculate) {
            LeadershipCommission::dispatch($startDate, $endDate, $isConfirmRecalculate)->onQueue('default');

            $status = new CommissionStatus();

            $status->end_date = $endDate;
            $status->commission_type = \App\Services\LeadershipCommission::COMMISSION_TYPE;
            $status->status = JobService::STATUS_QUEUED;

            $status->save();

            return ['message' => 'Leadership commission is being calculated'];
        }

        $unpaidCommission = $this->leadershipService->getUnpaidCommissions($endDate);

        if ($unpaidCommission->isNotEmpty()) {
            return ['isConfirmRecalculate' => true];
        }

        if (!$isConfirmAction) {
            return [
                'isConfirmAction' => true,
                'message' => sprintf(
                    'You are about to calculate Leadership commission for the %s - %s period',
                    $startDate->toDateString(),
                    $endDate->toDateString()
                )
            ];
        }

        LeadershipCommission::dispatch($startDate, $endDate)->onQueue('default');

        $status = new CommissionStatus();

        $status->end_date = $endDate;
        $status->commission_type = \App\Services\LeadershipCommission::COMMISSION_TYPE;
        $status->status = JobService::STATUS_QUEUED;

        $status->save();

        return ['message' => 'Leadership commission is being calculated'];
    }
}
