<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use JenkinsKhan\Jenkins;

class JenkinsController extends Controller
{
    private $jenkins;

    public function __construct()
    {
        $login = env('JENKINS_LOGIN');
        $token = env('JENKINS_TOKEN');

        $this->jenkins = new Jenkins("https://$login:$token@jenkins.server-bitjarlabs.com");
        $this->middleware('auth.admin');
    }

    private function getJenkinsRanksCronTimings()
    {
        try {
            $jobConfig = $this->jenkins->getJobconfig('Ranks Cron');
            $jobConfig = str_replace("1.1", "1.0", $jobConfig);
            $xml = simplexml_load_string($jobConfig);
            $triggers = $xml->triggers->xpath('hudson.triggers.TimerTrigger/spec');
            return (string)$triggers[0];
        } catch (\Exception $e) {
            return false;
        }
    }

    public function ranksIndex()
    {
        $login = env('JENKINS_LOGIN');
        $token = env("JENKINS_TOKEN");

        if (!$login || !$token) {
            return response()->json([
                'error' => 1,
                'msg' => 'Invalid jenkins credentials. Please contact Ibuumerang.'
            ])->setStatusCode(500);
        }

        $timer = $this->getJenkinsRanksCronTimings();

        if (!$timer) {
            return response()->json([
                'error' => 1,
                'msg' => 'Unknown issue contacting Jenkins. Please contact Ibuumerang.'
            ])->setStatusCode(500);
        }

        $has2359 = stripos($timer, "\n59 23 * * *") !== false;

        $cronString = $timer;

        if ($has2359) {
            $steps = explode(' ', $timer);
            $cronString = $steps[1];
        }

        $hours = explode(',', $cronString);

        return view('admin.settings.ranks', [
            'hours' => $hours,
            'has2359' => $has2359
        ]);
    }

    public function ranksPost()
    {
        $login = env('JENKINS_LOGIN');
        $token = env("JENKINS_TOKEN");

        if (!$login || !$token) {
            return response()->json([
                'error' => 1,
                'msg' => 'Invalid jenkins credentials. Please contact Ibuumerang.'
            ])->setStatusCode(500);
        }

        $cronTimings = $this->getJenkinsRanksCronTimings();

        if (!$cronTimings) {
            return response()->json([
                'error' => 1,
                'msg' => 'Unknown issue contacting Jenkins. Please contact Ibuumerang.'
            ])->setStatusCode(500);
        }

        $rules = [
            'hour.*' => 'required|integer'
        ];

        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            $errorMessages = $this->generateErrorMessageFromValidator($validator);
            return response()->json([
                'error' => 1,
                'msg' => $errorMessages
            ]);
        }

        $hours = request()->post('hour');
        $add2359 = false;

        if (in_array(24, $hours)) {
            $add2359 = true;
            array_splice($hours, array_search(24, $hours), 1);
        }

        $newCronString = '0 ' . implode(',', $hours) . ' * * *';

        if ($add2359) {
            $newCronString .= "\n59 23 * * *";
        }

        $fullJobConfig = $this->jenkins->getJobconfig('Ranks Cron');
        $fullJobConfig = str_replace($cronTimings, $newCronString, $fullJobConfig);
        $this->jenkins->setJobConfig('Ranks Cron', $fullJobConfig);

        return response()->json([
            'error' => 0,
            'msg' => 'Successfully updated the ranks cron timings'
        ]);
    }
}
